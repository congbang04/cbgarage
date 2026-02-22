<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
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
        Auth::logout(); // Đăng xuất user khỏi guard hiện tại

        // Xóa toàn bộ session để tránh session cũ bị tái sử dụng
        $request->session()->invalidate();

        // Tạo lại CSRF token mới để tránh lỗi khi gửi form tiếp theo
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
