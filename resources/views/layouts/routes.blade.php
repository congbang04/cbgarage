@php
    $appRoutes = [
        'customerUpdated' => route('customer.update'),

        'carStore' => route('car.store'),
        'carDestroy' => route('car.destroy', ['car' => ':id']),
        'carUpdate' => route('car.update', ['car' => ':id']),

        'createBooking' => route('createBooking'),
        'createLock' => route('createLock'),
        'releaseLock' => route('releaseLock'),

        'slotsIndex' => route('slots.index'),

        'customerSearch' => route('customerSearch'),

        'serviceList' => route('serviceList'),
        'serviceStore' => route('services.store'),
        'serviceDestroy' => route('services.destroy', ['service' => ':service']),
        'serviceUpdate' => route('services.update', ['service' => ':service']),

        'partList' => route('partList'),
        'partsJson' => route('partsJson'),
        'partStore' => route('parts.store'),
        'partDestroy' => route('parts.destroy', ['part' => ':part']),
        'partUpdate' => route('parts.update', ['part' => ':part']),

        'branchList' => route('branchList'),
        'branchStore' => route('branches.store'),
        'branchDestroy' => route('branches.destroy', ['branch' => ':branch']),
        'branchUpdate' => route('branches.update', ['branch' => ':branch']),

        'staffList' => route('staff.list'),
        'staffStore' => route('staff.store'),
        'staffUpdate' => route('staff.update', ['staff' => ':staff']),
        'staffDestroy' => route('staff.destroy', ['staff' => ':staff']),
        'staffToggleStatus' => route('staff.toggle-status', ['staff' => ':staff']),

        'receptionStore' => route('reception.store'),
        'tableList' => route('table'),

        'receptionServices' => route('reception.services'),
        'receptionParts' => route('reception.parts'),
        'receptionTechnicians' => route('reception.technicians'),
        'receptionSaveQuotation' => route('reception.save-quotation', ['orderId' => ':orderId']),
        'receptionQuotationItems' => route('reception.quotation-items', ['orderId' => ':orderId']),

        'technicianJobsTable' => route('technician.jobs.table'),
        'technicianJobsUpdateStatus' => route('technician.jobs.updateStatus', ['orderId' => ':orderId']),
        'technicianJobsCompleteItem' => route('technician.jobs.completeItem', ['orderId' => ':orderId']),
        'technicianJobsRequestPart' => route('technician.jobs.requestPart', ['orderId' => ':orderId']),

        'warehouseInventory' => route('warehouse.inventory'),
        'warehouseInventoryList' => route('warehouse.inventory-list'),
        'warehousePartRequests' => route('warehouse.part-requests'),
        'warehouseCardPartRequests' => route('warehouse.card-part-requests'),
        'warehouseGoodsIssue' => route('warehouse.goods-issue', ['partRequestId' => ':partRequestId']),
        'warehouseApprovePartRequest' => route('warehouse.approve-part-request', ['partRequestId' => ':partRequestId']),
        'warehouseRejectPartRequest' => route('warehouse.reject-part-request', ['partRequestId' => ':partRequestId']),
        'warehouseStockTransfer' => route('warehouse.stock-transfer'),
        'warehouseCreateStockTransfer' => route('warehouse.create-stock-transfer'),
        'warehouseGetParts' => route('warehouse.get-parts'),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
