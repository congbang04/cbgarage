<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected $geminiService;
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    // Hiển thị trang chat full
    public function index()
    {
        return view('customer.chat');
    }

    // API nhận tin nhắn và trả về kết quả
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $botReply = $this->geminiService->generateChatResponse($request->message);

        return response()->json([
            'status' => 'success',
            'reply' => $botReply
        ]);
    }
}
