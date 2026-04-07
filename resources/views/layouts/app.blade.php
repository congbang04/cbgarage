<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tiêu đề mẫu')</title>

    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- font-awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- AOS animation --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"> --}}

    @include('layouts.routes')
    {{-- nhúng file css và js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('vite')
    @yield('styles')

    <!-- Cropper.js for image cropping -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
</head>

<body class="@yield('body-class')">
    @yield('header')

    @yield('content')

    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        // Khởi tạo thư viện Animation
        AOS.init({
            once: true, // Animation chỉ chạy 1 lần khi cuộn tới
            offset: 100, // Điểm kích hoạt animation (px)
            duration: 800, // Thời gian chạy animation (ms)
            easing: 'ease-out-cubic',
        });
    </script>

    <script>
        const Toast = Swal.mixin({
            toast: true, // Kích hoạt chế độ toast (thông báo nhỏ)
            position: "top-end", // Vị trí ở góc trên bên phải
            showConfirmButton: false, // Ẩn nút OK
            timer: 1500, // Tự động đóng sau 1.5 giây
            timerProgressBar: true, // Hiển thị thanh thời gian đếm ngược
            didOpen: (toast) => {
                toast.parentElement.style.top = "60px";
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success', // Icon thành công
                title: '{{ session('success') }}' // Lấy nội dung từ session
            });
        @endif
    </script>

    @stack('scripts')
</body>

</html>
