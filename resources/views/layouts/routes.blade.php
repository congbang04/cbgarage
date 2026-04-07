@php
    $appRoutes = [
        'customerUpdated' => route('customer.update'),

        'carStore' => route('car.store'),
        'carOrders' => route('car.carOrders', ['car' => ':id']),
        'carQuotationItems' => route('car.get-quotation-items', ['orderId' => ':orderId']),
        'carDestroy' => route('car.destroy', ['car' => ':id']),
        'carUpdate' => route('car.update', ['car' => ':id']),

        'createBooking' => route('createBooking'),
        'createLock' => route('createLock'),
        'releaseLock' => route('releaseLock'),

        'slotsIndex' => route('slots.index'),

        'customerSearch' => route('customerSearch'),
        'adminStatsApi' => route('admin.stats.api'),

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
        'warehouseGetPendingCount' => route('warehouse.getPendingCount'),

        'warehousePartRequests' => route('warehouse.part-requests'),
        'warehouseCardPartRequests' => route('warehouse.card-part-requests'),
        'warehouseSearchPartRequests' => route('warehouse.card-part-requests'),
        'warehouseGoodsIssue' => route('warehouse.goods-issue', ['partRequestId' => ':partRequestId']),

        'warehouseGoodsIssueTransfer' => route('warehouse.goods-issue-transfer', [
            'stockTransferId' => ':stockTransferId',
        ]),

        'warehouseApprovePartRequest' => route('warehouse.approve-part-request', ['partRequestId' => ':partRequestId']),
        'warehouseRejectPartRequest' => route('warehouse.reject-part-request', ['partRequestId' => ':partRequestId']),
        'warehouseCancelTransferRequest' => route('warehouse.cancel-transfer-request', [
            'stockTransferId' => ':stockTransferId',
        ]),

        'warehouseStockTransfer' => route('warehouse.stock-transfer'),
        'warehouseStockTransferHistory' => route('warehouse.stock-transfer-history'),
        'warehouseFullTransferHistoryTable' => route('warehouse.transfer.history-table'),
        'warehouseCardTransferRequests' => route('warehouse.card-transfer-requests'),
        'warehouseCreateStockTransfer' => route('warehouse.create-stock-transfer'),
        'warehouseGetParts' => route('warehouse.get-parts'),
        'warehouseApproveTransferRequest' => route('warehouse.transfer-request.approve', [
            'stockTransferId' => ':stockTransferId',
        ]),
        'warehouseIncomingTransfers' => route('warehouse.incoming-transfer'),
        'warehouseCompleteTransfer' => route('warehouse.complete-transfer-request', [
            'stockTransferId' => ':stockTransferId',
        ]),

        'chatGetSessions' => route('chat.get-sessions'),
        'chatCreateSession' => route('chat.create-session'),
        'chatGetMessagesBySession' => route('chat.get-messages-by-session', ['sessionId' => ':sessionId']),
        'chatSend' => route('chat.send'),
        'chatDeleteSession' => route('chat.delete-session', ['id' => ':id']),

        'adminAiLogSessions' => route('admin.ai-logs.sessions'),
        'adminAiLogMessages' => route('admin.ai-logs.messages', ['sessionId' => ':sessionId']),
        'adminAiLogDelete' => route('admin.ai-logs.delete', ['sessionId' => ':sessionId']),

        'adminBookingTable' => route('admin.booking.table'),

        'customerBookingTable' => route('customer-bookings.table'),
        'customerBookingCancel' => route('customer-bookings.cancel', ['id' => ':id']),

        'paymentIndex' => route('payment.index'),
        'paymentTable' => route('payment.table'),
        'paymentDetail' => route('payment.detail', ['id' => ':id']),
        'paymentPay' => route('payment.api.pay'),
        'paymentProcessPayment' => route('payment.api.process-payment'),
        'paymentRefund' => route('payment.api.refund'),
        'paymentOrderDetails' => route('payment.api.order-details'),
        'paymentBranches' => route('payment.api.branches'),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
