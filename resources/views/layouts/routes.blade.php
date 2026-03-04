@php
    $appRoutes = [
        'customerUpdated' => route('customer.update'),
        'carStore' => route('car.store'),
        'carDestroy' => route('car.destroy', ['car' => ':id']),
        'carUpdate' => route('car.update', ['car' => ':id']),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
