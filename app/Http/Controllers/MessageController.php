<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    /**
     * GET /messages - 收件箱入口
     */
    public function index()
    {
        $currentUserId = Auth::id();
        
        $latestConversation = Message::query()
            ->where('sender_id', $currentUserId)
            ->orWhere('receiver_id', $currentUserId)
            ->latest('timestamp') 
            ->first();

        if ($latestConversation) {
            $contactId = ($latestConversation->sender_id == $currentUserId) 
                ? $latestConversation->receiver_id 
                : $latestConversation->sender_id;
            
            return redirect()->route('messages.show', ['user' => $contactId]);
        }
        
        return redirect()->route('messages.show', ['user' => null]);
    }

    /**
     * GET /messages/{user} - 显示对话。
     */
    public function show(?User $user = null)
    {
        $currentUserId = Auth::id();
        $referrer = request()->headers->get('referer');
        $currentUrl = request()->url();
        
        if ($referrer && $referrer !== $currentUrl && !Str::contains($referrer, '/messages')) {
            session(['message_entry_point' => $referrer]);
        }

        $otherUser = $user;
        $otherUserId = $user ? $user->id : null;
        
        $conversations = $this->getConversationList($currentUserId);

        $messages = collect();
        if ($otherUserId) {
            $messages = Message::query()
                ->where(function ($query) use ($currentUserId, $otherUserId) {
                    $query->where('sender_id', $currentUserId)->where('receiver_id', $otherUserId);
                })
                ->orWhere(function ($query) use ($currentUserId, $otherUserId) {
                    $query->where('sender_id', $otherUserId)->where('receiver_id', $currentUserId);
                })
                ->oldest('timestamp')
                ->get();

            // 标记未读消息为已读
            Message::where('receiver_id', $currentUserId)
                   ->where('sender_id', $otherUserId)
                   ->where('is_read', false)
                   ->update(['is_read' => true]);
        }

        return view('messages.show', compact('conversations', 'messages', 'otherUser'));
    }

    /**
     * POST /messages/{user} - 处理消息发送。
     */
    public function send(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('messages.show', ['user' => $user->id]);
    }


    /**
     * AJAX获取当前对话最新消息 (右侧面板)
     */
    public function fetchLatestMessages(User $user)
    {
        $currentUserId = Auth::id();
        $lastMessageId = (int) request('last_message_id', 0); 

        $query = Message::where(function ($q) use ($user, $currentUserId) {
                $q->where('sender_id', $currentUserId)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($user, $currentUserId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $currentUserId);
            });

        // 核心：只查询 ID 大于已知 ID 的消息
        $newMessages = $query->where('message_id', '>', $lastMessageId) 
            ->orderBy('message_id', 'asc')
            ->get();

        $newLastMessageId = $lastMessageId;
        if ($newMessages->isNotEmpty()) {
            $newLastMessageId = $newMessages->last()->message_id; 
        }
        
        // 标记新接收的消息为已读
        if ($newMessages->isNotEmpty()) {
            Message::whereIn('message_id', $newMessages->pluck('message_id'))
                   ->where('receiver_id', $currentUserId)
                   ->update(['is_read' => true]);
        }
        
        return response()->json([
            'messages' => $newMessages->map(function ($msg) {
                return [
                    'message_id' => $msg->message_id, 
                    'sender_id' => $msg->sender_id,
                    'content' => $msg->content,
                    'timestamp' => $msg->timestamp, 
                ];
            }),
            'last_message_id' => $newLastMessageId 
        ]);
    }

    /**
     * AJAX获取侧边栏对话列表数据 (左侧面板)
     */
    public function fetchConversationList()
    {
        $currentUserId = Auth::id();
        $conversations = $this->getConversationList($currentUserId);

        $formattedConversations = $conversations->map(function ($conv) use ($currentUserId) {
            $contact = ($conv->sender_id == $currentUserId) ? $conv->receiver : $conv->sender;
            return [
                'contact_id' => $contact->id,
                'contact_name' => $contact->name, 
                'last_content' => Str::limit($conv->content, 30),
                'last_timestamp' => $conv->timestamp->format('Y-m-d H:i'),
                'unread_count' => $conv->unread_count,
            ];
        });

        return response()->json([
            'conversations' => $formattedConversations,
            'current_user_id' => $currentUserId,
        ]);
    }

    /**
     * 封装获取对话列表的查询逻辑
     */
    private function getConversationList(int $currentUserId)
{
    // 1. 找到每个对话 (contact_id) 的最新消息 ID
    $latestMessageIds = DB::table('messages')
        ->select(DB::raw('MAX(message_id) as max_message_id'))
        ->where('sender_id', $currentUserId)
        ->orWhere('receiver_id', $currentUserId)
        ->groupBy(DB::raw('CASE WHEN sender_id = ' . $currentUserId . ' THEN receiver_id ELSE sender_id END'))
        ->pluck('max_message_id');

    // 2. 根据这些 ID 获取完整的最新消息记录，并计算未读数
    return Message::query()
        ->select([
            'messages.*',
            DB::raw('CASE WHEN sender_id = ' . $currentUserId . ' THEN receiver_id ELSE sender_id END AS contact_id'),
            // 子查询计算未读数
            DB::raw('(SELECT COUNT(*) FROM messages AS m2 
                     WHERE m2.receiver_id = ' . $currentUserId . ' 
                     AND m2.sender_id = (CASE WHEN messages.sender_id = ' . $currentUserId . ' THEN messages.receiver_id ELSE messages.sender_id END) 
                     AND m2.is_read = 0) AS unread_count')
        ])
        ->whereIn('message_id', $latestMessageIds) // 仅选择最新消息
        ->with(['sender', 'receiver'])
        ->orderBy('timestamp', 'desc')
        ->get();
}
}