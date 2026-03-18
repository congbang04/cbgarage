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
        'partStore' => route('parts.store'),
        'partDestroy' => route('parts.destroy', ['part' => ':part']),
        'partUpdate' => route('parts.update', ['part' => ':part']),

        'branchList' => route('branchList'),
        'branchStore' => route('branches.store'),
        'branchDestroy' => route('branches.destroy', ['branch' => ':branch']),
        'branchUpdate' => route('branches.update', ['branch' => ':branch']),

        'receptionStore' => route('reception.store'),
        'tableList' => route('table'),

        'receptionServices' => route('reception.services'),
        'receptionParts' => route('reception.parts'),
        'receptionTechnicians' => route('reception.technicians'),
        'receptionSaveQuotation' => route('reception.save-quotation', ['orderId' => ':orderId']),
        'receptionQuotationItems' => route('reception.quotation-items', ['orderId' => ':orderId']),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
