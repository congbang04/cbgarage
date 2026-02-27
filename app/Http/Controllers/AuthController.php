<?php

namespace App\Http\Controllers;

use App\Http\Middleware\NoCacheHeaders;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements HasMiddleware

{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public static function middleware(): array
    {
        return [
            // Áp dụng cho các phương thức chỉ dành cho GUEST
            new Middleware(
                // Kết hợp cả hai middleware
                middleware: ['guest', NoCacheHeaders::class],
                // Áp dụng cho tất cả các trang không cần đăng nhập
                only: ['login', 'register', 'storeRegister']
            ),

            // Áp dụng cho các phương thức yêu cầu đã ĐĂNG NHẬP
            new Middleware(
                middleware: 'auth',
                only: ['logout']
            ),
        ];
    }
    public function login()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
    }
    public function store(LoginUserRequest $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'last_login_ip' => $request->ip(),
            'remember_token' => $request->has('remember_token'),
        ];
        try {
            // Thực hiện đăng nhập
            $this->authService->login($data);

            // Lấy thông tin người dùng vừa được xác thực
            $user = Auth::user();

            // Kiểm tra group_role và điều hướng
            if ($user && $user->group_role == 'ADMIN') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Chào mừng Admin, đăng nhập thành công!');
            }
            if ($user && $user->group_role == 'TECHNICIAN') {
                return redirect()->route('toDoList')
                    ->with('success', 'Chào mừng Technician, đăng nhập thành công!');
            }

            // Nếu không phải ADMIN, chuyển hướng đến trang mặc định
            return redirect()->route('home')
                ->with('success', 'Đăng nhập thành công!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except('password'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function storeRegister(RegisterRequest $request)
    {
        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        try {
            $user = $this->authService->confirmedRegister($data);
            event(new Registered($user));
            Auth::login($user);
            return redirect()->route('verification.notice')
                ->with('success', 'Vui lòng kiểm tra email để xác thực tài khoản.');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            // Xóa remember_token trong database
            $user->setRememberToken(null);
            $user->save();
        }
        Auth::logout(); // Đăng xuất user khỏi guard hiện tại

        // Xóa toàn bộ session để tránh session cũ bị tái sử dụng
        $request->session()->invalidate();

        // Tạo lại CSRF token mới để tránh lỗi khi gửi form tiếp theo
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
