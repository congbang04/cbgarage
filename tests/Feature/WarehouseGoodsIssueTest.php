<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Part;
use App\Models\Inventory;
use App\Services\PartRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseGoodsIssueTest extends TestCase
{
    use RefreshDatabase;

    protected PartRequestService $partRequestService;

    public function setUp(): void
    {
        parent::setUp();
        $this->partRequestService = app(PartRequestService::class);
    }

    public function test_deduct_inventory_success()
    {
        // Tạo branch
        $branch = Branch::create([
            'name' => 'Test Branch',
            'address' => '123 Test St',
            'phone' => '0123456789',
            'is_central' => true
        ]);

        // Tạo part
        $part = Part::create([
            'sku' => 'TEST-001',
            'name' => 'Test Part',
            'description' => 'Test Description',
            'unit' => 'Cái',
            'price_buy' => 100000,
            'price_sell' => 150000
        ]);

        // Tạo inventory
        $inventory = Inventory::create([
            'part_id' => $part->id,
            'branch_id' => $branch->id,
            'quantity' => 100,
            'min_stock' => 10
        ]);

        $initialQuantity = $inventory->quantity;

        // Test deductInventory method trực tiếp (sử dụng reflection để gọi protected method)
        $reflection = new \ReflectionClass($this->partRequestService);
        $method = $reflection->getMethod('deductInventory');
        $method->setAccessible(true);

        // Gọi deductInventory
        $this->actingAs(auth()->loginUsingId(
            \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'test@test.com',
                'password' => bcrypt('password'),
                'branch_id' => $branch->id,
                'role' => 'warehouse'
            ])->id
        ));

        $method->invoke($this->partRequestService, $part->id, 10, $branch->id);

        // Kiểm tra inventory đã trừ
        $inventory->refresh();
        $this->assertEquals(90, $inventory->quantity);
    }

    public function test_deduct_inventory_insufficient_stock()
    {
        // Tạo branch
        $branch = Branch::create([
            'name' => 'Test Branch',
            'address' => '123 Test St',
            'phone' => '0123456789',
            'is_central' => true
        ]);

        // Tạo part
        $part = Part::create([
            'sku' => 'TEST-002',
            'name' => 'Test Part 2',
            'description' => 'Test Description',
            'unit' => 'Cái',
            'price_buy' => 100000,
            'price_sell' => 150000
        ]);

        // Tạo inventory với số lượng ít
        $inventory = Inventory::create([
            'part_id' => $part->id,
            'branch_id' => $branch->id,
            'quantity' => 5,
            'min_stock' => 10
        ]);

        // Login
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'branch_id' => $branch->id,
            'role' => 'warehouse'
        ]);

        $this->actingAs($user);

        // Test deductInventory method
        $reflection = new \ReflectionClass($this->partRequestService);
        $method = $reflection->getMethod('deductInventory');
        $method->setAccessible(true);

        // Expect exception khi stock không đủ
        $this->expectException(\Exception::class);
        $method->invoke($this->partRequestService, $part->id, 10, $branch->id);
    }
}
