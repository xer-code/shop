/**
 * ShopX Global — Main JS
 * Handles mobile menu, theme toggle, AJAX cart, PWA installation, and Live Chat
 */

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initTheme();
    initCartAJAX();
    initPWAInstall();
    initGlobeWidget();
    // Chat messages loading if panel is open
    if (document.getElementById('chatPanel')) {
        loadChatMessages();
    }
});

/* =============================================
   1. Navigation & Mobile Menu
   ============================================= */
function initNavigation() {
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (mobileMenu.classList.contains('active') && !mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileMenu.classList.remove('active');
            }
        });
    }
}

/* =============================================
   2. Theme Toggle (Dark / Light)
   ============================================= */
function initTheme() {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    // Default to dark, check localStorage
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
}

function updateThemeIcon(theme) {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;
    
    if (theme === 'light') {
        themeToggle.innerHTML = `
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
            </svg>
        `;
    } else {
        themeToggle.innerHTML = `
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        `;
    }
}

/* =============================================
   3. AJAX Cart Operations
   ============================================= */
function initCartAJAX() {
    // Intercept Add to Cart forms
    const addCartForms = document.querySelectorAll('form[action$="/cart/add"]');
    addCartForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartBadge(data.cart_count);
                    showToast('success', '🛒 Item added to cart!');
                } else if (data.error) {
                    showToast('error', data.error);
                }
            })
            .catch(err => {
                console.error('Error adding to cart:', err);
                showToast('error', 'Failed to add item to cart.');
            });
        });
    });
}

function updateCartBadge(count) {
    const badge = document.getElementById('cartBadge');
    const cartIcon = document.getElementById('cartIcon');
    
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else if (cartIcon) {
            const newBadge = document.createElement('span');
            newBadge.id = 'cartBadge';
            newBadge.className = 'cart-badge';
            newBadge.textContent = count;
            cartIcon.appendChild(newBadge);
        }
    } else {
        if (badge) badge.remove();
    }
}

/* =============================================
   4. Live Chat Functionality (Pusher)
   ============================================= */
let chatPollInterval = null;
let loadedMessagesCount = -1; // -1 to force initial render
let pusherClient = null;
let pusherChannel = null;
let adminPresenceChannel = null;

function initPusherChat() {
    if (!window.SHOPX_CHAT_CONFIG || !window.SHOPX_CHAT_CONFIG.pusherKey) {
        console.warn('Pusher not configured. Falling back to HTTP polling.');
        return false;
    }
    
    if (pusherClient) return true; // already initialized

    pusherClient = new Pusher(window.SHOPX_CHAT_CONFIG.pusherKey, {
        cluster: window.SHOPX_CHAT_CONFIG.pusherCluster,
        channelAuthorization: {
            endpoint: window.SHOPX_CHAT_CONFIG.authEndpoint,
            transport: 'ajax',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    });

    // Subscribe to messages
    const channelName = window.SHOPX_CHAT_CONFIG.userId 
        ? 'private-chat-user-' + window.SHOPX_CHAT_CONFIG.userId
        : 'private-chat-guest-' + window.SHOPX_CHAT_CONFIG.sessionId;
        
    pusherChannel = pusherClient.subscribe(channelName);
    
    pusherChannel.bind('new-message', function(data) {
        appendChatBubble(data.message, data.sender);
        
        // Update badge if chat panel is closed
        const chatPanel = document.getElementById('chatPanel');
        const badge = document.getElementById('chatWidgetBadge');
        if (badge && chatPanel && !chatPanel.classList.contains('active')) {
            let current = parseInt(badge.textContent) || 0;
            badge.textContent = current + 1;
            badge.style.display = 'flex';
        }
    });

    // Subscribe to admin status
    adminPresenceChannel = pusherClient.subscribe('presence-chat-admins');
    adminPresenceChannel.bind('status-change', function(data) {
        updateAdminStatusUI(data.is_online);
    });
    
    return true;
}

function escapeChatHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
}

function updateAdminStatusUI(isOnline) {
    const badge = document.getElementById('chatStatusBadge');
    const subtitle = document.getElementById('chatStatusSubtitle');
    if (!badge) return;

    if (isOnline) {
        badge.className = 'chat-status-badge online';
        badge.innerHTML = '<span class="status-dot"></span><span class="status-text">Online</span>';
        if (subtitle) subtitle.textContent = '🟢 Support is online — fast replies';
    } else {
        badge.className = 'chat-status-badge offline';
        badge.innerHTML = '<span class="status-dot"></span><span class="status-text">Offline</span>';
        if (subtitle) subtitle.textContent = '🔴 Support is offline — leave a message';
    }
}

window.toggleChat = function() {
    const chatPanel = document.getElementById('chatPanel');
    const badge = document.getElementById('chatWidgetBadge');
    if (chatPanel) {
        chatPanel.classList.toggle('active');
        if (chatPanel.classList.contains('active')) {
            if (badge) badge.style.display = 'none';
            
            // Try initializing Pusher
            const isPusherActive = initPusherChat();
            
            // Initial load of past messages
            if (loadedMessagesCount === -1) {
                loadChatMessages(true);
            }
            
            const input = document.getElementById('chatInput');
            if (input) input.focus();
            
            // If Pusher isn't configured, fall back to polling
            if (!isPusherActive && !chatPollInterval) {
                chatPollInterval = setInterval(() => loadChatMessages(false), 3000);
            }
        } else {
            // Stop active panel polling if using fallback
            if (chatPollInterval) {
                clearInterval(chatPollInterval);
                chatPollInterval = null;
            }
        }
    }
};

window.sendChatMessage = function() {
    const input = document.getElementById('chatInput');
    if (!input) return;
    const message = input.value.trim();
    if (!message) return;

    input.value = '';

    // Optimistic UI update: display user's message immediately
    appendChatBubble(message, 'user');

    const formData = new FormData();
    formData.append('message', message);
    
    // Add CSRF if available
    const csrfToken = document.querySelector('input[name="_csrf_token"]');
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken.value);
    }

    const sendUrl = (window.SHOPX_CHAT_CONFIG && window.SHOPX_CHAT_CONFIG.sendEndpoint) ? window.SHOPX_CHAT_CONFIG.sendEndpoint : '/chat/send';

    fetch(sendUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.admin_online !== undefined) {
            updateAdminStatusUI(data.admin_online);
        }
        
        if (!pusherClient) {
            // Force refresh chat messages if using polling fallback
            loadedMessagesCount = -1;
            loadChatMessages(true);
        }
    })
    .catch(err => console.error('Error sending message:', err));
};


function appendChatBubble(text, sender) {
    const container = document.getElementById('chatMessages');
    if (!container) return;

    const bubble = document.createElement('div');
    const bubbleClass = (sender === 'user') ? 'user' : 'bot';
    bubble.className = `chat-bubble ${bubbleClass}`;
    
    if (sender === 'admin') {
        bubble.innerHTML = `<span style="font-size: 0.7rem; display: block; color: var(--gold-primary); font-weight: 700; margin-bottom: 2px;">🛡️ Support Representative</span>` + escapeChatHtml(text);
    } else {
        bubble.textContent = text;
    }
    
    container.appendChild(bubble);
    container.scrollTop = container.scrollHeight;
}

function loadChatMessages(forceScroll = false) {
    const cacheBuster = new Date().getTime();
    const messagesUrl = (window.SHOPX_CHAT_CONFIG && window.SHOPX_CHAT_CONFIG.messagesEndpoint) ? window.SHOPX_CHAT_CONFIG.messagesEndpoint : '/chat/messages';
    
    fetch(messagesUrl + '?_t=' + cacheBuster, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })

    .then(res => res.json())
    .then(data => {
        if (data.admin_online !== undefined) {
            updateAdminStatusUI(data.admin_online);
        }

        const container = document.getElementById('chatMessages');
        if (!container || !data.messages) return;

        // Skip rebuilding if message count has not changed unless forced
        if (!forceScroll && data.messages.length === loadedMessagesCount) {
            return;
        }

        // Rebuild messages
        container.innerHTML = `
            <div class="chat-bubble bot">
                👋 Welcome to ShopX Global! How can we help you today?
            </div>
        `;

        let unreadFromAdmin = 0;
        data.messages.forEach(msg => {
            appendChatBubble(msg.message, msg.sender);
            if (msg.sender === 'admin' && !msg.is_read) {
                unreadFromAdmin++;
            }
        });

        loadedMessagesCount = data.messages.length;

        // Update badge if chat panel is closed
        const chatPanel = document.getElementById('chatPanel');
        const badge = document.getElementById('chatWidgetBadge');
        if (badge && chatPanel && !chatPanel.classList.contains('active')) {
            if (unreadFromAdmin > 0) {
                badge.textContent = unreadFromAdmin;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    })
    .catch(err => console.log('Chat initialization check.'));
}

function checkAdminStatusOnly() {
    const cacheBuster = new Date().getTime();
    fetch('/chat/admin-status?_t=' + cacheBuster, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })

    .then(res => res.json())
    .then(data => {
        if (data.online !== undefined) {
            updateAdminStatusUI(data.online);
        }
    })
    .catch(() => {});
}

// Initial status check
checkAdminStatusOnly();
// Fallback periodic check only if Pusher isn't active
setInterval(() => {
    if (!pusherClient) checkAdminStatusOnly();
}, 10000);


/* =============================================
   5. PWA & Mobile App Install Logic
   ============================================= */
let deferredPrompt = null;

function isMobileView() {
    return window.innerWidth <= 768 || /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
}

function showPWABanner() {
    if (!isMobileView()) {
        hidePWABanner();
        return;
    }
    const card = document.getElementById('pwaFloatingCard');
    const sec = document.getElementById('pwaSection');
    if (card) {
        card.style.display = 'block';
        // Force reflow for CSS opacity/transform transition
        void card.offsetWidth;
        card.classList.add('active');
    }
    if (sec) {
        sec.style.display = 'block';
    }
}

function hidePWABanner() {
    const card = document.getElementById('pwaFloatingCard');
    const sec = document.getElementById('pwaSection');
    if (card) {
        card.classList.remove('active');
        setTimeout(() => {
            if (!card.classList.contains('active')) {
                card.style.display = 'none';
            }
        }, 400);
    }
    if (sec) {
        sec.style.display = 'none';
    }
}

function initPWAInstall() {
    // 0. Only run install card logic on mobile view
    if (!isMobileView()) {
        hidePWABanner();
    }

    window.addEventListener('resize', () => {
        if (!isMobileView()) {
            hidePWABanner();
        }
    });

    // 1. Check if already running in standalone mode (installed PWA)
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                         window.navigator.standalone === true || 
                         (document.referrer && document.referrer.startsWith('android-app://'));

    if (isStandalone || localStorage.getItem('pwaInstalled') === 'true') {
        hidePWABanner();
        return;
    }

    // 2. Check if user dismissed prompt recently (within last 7 days)
    const lastDismissed = localStorage.getItem('pwaDismissed');
    if (lastDismissed) {
        const daysPassed = (Date.now() - parseInt(lastDismissed, 10)) / (1000 * 60 * 60 * 24);
        if (daysPassed < 7) {
            hidePWABanner();
            return;
        }
    }

    // 3. Setup platform specific UI texts
    const ua = window.navigator.userAgent.toLowerCase();
    const isIos = /iphone|ipad|ipod/.test(ua);

    const cardTitle = document.querySelector('.pwa-card-title');
    const cardSubtitle = document.getElementById('pwaCardSubtitle');
    const cardBtn = document.getElementById('pwaCardInstallBtn');

    const bannerTitle = document.querySelector('.pwa-banner h3');
    const bannerSubtitle = document.querySelector('.pwa-banner p');
    const bannerBtn = document.getElementById('pwaInstallBtn');

    if (isIos) {
        if (cardTitle) cardTitle.textContent = 'Add to Home Screen';
        if (cardSubtitle) cardSubtitle.innerHTML = 'Tap <span style="font-weight:700; text-decoration:underline;">Share 📤</span> and select <span style="font-weight:700; text-decoration:underline;">Add to Home Screen</span>';
        if (cardBtn) {
            cardBtn.innerHTML = `
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Share to Home Screen</span>
            `;
        }

        if (bannerTitle) bannerTitle.textContent = 'Add ShopX to Home Screen';
        if (bannerSubtitle) bannerSubtitle.textContent = 'Tap Share 📤 and select Add to Home Screen in Safari';
        if (bannerBtn) {
            bannerBtn.innerHTML = `
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Share to Home Screen</span>
            `;
        }
    } else {
        if (cardTitle) cardTitle.textContent = 'Install ShopX App';
        if (cardSubtitle) cardSubtitle.textContent = 'Download official Android App (APK)';
        if (cardBtn) {
            cardBtn.innerHTML = `
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Download Android App</span>
            `;
        }

        if (bannerTitle) bannerTitle.textContent = 'Download ShopX Android App';
        if (bannerSubtitle) bannerSubtitle.textContent = 'Download the official app APK directly to your device';
        if (bannerBtn) {
            bannerBtn.innerHTML = `
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Download Android App</span>
            `;
        }
    }

    // 4. Listen for native browser beforeinstallprompt event (Android/Chrome/Edge)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (isMobileView()) {
            showPWABanner();
        }
    });

    // 5. Hide banner permanently once installed
    window.addEventListener('appinstalled', () => {
        localStorage.setItem('pwaInstalled', 'true');
        hidePWABanner();
        deferredPrompt = null;
    });

    // 6. Show the widget automatically when visiting on mobile!
    if (isMobileView()) {
        setTimeout(showPWABanner, 600);
    }
}

window.dismissPWABanner = function() {
    localStorage.setItem('pwaDismissed', Date.now().toString());
    hidePWABanner();
};

window.showIosInstallModal = function() {
    const modal = document.getElementById('iosInstallModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('active');
    } else {
        alert('To install this app on your iPhone/iPad:\n\n1. Tap the Share icon (box with arrow up 📤) at the bottom of Safari.\n2. Scroll down and tap "Add to Home Screen".\n3. Tap "Add" at top right.');
    }
};

window.closeIosModal = function() {
    const modal = document.getElementById('iosInstallModal');
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
    }
};

window.installPWA = function() {
    const ua = window.navigator.userAgent.toLowerCase();
    const isIos = /iphone|ipad|ipod/.test(ua);

    if (isIos) {
        // iOS: Share to Home Screen
        if (navigator.share) {
            navigator.share({
                title: document.title || 'ShopX Global',
                text: 'Add ShopX Global to your Home Screen',
                url: window.location.href
            }).then(() => {
                showToast('info', 'Tap "Add to Home Screen" in the share menu.');
            }).catch(() => {
                // If user cancels or navigator.share aborts, show modal guide
                showIosInstallModal();
            });
        } else {
            showIosInstallModal();
        }
    } else {
        // Android / Mobile: Download android app APK (app-release.apk)
        // 1. Try to find an existing download link on the current page
        const existingBtn = document.querySelector('a[download="app-release.apk"]');
        let targetHref;
        if (existingBtn) {
            targetHref = existingBtn.href;
        } else {
            // 2. Compute base URL from current page location
            //    Remove trailing segments like /download, /shop, etc.
            const base = window.location.origin +
                window.location.pathname.replace(/\/(?:download|shop|public|cart|checkout|login|register|profile|dashboard)?\/?$/i, '');
            targetHref = base + '/app-release.apk';
        }
        const apkLink = document.createElement('a');
        apkLink.href = targetHref;
        apkLink.download = 'app-release.apk';
        document.body.appendChild(apkLink);
        apkLink.click();
        apkLink.remove();

        showToast('success', '📥 Downloading Android App (app-release.apk)...');

        if (deferredPrompt) {
            setTimeout(() => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(() => {
                    deferredPrompt = null;
                });
            }, 1200);
        }
    }
};

/* =============================================
   6. Globe Widget Custom interactions
   ============================================= */
function initGlobeWidget() {
    const globeBtn = document.getElementById('globeBtn');
    if (!globeBtn) return;
    
    globeBtn.addEventListener('click', () => {
        showToast('info', '🌎 Multi-currency and translation options are configured automatically based on your location.');
    });
}

/* =============================================
   7. Toast Notification Utility
   ============================================= */
function showToast(type, message) {
    const container = document.body;
    const toast = document.createElement('div');
    toast.className = `flash-message flash-${type} fade-in`;
    toast.style.position = 'fixed';
    toast.style.top = '1rem';
    toast.style.right = '1rem';
    toast.style.zIndex = '999';
    toast.style.margin = '0';
    toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.5)';
    
    let icon = 'ℹ️';
    if (type === 'success') icon = '✓';
    if (type === 'error') icon = '⚠️';
    
    toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

/* =============================================
   8. Wishlist AJAX Toggle
   ============================================= */
window.toggleWishlist = function(productId, btn) {
    const formData = new FormData();
    formData.append('product_id', productId);
    
    // Find CSRF token if present
    const csrfToken = document.querySelector('input[name="_csrf_token"]');
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken.value);
    }
    
    fetch('/wishlist/toggle', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (res.status === 401) {
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }
        return res.json();
    })
    .then(data => {
        if (data.wishlisted) {
            btn.classList.add('active');
            btn.innerHTML = `<svg width="18" height="18" fill="red" stroke="red" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
            showToast('success', 'Added to wishlist!');
        } else {
            btn.classList.remove('active');
            btn.innerHTML = `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
            showToast('info', 'Removed from wishlist.');
        }
    })
    .catch(err => console.log('Wishlist toggle error:', err));
};
