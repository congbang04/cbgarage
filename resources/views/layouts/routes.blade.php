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
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
