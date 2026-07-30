<?php
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$langPrefix = 'en';
if (preg_match('#^/([a-z]{2})/#', $currentUrl, $matches)) {
    $langPrefix = $matches[1];
}
?>
<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">💬 Live Chat Messages</h3>
            <p class="text-xs text-gray-500">View and reply to customer live chat conversations in real-time</p>
        </div>
        
        <!-- Online/Offline Toggle -->
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest" id="statusLabel">
                <?= $isOnline ? 'Online' : 'Offline' ?>
            </span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="adminOnlineToggle" class="sr-only peer" <?= $isOnline ? 'checked' : '' ?>>
                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
            </label>
        </div>
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
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-size: 0.65rem; color: #555;"><?= count($selectedMessages) ?> messages</span>
                        <form method="POST" action="<?= url('/' . $langPrefix . '/admin/live-chat/delete/' . (!empty($selectedUserId) ? $selectedUserId : $selectedGuestId)) ?>" onsubmit="return confirm('Are you sure you want to delete this conversation? This cannot be undone.');">
                            <?= csrf_field() ?>
                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; display: flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; padding: 0.3rem 0.6rem; border-radius: 4px; border: 1px solid rgba(239, 68, 68, 0.2); transition: all 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.1)'" onmouseout="this.style.background='none'">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
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
                    <?php 
                    $replyIdentifier = !empty($selectedUserId) ? $selectedUserId : $selectedGuestId; 
                    ?>
                    <form id="adminReplyForm" method="POST" action="<?= url('/' . $langPrefix . '/admin/live-chat/reply/' . $replyIdentifier) ?>" style="display: flex; gap: 0.5rem;">
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
    let pusherClient = null;
    let pusherAdminChannel = null;
    
    // Auto-scroll to bottom initially
    const chatBox = document.getElementById('chatMessages');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    function initPusherAdmin() {
        if (!window.SHOPX_ADMIN_CHAT_CONFIG || !window.SHOPX_ADMIN_CHAT_CONFIG.pusherKey) {
            console.warn('Pusher not configured for admin. Falling back to HTTP polling.');
            return false;
        }

        pusherClient = new Pusher(window.SHOPX_ADMIN_CHAT_CONFIG.pusherKey, {
            cluster: window.SHOPX_ADMIN_CHAT_CONFIG.pusherCluster,
            channelAuthorization: {
                endpoint: window.SHOPX_ADMIN_CHAT_CONFIG.authEndpoint,
                transport: 'ajax',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        });

        // Admin subscribes to all incoming messages
        pusherAdminChannel = pusherClient.subscribe('private-admin-chat');
        
        pusherAdminChannel.bind('new-message', function(data) {
            // Check if the message belongs to the currently active conversation
            const isMatchingUser = activeUserId && data.user_id == activeUserId;
            const isMatchingGuest = activeGuestId && data.session_id == activeGuestId;

            if (isMatchingUser || isMatchingGuest) {
                // Instantly append to current thread
                appendAdminChatBubble(data.message, data.sender);
                // Also trigger a background poll just to update sidebar ordering quietly
                pollChatData(true);
            } else {
                // It's for another conversation, poll to update the sidebar unread counts
                pollChatData(false);
            }
            
            // Play notification sound
            playNotificationSound();
        });

        return true;
    }

    function playNotificationSound() {
        try {
            const audio = new Audio('/assets/sounds/notification.mp3');
            audio.play().catch(e => {});
        } catch(e) {}
    }

    function appendAdminChatBubble(text, sender) {
        const chatBox = document.getElementById('chatMessages');
        if (!chatBox) return;

        const isAdmin = sender === 'admin';
        const bubbleBg = isAdmin ? 'rgba(212,160,23,0.12)' : '#222';
        const bubbleBorder = isAdmin ? 'rgba(212,160,23,0.2)' : '#2a2a2a';
        const bubbleRadius = isAdmin ? '12px 12px 4px 12px' : '12px 12px 12px 4px';
        const alignment = isAdmin ? 'justify-content: flex-end;' : 'justify-content: flex-start;';
        const textAlign = isAdmin ? 'right' : 'left';
        
        let roleName = '👤 Customer';
        if (sender === 'admin') roleName = '🛡️ Admin';
        else if (sender === 'bot') roleName = '🤖 Bot';

        const html = `
            <div style="display: flex; ${alignment}">
                <div style="max-width: 70%; padding: 0.65rem 0.85rem; border-radius: ${bubbleRadius}; background: ${bubbleBg}; border: 1px solid ${bubbleBorder};">
                    <p style="font-size: 0.8rem; color: #ddd; line-height: 1.5; word-wrap: break-word;">${escapeHtml(text)}</p>
                    <div style="font-size: 0.6rem; color: #555; margin-top: 0.3rem; text-align: ${textAlign};">
                        ${roleName} · just now
                    </div>
                </div>
            </div>
        `;
        
        chatBox.insertAdjacentHTML('beforeend', html);
        chatBox.scrollTop = chatBox.scrollHeight;
        lastMessageCount++;
    }

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

    // Ajax polling (used for initial load, sidebar updates, and fallback)
    function pollChatData(silentUpdate = false) {
        const cacheBuster = new Date().getTime();
        const basePollUrl = (window.SHOPX_ADMIN_CHAT_CONFIG && window.SHOPX_ADMIN_CHAT_CONFIG.pollEndpoint) 
            ? window.SHOPX_ADMIN_CHAT_CONFIG.pollEndpoint 
            : '/admin/live-chat';
            
        let url = basePollUrl + '?ajax=1&_t=' + cacheBuster;
        if (activeUserId) {
            url = basePollUrl + `?user=${activeUserId}&ajax=1&_t=${cacheBuster}`;
        } else if (activeGuestId) {
            url = basePollUrl + `?guest=${activeGuestId}&ajax=1&_t=${cacheBuster}`;
        }

        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            updateConversationsSidebar(data.conversations, data.selectedUserId, data.selectedGuestId);
            if (!silentUpdate && (activeUserId || activeGuestId) && data.selectedMessages) {
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
            
            const basePollUrl = (window.SHOPX_ADMIN_CHAT_CONFIG && window.SHOPX_ADMIN_CHAT_CONFIG.pollEndpoint) 
                ? window.SHOPX_ADMIN_CHAT_CONFIG.pollEndpoint 
                : '/admin/live-chat';
            const urlPath = basePollUrl + '?' + linkParam;
            
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
                    if (!pusherClient) {
                        pollChatData();
                    } else {
                        // Optimistically append the admin's own reply
                        appendAdminChatBubble(message, 'admin');
                        pollChatData(true); // silent update sidebar
                    }
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(err => {
                console.error('Error sending reply:', err);
            });
        });
    }

    // Initialize Pusher. If it fails or isn't configured, fall back to polling
    const isPusherActive = initPusherAdmin();
    let pollInterval = null;
    
    if (!isPusherActive) {
        pollInterval = setInterval(pollChatData, 4000);
    }

    window.addEventListener('beforeunload', () => {
        if (pollInterval) clearInterval(pollInterval);
    });
    // Handle Online/Offline Toggle
    const onlineToggle = document.getElementById('adminOnlineToggle');
    const statusLabel = document.getElementById('statusLabel');
    if (onlineToggle) {
        onlineToggle.addEventListener('change', function() {
            const isOnline = this.checked ? 1 : 0;
            
            // Optimistic UI update
            statusLabel.textContent = isOnline ? 'Online' : 'Offline';
            
            const formData = new FormData();
            formData.append('is_online', isOnline);
            // Append CSRF token if available on page
            const csrfInput = document.querySelector('input[name="_csrf_token"]');
            if (csrfInput) {
                formData.append('_csrf_token', csrfInput.value);
            }
            
            const toggleUrl = (window.SHOPX_ADMIN_CHAT_CONFIG && window.SHOPX_ADMIN_CHAT_CONFIG.toggleStatusEndpoint) 
                ? window.SHOPX_ADMIN_CHAT_CONFIG.toggleStatusEndpoint 
                : '/admin/live-chat/toggle-status';
            
            fetch(toggleUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    console.log('Status toggled successfully');
                }
            })
            .catch(err => {
                console.error('Error toggling status:', err);
                // Revert UI on failure
                onlineToggle.checked = !isOnline;
                statusLabel.textContent = !isOnline ? 'Online' : 'Offline';
            });
        });
    }

})();
</script>
