<?php

namespace App\Services;

use App\Models\AiChatSession;
use App\Models\AiMessage;
use Illuminate\Support\Facades\DB;

class ChatService
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Lấy danh sách sessions của khách hàng
     */
    public function getSessions($customerId)
    {
        return AiChatSession::where('customer_id', $customerId)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'title', 'updated_at']);
    }

    /**
     * Tạo session mới
     */
    public function createSession($customerId)
    {
        return AiChatSession::create([
            'customer_id' => $customerId,
            'title' => 'Cuộc trò chuyện mới'
        ]);
    }

    /**
     * Lấy danh sách tin nhắn của 1 session (Có validate quyền)
     */
    public function getMessages($sessionId, $customerId)
    {
        AiChatSession::where('id', $sessionId)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        return AiMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender', 'content', 'created_at']);
    }

    /**
     * Xử lý luồng gửi tin nhắn: Lưu User Msg -> Gọi AI -> Lưu AI Msg
     */
    public function processMessage($sessionId, $customerId, $userText)
    {
        // Kiểm tra quyền sở hữu
        $session = AiChatSession::where('id', $sessionId)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Cập nhật title nếu đây là tin nhắn đầu tiên của session
            $msgCount = AiMessage::where('session_id', $sessionId)->count();
            if ($msgCount === 0) {
                $session->update(['title' => mb_substr($userText, 0, 30) . '...']);
            }

            // 1. Lưu tin nhắn của User
            AiMessage::create([
                'session_id' => $sessionId,
                'sender' => 'user',
                'content' => $userText
            ]);

            // Cập nhật thời gian session để đẩy lên đầu danh sách Sidebar
            $session->touch();

            // 2. Lấy Context (Tối đa 20 tin nhắn gần nhất) để gửi cho AI
            $history = AiMessage::where('session_id', $sessionId)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->sender,
                        'content' => $msg->content
                    ];
                })->toArray();

            // 3. Gọi Gemini API Service
            $botReply = $this->geminiService->generateChatResponse($history);

            // 4. Lưu tin nhắn trả về của Bot
            AiMessage::create([
                'session_id' => $sessionId,
                'sender' => 'model',
                'content' => $botReply
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'reply' => $botReply,
                'session_title' => $session->title
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Đẩy lỗi ra cho Controller xử lý (HTTP 500)
        }
    }
}
