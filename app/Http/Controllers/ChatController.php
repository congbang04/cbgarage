<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }
    // Hiển thị trang chat full
    public function index()
    {
        return view('customer.chat');
    }

    private function getCustomerId()
    {
        return auth()->user()->customer->id ?? null;
    }

    // 1. Lấy danh sách sessions
    public function getSessions()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Tài khoản không có quyền sử dụng chức năng này.'], 403);

        // Gọi Service xử lý Data
        $sessions = $this->chatService->getSessions($customerId);

        return response()->json(['sessions' => $sessions]);
    }

    // 2. Tạo session mới
    public function createSession()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Tài khoản không có quyền sử dụng chức năng này.'], 403);

        $session = $this->chatService->createSession($customerId);

        return response()->json(['session' => $session]);
    }

    // 3. Lấy tin nhắn của 1 session
    public function getMessages($sessionId)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Tài khoản không có quyền sử dụng chức năng này.'], 403);

        try {
            // Gọi Service xử lý Data
            $messages = $this->chatService->getMessages($sessionId, $customerId);
            return response()->json(['messages' => $messages]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Không tìm thấy session hoặc không có quyền truy cập.'], 404);
        }
    }

    // 4. Gửi tin nhắn và gọi AI
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:ai_chat_sessions,id',
            'message' => 'required|string|max:1000'
        ]);

        $customerId = $this->getCustomerId();
        if (!$customerId) return response()->json(['error' => 'Tài khoản không có quyền sử dụng chức năng này.'], 403);

        try {
            $result = $this->chatService->processMessage(
                $request->session_id,
                $customerId,
                $request->message
            );

            return response()->json($result);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Session không hợp lệ hoặc không có quyền truy cập.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã có lỗi xảy ra khi lưu trữ tin nhắn.'], 500);
        }
    }
}
