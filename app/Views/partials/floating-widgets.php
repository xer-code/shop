<?php
$currentLang = 'en';
if (isset($_COOKIE['googtrans'])) {
    $parts = explode('/', $_COOKIE['googtrans']);
    if (count($parts) >= 3) {
        $currentLang = $parts[2];
    }
}
$flags = [
    'en' => '🇺🇸',
    'es' => '🇪🇸',
    'fr' => '🇫🇷',
    'de' => '🇩🇪',
    'pt' => '🇧🇷',
    'it' => '🇮🇹',
    'nl' => '🇳🇱',
    'pl' => '🇵🇱',
    'sv' => '🇸🇪'
];
$currentFlag = $flags[$currentLang] ?? '🇺🇸';

$sysSettings = \App\Core\Session::get('ent_settings', []);
$appName = $sysSettings['app_name'] ?? 'ShopX-Global';
?>
<!-- Floating PWA Install Card -->
<div id="pwaFloatingCard" class="pwa-floating-card" style="display: none;">
    <button type="button" class="pwa-card-close" onclick="dismissPWABanner()" aria-label="Close install prompt">&times;</button>
    <div class="pwa-card-body">
        <div class="pwa-card-icon">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="6" y="2" width="12" height="20" rx="3" ry="3"></rect>
                <line x1="12" y1="18" x2="12.01" y2="18" stroke-width="3" stroke-linecap="round"></line>
            </svg>
        </div>
        <div class="pwa-card-text">
            <h3 class="pwa-card-title">Install <?= e($appName) ?></h3>
            <p class="pwa-card-subtitle" id="pwaCardSubtitle">Add to your home screen for quick access</p>
        </div>
    </div>
    <button type="button" class="pwa-card-btn" id="pwaCardInstallBtn" onclick="installPWA()">
        <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span>Install Now</span>
    </button>
</div>

<!-- iOS Share to Home Screen Guidance Modal -->
<div id="iosInstallModal" class="modal-overlay" style="display: none; z-index: 100000;" onclick="closeIosModal()">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 360px; text-align: center; border-radius: 24px; padding: 2rem; background: #121214; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 25px 50px rgba(0,0,0,0.8);">
        <div style="font-size: 3rem; margin-bottom: 0.75rem;">📱</div>
        <h3 style="color: white; font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">Add to Home Screen</h3>
        <p style="color: var(--text-muted); font-size: 0.88rem; line-height: 1.5; margin-bottom: 1.25rem;">
            To install <strong><?= e($appName) ?></strong> on your iPhone or iPad:
        </p>
        <div style="text-align: left; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: #ddd; display: flex; flex-direction: column; gap: 0.85rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="background: rgba(124,58,237,0.4); color: #c4b5fd; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">1</span>
                <span>Tap the <strong>Share</strong> icon <span style="font-size: 1.1rem;">📤</span> at bottom of Safari.</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="background: rgba(124,58,237,0.4); color: #c4b5fd; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">2</span>
                <span>Scroll down & tap <strong>"Add to Home Screen"</strong>.</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="background: rgba(124,58,237,0.4); color: #c4b5fd; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">3</span>
                <span>Tap <strong>"Add"</strong> in top right corner.</span>
            </div>
        </div>
        <button type="button" onclick="closeIosModal()" style="width: 100%; padding: 0.8rem; background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark)); color: #000; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(212,160,23,0.3);">
            Got it!
        </button>
    </div>
</div>


<!-- Floating Widgets -->
<div class="floating-widgets">
    <!-- Globe / Language -->
    <div class="globe-widget">
        <button class="globe-btn" aria-label="Language selector" id="globeBtn" onclick="openLanguageModal()" style="border-radius: 14px;">
            🌐
            <span class="flag-overlay"><?= $currentFlag ?></span>
        </button>
    </div>

    <!-- Chat -->
    <div class="chat-widget">
        <button class="chat-btn" aria-label="Open live chat" onclick="toggleChat()" id="chatToggleBtn" style="position: relative;">
            💬
            <span id="chatWidgetBadge" class="chat-unread-badge" style="display: none;">0</span>
        </button>
    </div>
</div>

<!-- Chat Panel -->
<div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <h3 style="font-size: 1rem; font-weight: 700; margin: 0;">💬 Live Chat</h3>
                <span id="chatStatusBadge" class="chat-status-badge offline">
                    <span class="status-dot"></span>
                    <span class="status-text">Offline</span>
                </span>
            </div>
            <p id="chatStatusSubtitle" style="font-size: 0.75rem; color: var(--text-muted); margin: 2px 0 0 0;">Checking support availability...</p>
        </div>
        <button onclick="toggleChat()" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>

    <div class="chat-messages" id="chatMessages">
        <div class="chat-bubble bot">
            👋 Welcome to ShopX Global! How can we help you today?
        </div>
    </div>

    <div class="chat-input-area">
        <input type="text" id="chatInput" placeholder="Type a message..." onkeypress="if(event.key==='Enter')sendChatMessage()">
        <button onclick="sendChatMessage()" aria-label="Send message">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
        </button>
    </div>
</div>


<!-- Pusher Chat Config & Script -->
<?php
$settings = \App\Core\Session::get('ent_settings', []);
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$langPrefix = 'en';
if (preg_match('#^/([a-z]{2})/#', $currentUrl, $matches)) {
    $langPrefix = $matches[1];
}
?>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
window.SHOPX_CHAT_CONFIG = {
    pusherKey: <?= json_encode($settings['pusher_key'] ?? '') ?>,
    pusherCluster: <?= json_encode($settings['pusher_cluster'] ?? 'mt1') ?>,
    userId: <?= json_encode(\App\Core\Auth::id()) ?>,
    sessionId: <?= json_encode(session_id()) ?>,
    authEndpoint: '<?= url("/{$langPrefix}/pusher/auth") ?>',
    sendEndpoint: '<?= url("/{$langPrefix}/chat/send") ?>',
    messagesEndpoint: '<?= url("/{$langPrefix}/chat/messages") ?>',
    csrfToken: '<?= \App\Core\Session::generateCsrf() ?>'
};
</script>

<!-- Language Modal Backdrop & Box -->
<div class="language-modal-backdrop" id="langModalBackdrop" onclick="closeLanguageModal()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div class="language-modal" onclick="event.stopPropagation()" style="background: #0e0e0e; border: 1px solid rgba(212,160,23,0.3); border-radius: 16px; width: 90%; max-width: 380px; padding: 1.5rem; box-shadow: 0 20px 45px rgba(0,0,0,0.6); display: flex; flex-direction: column; gap: 1.25rem;">

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1a1a1a; padding-bottom: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.65rem; color: #fff; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.02em;">
                <span style="color: var(--gold-primary); font-size: 1.25rem;">🌐</span> Language
            </div>
            <button onclick="closeLanguageModal()" style="background: none; border: none; color: #666; font-size: 1.5rem; line-height: 1; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#666'">&times;</button>
        </div>

        <!-- Search Input -->
        <div style="position: relative;">
            <input type="text" id="langSearchInput" placeholder="Search language..." oninput="filterLanguages()" style="width: 100%; padding: 0.75rem 1rem; background: #161616; border: 1px solid #222; border-radius: 8px; color: white; font-size: 0.9rem; outline: none; transition: border-color 0.2s, box-shadow 0.2s;" onfocus="this.style.borderColor='var(--gold-primary)'; this.style.boxShadow='0 0 10px rgba(212,160,23,0.1)';" onblur="this.style.borderColor='#222'; this.style.boxShadow='none';">
        </div>

        <!-- Language List -->
        <div id="langList" style="max-height: 280px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.35rem; padding-right: 0.25rem;">
            <?php
            $languages = [
                ['code' => 'en', 'name' => 'English', 'flag' => '🇺🇸'],
                ['code' => 'es', 'name' => 'Español', 'flag' => '🇪🇸'],
                ['code' => 'fr', 'name' => 'Français', 'flag' => '🇫🇷'],
                ['code' => 'de', 'name' => 'Deutsch', 'flag' => '🇩🇪'],
                ['code' => 'pt', 'name' => 'Português', 'flag' => '🇧🇷'],
                ['code' => 'it', 'name' => 'Italiano', 'flag' => '🇮🇹'],
                ['code' => 'nl', 'name' => 'Nederlands', 'flag' => '🇳🇱'],
                ['code' => 'pl', 'name' => 'Polski', 'flag' => '🇵🇱'],
                ['code' => 'sv', 'name' => 'Svenska', 'flag' => '🇸🇪']
            ];
            foreach ($languages as $lang):
                $isCurrent = ($currentLang === $lang['code']);
            ?>
                <button class="lang-item" data-name="<?= strtolower($lang['name']) ?>" onclick="selectLanguage('<?= $lang['code'] ?>')" style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 0.8rem 1rem; border-radius: 8px; border: none; background: <?= $isCurrent ? 'rgba(212,160,23,0.1)' : 'transparent' ?>; text-align: left; cursor: pointer; transition: background 0.15s, transform 0.1s; outline: none; border-left: 3px solid <?= $isCurrent ? 'var(--gold-primary)' : 'transparent' ?>;" onmouseover="this.style.background='rgba(212,160,23,0.05)';" onmouseout="this.style.background='<?= $isCurrent ? 'rgba(212,160,23,0.1)' : 'transparent' ?>'">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 1.3rem;"><?= $lang['flag'] ?></span>
                        <span style="font-size: 0.95rem; font-weight: 600; color: <?= $isCurrent ? 'var(--gold-primary)' : '#ddd' ?>;"><?= $lang['name'] ?></span>
                    </div>
                    <?php if ($isCurrent): ?>
                        <span style="color: var(--gold-primary); font-weight: 700; font-size: 0.95rem;">✓</span>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<!-- Hidden Google Translate Element -->
<div id="google_translate_element" style="display:none;"></div>

<style>
    /* Custom Style adjustments for Google Translate */
    iframe.skiptranslate,
    .goog-te-banner-frame,
    #goog-gt-tt {
        display: none !important;
        visibility: hidden !important;
    }

    body {
        top: 0px !important;
    }

    .goog-tooltip {
        display: none !important;
    }

    .goog-tooltip:hover {
        display: none !important;
    }

    .goog-text-highlight {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    #langList::-webkit-scrollbar {
        width: 6px;
    }

    #langList::-webkit-scrollbar-track {
        background: #0e0e0e;
    }

    #langList::-webkit-scrollbar-thumb {
        background: #222;
        border-radius: 4px;
    }

    #langList::-webkit-scrollbar-thumb:hover {
        background: var(--gold-primary);
    }
</style>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,es,fr,de,pt,it,nl,pl,sv',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    function openLanguageModal() {
        const backdrop = document.getElementById('langModalBackdrop');
        if (backdrop) {
            backdrop.style.display = 'flex';
            document.getElementById('langSearchInput').value = '';
            filterLanguages();
            document.getElementById('langSearchInput').focus();
        }
    }

    function closeLanguageModal() {
        const backdrop = document.getElementById('langModalBackdrop');
        if (backdrop) backdrop.style.display = 'none';
    }

    function selectLanguage(langCode) {
        // Clear existing translation cookies
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname + ";";

        if (langCode !== 'en') {
            // Set new translation cookies
            document.cookie = "googtrans=/en/" + langCode + "; path=/;";
            document.cookie = "googtrans=/en/" + langCode + "; path=/; domain=" + window.location.hostname + ";";
        }
        location.reload();
    }

    function filterLanguages() {
        const search = document.getElementById('langSearchInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.lang-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(search)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>