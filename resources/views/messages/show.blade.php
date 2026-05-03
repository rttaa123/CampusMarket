<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $otherUser ? '与 ' . $otherUser->username . ' 的聊天' : '我的收件箱' }}</title>
    
    {{-- 注意：如果您项目使用了 Bootstrap 或其他 CSS 框架，请在这里添加链接，或者确保您的主布局文件被继承 --}}

    <style>
        :root{
            --tb-red:#E23729;
            --tb-red-2:#EB483E;
            --tb-red-soft:#FCF1F0;
            --tb-orange:#EC5E29;
            --tb-orange-soft:#FDF1EC;
            --tb-gradient:linear-gradient(90deg,var(--tb-red),var(--tb-orange));
            --tb-border:rgba(226,55,41,.16);
        }

        /* ==================== 基础布局样式 ==================== */
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'PingFang SC', 'Microsoft YaHei', sans-serif; background:
            radial-gradient(circle at 12% 0%, rgba(252,241,240,.95) 0%, rgba(252,241,240,0) 55%),
            radial-gradient(circle at 95% 10%, rgba(253,241,236,.95) 0%, rgba(253,241,236,0) 55%),
            #ffffff; }
        .app-layout { 
            display: flex; 
            height: 100vh; 
            max-width: 1200px; 
            margin: 0 auto; 
            box-shadow: 0 0 20px rgba(0,0,0,0.1); 
            background-color: #ffffff;
            border-radius: 18px;
            overflow: hidden;
        }

        /* ==================== 左侧边栏 (对话列表) ==================== */
        .sidebar { 
            width: 300px; 
            border-right: 1px solid #e0e0e0; 
            overflow-y: auto; 
            background-color: #ffffff; 
            flex-shrink: 0; 
            display: flex; 
            flex-direction: column;
        }
        .sidebar h3 { 
            padding: 15px; 
            margin: 0; 
            border-bottom: 1px solid #e0e0e0; 
            font-size: 1.2em;
            background:
                linear-gradient(90deg, rgba(252,241,240,.95), rgba(253,241,236,.85));
            color: rgba(0,0,0,.80);
        }
        .conversation-item {
            display: block;
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
        }
        .conversation-item:hover {
            background-color: rgba(253,241,236,.55);
        }
        .conversation-item.active {
            background-color: var(--tb-red-soft); /* 选中状态 */
            border-left: 3px solid var(--tb-red);
        }
        .unread-count {
            color: var(--tb-red); /* 红色未读标记 */
            font-weight: bold;
            font-size: 0.9em;
            margin-left: 5px;
        }

        /* ==================== 右侧聊天区 ==================== */
        .chat-area { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }
        .chat-header { 
            padding: 15px; 
            border-bottom: 1px solid #e0e0e0; 
            display: flex; 
            align-items: center; 
            background:
                linear-gradient(90deg, rgba(252,241,240,.85), rgba(253,241,236,.75));
            flex-shrink: 0;
        }
        .back-button {
            background: none;
            border: none;
            color: var(--tb-red);
            cursor: pointer;
            margin-right: 20px;
            font-size: 1em;
        }
        .chat-history { 
            flex-grow: 1; 
            padding: 15px; 
            overflow-y: auto; 
            background-color: #ffffff;
        }
        
        /* 消息气泡 */
        .message-row-wrapper { 
            display: flex; 
            margin-bottom: 15px; 
        }
        .message-content { 
            padding: 10px 14px; 
            border-radius: 18px; 
            max-width: 65%; 
            word-wrap: break-word; 
            line-height: 1.4; 
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            font-size: 0.95em;
        }
        .timestamp {
            font-size: 0.75em;
            color: #999;
            margin-top: 5px;
            display: block;
        }

        /* 接收到的消息 (左侧) */
        .received { 
            justify-content: flex-start; 
        }
        .received .message-content { 
            background-color: #ffffff; 
            border: 1px solid #e0e0e0; 
            color: #333; 
        }
        .received .timestamp { 
            text-align: left; 
        }

        /* 发送的消息 (右侧) */
        .sent { 
            justify-content: flex-end; 
        }
        .sent .message-content { 
            background: var(--tb-gradient);
            color: #fff; 
        }
        .sent .timestamp{
            color: rgba(255,255,255,.78);
        }
        .sent .timestamp { 
            text-align: right; 
        }

        /* 输入框区域 */
        .chat-input {
            border-top: 1px solid #e0e0e0;
            padding: 10px 15px;
            flex-shrink: 0;
            background-color: #fefefe;
            display: flex;
        }
        .chat-input textarea {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            margin-right: 10px;
            font-size: 1em;
        }
        .chat-input button {
            padding: 8px 20px;
            background: var(--tb-gradient);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s;
        }
        .chat-input button:hover {
            filter: brightness(.98);
        }
    </style>
    
    <script>

        ///

       // 确保 $messages 是有效的 Laravel 集合，且每个元素都有 message_id
const otherUserId = {{ optional($otherUser)->id ?? 'null' }};

if (otherUserId) {
    // 1. 【强化初始化】：如果 $messages 集合为空，则 lastMessageId = 0
    let lastMessageId = 0;
    
    @if ($messages->isNotEmpty())
        // 如果 $messages 集合不为空，获取最后一条消息的 ID
        lastMessageId = {{ $messages->last()->message_id }};
    @endif
    
    // 调试：首次加载时查看 ID
    console.log("Initial Last Message ID:", lastMessageId); 

    function fetchNewMessages() {
        // 构造 AJAX URL，传递当前最新的 last_message_id
        const url = "{{ route('messages.latest', ['user' => optional($otherUser)->id]) }}" + `?last_message_id=${lastMessageId}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // 【关键点 1】：检查是否有新消息
                if (data.messages && data.messages.length > 0) {
                    const chatHistory = document.getElementById('chatHistory');
                    let scrolledToBottom = chatHistory.scrollHeight - chatHistory.clientHeight <= chatHistory.scrollTop + 1;

                    data.messages.forEach(msg => {
                        // 2. 【强化去重】：检查 DOM 中是否已经存在该 ID 的元素
                        if (document.getElementById(`msg-${msg.message_id}`)) {
                            console.log(`Message ID ${msg.message_id} already exists, skipping.`);
                            return; // 跳过此消息，防止重复打印
                        }
                        
                        // 3. 构建并插入 HTML
                        const isSender = (msg.sender_id == {{ Auth::id() }});
                        const className = isSender ? 'sent' : 'received';
                        
                        const messageHtml = `
                            <div id="msg-${msg.message_id}" class="message-row-wrapper ${className}">
                                <div class="message-row">
                                    <div class="message-content">
                                        ${msg.content.replace(/\n/g, '<br>')}
                                        <div class="timestamp">${msg.timestamp}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        chatHistory.insertAdjacentHTML('beforeend', messageHtml);
                    });
                    
                    // 4. 【强制更新】：使用服务器返回的最新 ID 更新客户端变量
                    lastMessageId = data.last_message_id; 
                    console.log("Updated Last Message ID:", lastMessageId); 

                    // 5. 滚动到底部（只有用户在底部时才自动滚动）
                    if (scrolledToBottom) {
                       chatHistory.scrollTop = chatHistory.scrollHeight;
                    }
                } else if (data.last_message_id) {
                    // 如果服务器返回了 last_message_id 但没有消息，我们仍然更新客户端 ID
                    // 这对于修复可能的时间/ID偏差非常重要
                    lastMessageId = data.last_message_id; 
                }
            })
            .catch(error => console.error('Error during polling:', error));
    }

    // 每 3 秒执行一次轮询
    setInterval(fetchNewMessages, 3000); 
}
        
        
        ///



        document.addEventListener('DOMContentLoaded', function() {
            var history = document.getElementById('chatHistory');
            // 自动滚动到底部
            if (history) {
                history.scrollTop = history.scrollHeight;
            }
        });
        function goBack() {
            // 返回上一页
            //window.history.back();
            // 使用纯 PHP 块来构造跳转逻辑，避免 Blade 编译器的兼容性问题
        <?php
            // 1. 获取入口点 URL
            $entryPoint = session('message_entry_point');
            // 2. 获取收件箱列表 URL
            $indexRoute = route('messages.index'); 
        ?>
        
        // 3. 将 PHP 变量安全地传递给 JavaScript
        const entryPoint = '<?php echo $entryPoint; ?>';
        const indexRoute = '<?php echo $indexRoute; ?>';

        if (entryPoint && entryPoint.length > 0) {
            // 如果存在入口点 (例如用户详情页)，直接跳转并替换当前历史记录
            window.location.replace(entryPoint); 
        } else {
            // 如果没有入口点，则跳转到收件箱列表
            window.location.href = indexRoute;
        }
        
        // 阻止任何其他默认行为
        return false;
        }
    </script>
</head>
<body>



<div class="app-layout">
    <div class="sidebar">
        <h3>我的对话</h3>
        @forelse ($conversations as $msg)
            @php
                $contactId = $msg->contact_id;
                // 从 sender 或 receiver 中获取联系人名称
                $contact = ($msg->sender_id == Auth::id()) 
                    ? $msg->receiver 
                    : $msg->sender;
                
                $contactName = optional($contact)->username ?? optional($contact)->name ?? '未知用户';
                
                $isActive = ($contactId == optional($otherUser)->id) ? 'active' : '';
                $unreadCount = $msg->unread_count;
            @endphp
            <a href="{{ route('messages.show', ['user' => $contactId]) }}" 
               class="conversation-item {{ $isActive }}">
                <div style="font-size: 1.1em; font-weight: {{ $isActive ? 'bold' : 'normal' }};">
                    {{ $contactName }}
                    @if ($unreadCount > 0) 
                        <span class="unread-count">({{ $unreadCount }})</span> 
                    @endif
                </div>
                {{-- 显示最新消息的片段 --}}
                <small style="color: #666; display: block; margin-top: 3px;">
                    {{ Str::limit($msg->content, 25) }}
                </small>
            </a>
        @empty
            <p style="padding: 15px; color: #888;">没有对话记录</p>
        @endforelse
    </div>

    <div class="chat-area">
        @if (!$otherUser)
            <div style="padding: 20px; text-align: center; margin-top: 100px;">
                <h2>站内信</h2>
                <p>请在左侧选择一个对话，或从其他用户的详情页发起新的对话。</p>
            </div>
        @else
            <div class="chat-header">
                {{-- 假设用户从详情页跳转过来，提供返回按钮 --}}
                <button class="back-button" onclick="goBack()">
                    &lt; 返回
                </button>
                <h3>与 {{ $otherUser->username ?? $otherUser->name }} 的聊天</h3>
            </div>
            
            <div id="chatHistory" class="chat-history">
                @forelse ($messages as $msg)
                    @php
                        $isSender = ($msg->sender_id == Auth::id());
                        $class = $isSender ? 'sent' : 'received';
                    @endphp
                        {{-- 【关键修正：确保每个初始消息气泡都有唯一的 ID】 --}}
                    <div id="msg-{{ $msg->message_id }}" class="message-row-wrapper {{ $class }}">
                        <div class="message-row">
                            <div class="message-content">
                                {{ nl2br(e($msg->content)) }}
                                <div class="timestamp">
                                    {{ $msg->timestamp }}
                                    {{-- 可以使用 Carbon 格式化时间，例如：$msg->timestamp->diffForHumans() --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #888;">暂无消息，开始对话吧！</p>
                @endforelse
            </div>

            <div class="chat-input">
                <form method="POST" action="{{ route('messages.send', ['user' => $otherUser->id]) }}" style="display: flex; width: 100%;">
                    @csrf 
                    <textarea name="content" rows="2" required placeholder="输入消息..." autofocus></textarea>
                    <button type="submit">发送</button>
                </form>
            </div>
        @endif
    </div>
</div>

</body>
</html>