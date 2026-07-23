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
        <button class="chat-btn" aria-label="Open live chat" onclick="toggleChat()" id="chatToggleBtn">
            💬
        </button>
    </div>
</div>

<!-- Chat Panel -->
<div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
        <div>
            <h3 style="font-size: 1rem; font-weight: 700;">💬 Live Chat</h3>
            <p style="font-size: 0.75rem; color: var(--text-muted);">We typically reply within minutes</p>
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