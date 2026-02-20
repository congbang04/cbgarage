<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function confirmedRegister(array $registerData)
    {
        return DB::transaction(function () use ($registerData) {
            try {
                // Tạo User bằng Eloquent
                $user = User::create([
                    'email'      => $registerData['email'],
                    'password'   => Hash::make($registerData['password']),
                    'role' => 'CUSTOMER',
                    'is_active'  => 1,
                    'is_delete'  => 0
                ]);

                // Dùng quan hệ để tạo Customer liên kết
                $user->customer()->create([
                    'name' => $registerData['customer_name'],
                ]);

                // Trả về User đã tạo (đã bao gồm customer)
                return $user->load('customer');
            } catch (Exception $e) {
                throw new Exception("Lỗi khi tạo user và customer: " . $e->getMessage());
            }
        });
    }
    public function login(array $credentials)
    {
        // Tìm user theo email
        $user = User::where('email', $credentials['email'])->first();
        // Kiểm tra user tồn tại không
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản không tồn tại.',
            ]);
        }
        // Kiểm tra tài khoản bị xóa
        if ($user->is_delete == 1) {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản này đã bị xóa hoặc vô hiệu hóa.',
            ]);
        }
        // Kiểm tra tài khoản bị khóa
        if ($user->is_active == 0) {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ]);
        }
        // Kiểm tra mật khẩu
        if (!Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Mật khẩu không đúng.',
            ]);
        }
        $remember = $credentials['remember_token'];
        // ✅ Nếu tất cả điều kiện đúng, tiến hành đăng nhập
        Auth::login($user, $remember);
        // Cập nhật thông tin đăng nhập
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $credentials['last_login_ip'],
        ]);
    }
}
