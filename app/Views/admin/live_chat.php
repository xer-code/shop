<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
        <h3 class="text-lg font-bold text-white">💬 Live Chat Messages</h3>
        <p class="text-xs text-gray-500">View and reply to customer live chat conversations in real-time</p>
    </div>

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; min-height: 550px;">
        <!-- Left: Conversation List -->
        <div style="background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 0.85rem 1rem; border-bottom: 1px solid #2a2a2a; font-size: 0.75rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.06em;">
                Conversations (<?= count($conversations) ?>)
            </div>
            <div id="conversationList" style="flex: 1; overflow-y: auto;">
                <?php if (empty($conversations)): ?>
                    <div style="padding: 2rem; text-align: center; color: #555; font-size: 0.8rem;">No chat conversations found.</div>
                <?php else: ?>
                    <?php foreach ($conversations as $conv): ?>
                        <?php 
                        $linkParam = !empty($conv['user_id']) ? 'user=' . $conv['user_id'] : 'guest=' . $conv['session_id']; 
                        $isSelected = !empty($selectedUserId) ? ($selectedUserId == $conv['user_id']) : (!empty($conv['session_id']) && $selectedGuestId == $conv['session_id']);
                        ?>
                        <a href="<?= url('/admin/live-chat?' . $linkParam) ?>" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-bottom: 1px solid #111; text-decoration: none; transition: background 0.15s; <?= $isSelected ? 'background: #222; border-left: 3px solid #D4A017;' : '' ?>" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background='<?= $isSelected ? '#222' : 'transparent' ?>'">
                            <!-- Avatar -->
                            <div style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid #2a2a2a; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #111; flex-shrink: 0;">
                                <?php if (!empty($conv['avatar'])): ?>
                                    <img src="<?= url('/' . $conv['avatar']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="font-size: 0.75rem; font-weight: 800; color: #D4A017;"><?= strtoupper(substr($conv['name'] ?? 'U', 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1; overflow: hidden;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($conv['name']) ?></span>
                                    <?php if ($conv['unread_count'] > 0): ?>
                                        <span style="min-width: 18px; height: 18px; border-radius: 50%; background: #D4A017; color: #000; font-size: 0.6rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><?= $conv['unread_count'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.15rem;">
                                    <span style="font-size: 0.65rem; color: #555; font-family: monospace;"><?= e($conv['email']) ?></span>
                                    <span style="font-size: 0.6rem; color: #444;"><?= timeAgo($conv['last_message_at']) ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Message Thread -->
        <div style="background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden;">
            <?php if ($selectedUser): ?>
                <!-- Thread Header -->
                <div style="padding: 0.85rem 1.25rem; border-bottom: 1px solid #2a2a2a; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #2a2a2a; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #111;">
                            <?php if (!empty($selectedUser['avatar'])): ?>
                                <img src="<?= url('/' . $selectedUser['avatar']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span style="font-size: 0.7rem; font-weight: 800; color: #D4A017;"><?= strtoupper(substr($selectedUser['name'] ?? 'U', 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #fff;"><?= e($selectedUser['name']) ?></div>
                            <div style="font-size: 0.65rem; color: #555; font-family: monospace;"><?= e($selectedUser['email']) ?></div>
                        </div>
                    </div>
                    <span style="font-size: 0.65rem; color: #555;"><?= count($selectedMessages) ?> messages</span>
                </div>

                <!-- Messages -->
                <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <?php foreach ($selectedMessages as $msg): ?>
                        <?php $isAdmin = ($msg['sender'] === 'admin'); ?>
                        <div style="display: flex; <?= $isAdmin ? 'justify-content: flex-end;' : 'justify-content: flex-start;' ?>">
                            <div style="max-width: 70%; padding: 0.65rem 0.85rem; border-radius: <?= $isAdmin ? '12px 12px 4px 12px' : '12px 12px 12px 4px' ?>; background: <?= $isAdmin ? 'rgba(212,160,23,0.12)' : '#222' ?>; border: 1px solid <?= $isAdmin ? 'rgba(212,160,23,0.2)' : '#2a2a2a' ?>;">
                                <p style="font-size: 0.8rem; color: #ddd; line-height: 1.5; word-wrap: break-word;"><?= e($msg['message']) ?></p>
                                <div style="font-size: 0.6rem; color: #555; margin-top: 0.3rem; text-align: <?= $isAdmin ? 'right' : 'left' ?>;">
                                    <?php
                                    if ($msg['sender'] === 'admin') {
                                        echo '🛡️ Admin';
                                    } elseif ($msg['sender'] === 'bot') {
                                        echo '🤖 Bot';
                                    } else {
                                        echo '👤 Customer';
                                    }
                                    ?> · <?= timeAgo($msg['created_at']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Reply Form -->
                <div style="border-top: 1px solid #2a2a2a; padding: 0.85rem 1.25rem;">
                    <?php $replyIdentifier = !empty($selectedUserId) ? $selectedUserId : $selectedGuestId; ?>
                    <form id="adminReplyForm" method="POST" action="<?= url('/admin/live-chat/reply/' . $replyIdentifier) ?>" style="display: flex; gap: 0.5rem;">
                        <?= csrf_field() ?>
                        <input type="text" id="adminReplyInput" name="message" required placeholder="Type a reply to <?= e($selectedUser['name']) ?>..." class="input-dark" style="flex: 1; font-size: 0.85rem; padding: 0.6rem 0.85rem;" autocomplete="off">
                        <button type="submit" class="btn-gold" style="padding: 0.6rem 1.25rem; font-size: 0.8rem; border-radius: 8px; white-space: nowrap;">
                            Send →
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Empty state -->
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.75rem;">
                    <span style="font-size: 2.5rem;">💬</span>
                    <p style="font-size: 0.9rem; color: #555; font-weight: 600;">Select a conversation to view messages</p>
                    <p style="font-size: 0.75rem; color: #444;">Click on a customer from the left panel to read and reply.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Chat page polling and AJAX functionality
(function() {
    const activeUserId = <?= json_encode($selectedUserId) ?>;
    const activeGuestId = <?= json_encode($selectedGuestId) ?>;
    let lastMessageCount = <?= $selectedUser ? count($selectedMessages) : 0 ?>;
    
    // Auto-scroll to bottom initially
    const chatBox = document.getElementById('chatMessages');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    // Helper functions
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#039;');
    }

    function formatTimeAgo(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split(/[- :]/);
        if (parts.length < 5) return dateStr;
        const date = new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5] || 0);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if (seconds < 60) return 'just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        return `${days}d ago`;
    }

    // Ajax polling
    function pollChatData() {
        let url = '/admin/live-chat?ajax=1';
        if (activeUserId) {
            url = `/admin/live-chat?user=${activeUserId}&ajax=1`;
        } else if (activeGuestId) {
            url = `/admin/live-chat?guest=${activeGuestId}&ajax=1`;
        }
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            updateConversationsSidebar(data.conversations, data.selectedUserId, data.selectedGuestId);
            if ((activeUserId || activeGuestId) && data.selectedMessages) {
                updateMessages(data.selectedMessages);
            }
        })
        .catch(err => console.error('Error polling chat:', err));
    }

    function updateConversationsSidebar(conversations, selectedUserId, selectedGuestId) {
        const sidebar = document.getElementById('conversationList');
        if (!sidebar) return;
        
        if (!conversations || conversations.length === 0) {
            sidebar.innerHTML = '<div style="padding: 2rem; text-align: center; color: #555; font-size: 0.8rem;">No chat conversations found.</div>';
            return;
        }
        
        let html = '';
        conversations.forEach(conv => {
            const isSelected = selectedUserId ? (selectedUserId == conv.user_id) : (selectedGuestId == conv.session_id);
            const bgStyle = isSelected ? 'background: #222; border-left: 3px solid #D4A017;' : '';
            const hoverBg = isSelected ? '#222' : 'transparent';
            
            const linkParam = conv.user_id ? `user=${conv.user_id}` : `guest=${conv.session_id}`;
            const urlPath = `/admin/live-chat?${linkParam}`;
            
            let avatarHtml = '';
            if (conv.avatar) {
                avatarHtml = `<img src="/${conv.avatar}" alt="" style="width: 100%; height: 100%; object-fit: cover;">`;
            } else {
                const firstLetter = (conv.name || 'U').charAt(0).toUpperCase();
                avatarHtml = `<span style="font-size: 0.75rem; font-weight: 800; color: #D4A017;">${firstLetter}</span>`;
            }
            
            let unreadBadge = '';
            if (conv.unread_count > 0) {
                unreadBadge = `<span style="min-width: 18px; height: 18px; border-radius: 50%; background: #D4A017; color: #000; font-size: 0.6rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">${conv.unread_count}</span>`;
            }
            
            const timeStr = formatTimeAgo(conv.last_message_at);
            
            html += `
                <a href="${urlPath}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-bottom: 1px solid #111; text-decoration: none; transition: background 0.15s; ${bgStyle}" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background='${hoverBg}'">
                    <div style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid #2a2a2a; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #111; flex-shrink: 0;">
                        ${avatarHtml}
                    </div>
                    <div style="flex: 1; overflow: hidden;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(conv.name)}</span>
                            ${unreadBadge}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.15rem;">
                            <span style="font-size: 0.65rem; color: #555; font-family: monospace;">${escapeHtml(conv.email)}</span>
                            <span style="font-size: 0.6rem; color: #444;">${timeStr}</span>
                        </div>
                    </div>
                </a>
            `;
        });
        
        sidebar.innerHTML = html;
    }

    function updateMessages(messages) {
        const chatBox = document.getElementById('chatMessages');
        if (!chatBox) return;
        
        if (messages.length === lastMessageCount) {
            return;
        }
        
        let html = '';
        messages.forEach(msg => {
            const isAdmin = msg.sender === 'admin';
            const bubbleBg = isAdmin ? 'rgba(212,160,23,0.12)' : '#222';
            const bubbleBorder = isAdmin ? 'rgba(212,160,23,0.2)' : '#2a2a2a';
            const bubbleRadius = isAdmin ? '12px 12px 4px 12px' : '12px 12px 12px 4px';
            const alignment = isAdmin ? 'justify-content: flex-end;' : 'justify-content: flex-start;';
            const textAlign = isAdmin ? 'right' : 'left';
            
            let roleName = '👤 Customer';
            if (msg.sender === 'admin') {
                roleName = '🛡️ Admin';
            } else if (msg.sender === 'bot') {
                roleName = '🤖 Bot';
            }
            
            const timeStr = formatTimeAgo(msg.created_at);
            
            html += `
                <div style="display: flex; ${alignment}">
                    <div style="max-width: 70%; padding: 0.65rem 0.85rem; border-radius: ${bubbleRadius}; background: ${bubbleBg}; border: 1px solid ${bubbleBorder};">
                        <p style="font-size: 0.8rem; color: #ddd; line-height: 1.5; word-wrap: break-word;">${escapeHtml(msg.message)}</p>
                        <div style="font-size: 0.6rem; color: #555; margin-top: 0.3rem; text-align: ${textAlign};">
                            ${roleName} · ${timeStr}
                        </div>
                    </div>
                </div>
            `;
        });
        
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
        lastMessageCount = messages.length;
    }

    // Intercept reply form submission
    const replyForm = document.getElementById('adminReplyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const input = document.getElementById('adminReplyInput');
            if (!input) return;
            
            const message = input.value.trim();
            if (!message) return;
            
            const formData = new FormData(replyForm);
            input.value = '';
            
            fetch(replyForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    pollChatData();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(err => {
                console.error('Error sending reply:', err);
            });
        });
    }

    // Start polling every 4 seconds
    const pollInterval = setInterval(pollChatData, 4000);
    window.addEventListener('beforeunload', () => {
        clearInterval(pollInterval);
    });
})();
</script>
