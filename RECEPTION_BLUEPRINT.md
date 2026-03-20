# Reception Feature - Architecture Blueprint for Technician Implementation

## Quick File Reference

### BACKEND (app/)

| File                                         | Purpose                          | Key Methods                                                                                         |
| -------------------------------------------- | -------------------------------- | --------------------------------------------------------------------------------------------------- |
| **Http/Controllers/ReceptionController.php** | HTTP request handler             | `list()`, `create()`, `storeReception()`, `getServices()`, `getTechnicians()`, `saveQuotation()`    |
| **Http/Requests/ReceptionRequest.php**       | Input validation & authorization | `rules()`, `messages()`, `authorize()`                                                              |
| **Services/ReceptionService.php**            | Business logic, database queries | `store()`, `getOrders()`, `searchOrders()`, `getTechnicians()`, `saveQuotation()`, `getQuotation()` |
| **Models/Order.php**                         | Order domain model               | Constants for status lifecycle, relationships to Customer, Car, Technician                          |

### FRONTEND (resources/)

| File                                                   | Purpose                          | Key Functions                                                             |
| ------------------------------------------------------ | -------------------------------- | ------------------------------------------------------------------------- |
| **js/modules/reception.js**                            | Main event handlers & form logic | `loadOrders()`, form submissions, customer search, pagination             |
| **js/modules/reception/quotation.js**                  | Quotation specific UI logic      | Search items, add/remove details, calculate totals                        |
| **js/services/reception-api.js**                       | API wrapper for core features    | `customerSearch()`, `receptionStore()`, `tableList()`                     |
| **js/services/reception-quotation-api.js**             | API wrapper for quotation        | `getServices()`, `getParts()`, `getTechnicians()`, `saveQuotation()`      |
| **views/admin/reception/list.blade.php**               | Orders listing page              | Filters, table container #table-data, create button                       |
| **views/admin/reception/create.blade.php**             | Create order form                | Customer search, car selection, customer info, reception info             |
| **views/admin/reception/quotation.blade.php**          | Quotation form                   | Modals for services/parts, item list, calculations, technician assignment |
| **views/admin/reception/partial-table-list.blade.php** | Table HTML for AJAX              | Bootstrap table with status badges, pagination                            |

### ROUTES

**Route Prefix**: `/reception`

| Method | Endpoint                     | Handler               | Returns                                       |
| ------ | ---------------------------- | --------------------- | --------------------------------------------- |
| GET    | `/`                          | `list()`              | Blade view admin.reception.list               |
| GET    | `/create`                    | `create()`            | Blade view admin.reception.create with brands |
| GET    | `/partial/table-list`        | `tableList()`         | HTML partial with filtered orders             |
| POST   | `/store`                     | `storeReception()`    | JSON { message, order_id }                    |
| GET    | `/quotation`                 | `quotation()`         | Blade view admin.reception.quotation          |
| GET    | `/services`                  | `getServices()`       | JSON array of services                        |
| GET    | `/parts`                     | `getParts()`          | JSON array of parts                           |
| GET    | `/technicians`               | `getTechnicians()`    | JSON array of technicians (branch-filtered)   |
| POST   | `/quotation/{orderId}`       | `saveQuotation()`     | JSON { message, quotation }                   |
| GET    | `/quotation/{orderId}`       | `showQuotation()`     | JSON order data                               |
| GET    | `/quotation/{orderId}/items` | `getQuotationItems()` | HTML partial with order items                 |

---

## Architecture Patterns Used

### 1. Service Layer Pattern

```php
// Business logic lives in Service
$order = $this->receptionService->store($validated);

// Service wraps in DB::transaction for consistency
public function store(array $data) {
    return DB::transaction(function () use ($data) {
        // Create dependent entities
        // Maintain data integrity
    });
}

// Service filters by branch (scope)
$orders = Order::where('branch_id', auth()->user()->branch_id)->get();
```

### 2. Thin Controller Pattern

```php
public function storeReception(ReceptionRequest $request) {
    $data = $request->validated();  // Validation handled by FormRequest
    $order = $this->receptionService->store($data);  // Business logic in service
    return response()->json($order, 201);  // Simple response formatting
}
```

### 3. Frontend API Service Layer

```javascript
// Separation of concerns: API wrapper doesn't know about UI
export function receptionStore(data) {
    return $.ajax({
        url: window.routes.receptionStore,
        method: "POST",
        data: data,
    });
}

// UI Module handles the response
receptionStore(formData)
    .done((res) =>
        Swal.fire({
            /* success */
        }),
    )
    .fail((xhr) => showValidationErrors(xhr.responseJSON.errors));
```

### 4. Eager Loading for Performance

```php
$orders = Order::with([
    'customer:id,name,phone',
    'car:id,brand_id,license_plate',
    'car.brand:id,name',
    'technician:id,name'
])->paginate();
```

### 5. Blade Partial Views

```php
// Reusable table partial for AJAX updates
return view('admin.reception.partial-table-list', compact('orders'));

// In JavaScript
tableList(url, filters).done(html => $("#table-data").html(html));
```

### 6. Polymorphic Relations for OrderDetail

```php
// One OrderDetail can reference Service OR Part
$detail->item_type;  // "App\Models\Service" or "App\Models\Part"
$detail->item_id;    // Service.id or Part.id
$detail->item;       // Works with both though defined as polymorphic
```

---

## Key Data Flows

### Create Order (Reception Intake)

```
User Form → reception.js:$("#create-reception").submit()
  → receptionStore(formData)
  → POST /reception/store
  → ReceptionController::storeReception(ReceptionRequest $request)
  → ReceptionService::store($data)
    - Create Customer via CustomerService
    - Create Car via CarService
    - Create Order with status=RECEIVED
  → return JSON { message, order_id }
  → Frontend: Show success alert, reset form
```

### Save Quotation with Items

```
User adds services/parts via modals → quotation.js
  → saveQuotation(orderId, details, technicianId)
  → POST /reception/quotation/{orderId}
  → ReceptionController::saveQuotation(Request, $orderId)
  → ReceptionService::saveQuotation($order, $details, $technicianId)
    - DB::transaction:
      - Delete existing OrderDetail records
      - Create OrderDetail for each item
      - Calculate subtotal + VAT (8%)
      - Update Order: status=QUOTED, technician_id, total_amount
  → return JSON { message, quotation }
```

### Filter Orders List

```
User types keyword/selects filters → reception.js:initReceptionSearch()
  → Debounce 500ms (keyword only)
  → loadOrders()
  → tableList(url, { keyword, status, date })
  → GET /reception/partial/table-list?keyword=&status=&date=
  → ReceptionController::tableList(Request $request)
  → ReceptionService::searchOrders($filters) or getOrders()
    - Filter by branch_id (auth user)
    - Apply whereLike filters
    - Paginate
  → return view('partial-table-list', compact('orders'))
  → Frontend: Append HTML to #table-data
```

---

## Authorization Model (Current & Issues)

### Current Approach

- **Implicit**: Uses `auth()->user()->branch_id` in service layer
- **Location**: ReceptionService methods filter by branch
- **Controller**: ReceptionRequest::authorize() returns true (no check)
- **Routes**: No middleware restrictions

### Problem

- Any authenticated user can access reception routes
- No role-based access control
- Should restrict to 'receptionist' or 'admin' role

### Recommendation

```php
// Add to routes/web.php
Route::middleware(['auth', 'role:receptionist,admin'])->prefix('reception')->group(function () {
    // Reception routes here
});

// Or add to ReceptionRequest
public function authorize(): bool {
    return auth()->check() && in_array(auth()->user()->role, ['receptionist', 'admin']);
}
```

---

## Database Relationships

```
User (authenticated user)
  └─ branch_id → Branch (all queries filtered by this)

Order (main entity)
  ├─ customer_id → Customer
  ├─ car_id → Car
  ├─ user_id → User (who created receipt)
  ├─ branch_id → Branch
  ├─ technician_id → Technician (assigned later)
  ├─ status → enum(RECEIVED, QUOTED, IN_PROGRESS, WAITING_PART, COMPLETED, PAID, CANCELLED)
  └─ details → OrderDetail[] (polymorphic)

OrderDetail (order breakdown)
  ├─ order_id → Order
  ├─ item_type → string("App\Models\Service" | "App\Models\Part")
  ├─ item_id → integer (Service.id or Part.id)
  ├─ quantity → decimal
  ├─ price → decimal
  ├─ discount → decimal (percentage)
  └─ status → enum(PENDING, COMPLETED, CANCELLED)

Customer
  ├─ id, name, phone, address
  └─ cars → Car[]

Car
  ├─ customer_id → Customer
  ├─ brand_id → Brand
  └─ car_model_id → CarModel

Technician
  ├─ user_id → User
  └─ user.branch_id (for filtering)

Service
  └─ id, name, price

Part
  └─ id, sku, name, price_sell, unit
```

---

## Form Validation Rules (ReceptionRequest)

```javascript
{
  // Customer (Required)
  name: "required|string|max:255",
  phone: "required|string|max:20",
  address: "nullable|string|max:255",

  // Car (Required)
  license_plate: "required|string|max:20",
  manufacture_year: "required|digits:4",
  color: "required|string|max:50",

  // Car (Optional)
  brand_id: "nullable|exists:brands,id",
  car_model_id: "nullable|exists:car_models,id",
  vin_number: "nullable|string|max:50",

  // Reception Info
  current_odo: "nullable|integer|min:0",
  fuel_range: "nullable|integer|min:0|max:100",
  description: "nullable|string",
  note: "nullable|string",
  booking_id: "nullable|integer"
}
```

---

## Frontend State & Globals

### window.routes (injected from routes.blade.php)

```javascript
{
  customerSearch: "/customer-search",
  receptionStore: "/reception/store",
  tableList: "/reception/partial/table-list",
  receptionServices: "/reception/services",
  receptionParts: "/reception/parts",
  receptionTechnicians: "/reception/technicians",
  receptionSaveQuotation: "/reception/quotation/:orderId"
}
```

### window.brands (injected from controller)

```javascript
[
    {
        id: 1,
        name: "Toyota",
        car_models: [
            { id: 1, name: "Camry" },
            { id: 2, name: "Corolla" },
        ],
    },
    // ... more brands
];
```

---

## Frontend Event Handling Patterns

### Search with Debounce

```javascript
let debounceTimer;
$(document).on("input", 'input[name="keyword"]', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        loadOrders();
    }, 500);
});
```

### Cascading Dropdowns

```javascript
$(document).on("change", "#brand_id", function () {
    const brandId = $(this).val();
    const selectedBrand = window.brands.find((b) => b.id == brandId);

    // Update car model options
    selectedBrand.car_models.forEach((model) => {
        $("#car_model_id").append(
            `<option value="${model.id}">${model.name}</option>`,
        );
    });
});
```

### Form Submission with Validation

```javascript
$(document).on("submit", "#create-reception", function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    receptionStore(formData)
        .done((res) => showSuccess(res.message))
        .fail((xhr) => {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, $(this));
            }
        });
});
```

### AJAX Pagination

```javascript
$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    loadOrders($(this).attr("href")); // Pass URL with page parameter
});
```

---

## Testing Checklist

- [ ] ReceptionController dependency injection works
- [ ] ReceptionRequest validates all fields correctly
- [ ] ReceptionService::store() creates Customer → Car → Order in transaction
- [ ] ReceptionService filters orders by auth user's branch_id
- [ ] ReceptionController::tableList() returns partial-table-list.blade.php
- [ ] reception.js loadOrders() populates #table-data
- [ ] reception.js search debounces correctly (500ms)
- [ ] Form submit sends data to backend correctly
- [ ] Validation errors display under form fields
- [ ] Pagination links work and preserve filters
- [ ] Customer search populates form and shows previous cars
- [ ] Brand dropdown populates car model dropdown
- [ ] Quotation save calculates 8% VAT correctly
- [ ] Technician filter in getTechnicians() returns only branch users

---

## Summary - Apply for Technician Feature

To implement Technician feature using this blueprint:

1. **Create TechnicianController** - Follow ReceptionController structure
    - Inject TechnicianService
    - Return views or JSON

2. **Create TechnicianService** - Follow ReceptionService pattern
    - All queries in service methods
    - Filter by branch_id
    - Use DB::transaction for multi-step operations
    - Eager load relationships

3. **Create TechnicianRequest** - Follow ReceptionRequest
    - Define validation rules
    - Custom error messages
    - authorize() method

4. **Create routes** - Use same prefix pattern
    - /technician prefix
    - CRUD endpoints + API endpoints

5. **Create views** - Follow reception view structure
    - list.blade.php with filters
    - create.blade.php form
    - partial-table-list.blade.php
    - Reusable partials

6. **Create JS modules** - Follow reception.js pattern
    - Main module with event handlers
    - Separate API service with $.ajax wrappers
    - Debounce for search
    - Promise .done()/.fail() handling

7. **Follow patterns consistently**:
    - Service layer handles all logic
    - Controllers are thin HTTP handlers
    - Frontend API services are pure wrappers
    - Use Blade partials for reusable HTML
    - Filter by branch in service layer
    - Use eager loading to prevent N+1
    - Wrap multi-step operations in DB::transaction
