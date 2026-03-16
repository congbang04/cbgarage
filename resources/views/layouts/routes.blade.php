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
        'receptionStore' => route('reception.store'),
        'tableList' => route('table'),
        'serviceList' => route('serviceList'),
        'serviceStore' => route('services.store'),
        'serviceDestroy' => route('services.destroy', ['service' => ':service']),
        'serviceUpdate' => route('services.update', ['service' => ':service']),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
