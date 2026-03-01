@php
    $appRoutes = [
        'customerUpdated' => route('customer.update'),
        'carStore' => route('car.store'),
        'carDestroy' => route('car.destroy', ['car' => ':id']),
    ];
@endphp

<script>
    window.routes = @json($appRoutes);
</script>
