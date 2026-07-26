<?php $pageTitle = 'Download Mobile App — ShopX Global'; ?>

<section class="container" style="padding: 4rem 1rem 6rem;">
    <!-- Hero Banner -->
    <div style="background: linear-gradient(135deg, rgba(30, 27, 75, 0.75), rgba(88, 28, 135, 0.5), rgba(15, 23, 42, 0.9)); border: 1px solid rgba(212, 160, 23, 0.3); border-radius: 28px; padding: 3.5rem 2rem; text-align: center; position: relative; overflow: hidden; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px);">
        
        <!-- Glowing Accent Effect -->
        <div style="position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 380px; height: 380px; background: radial-gradient(circle, rgba(212,160,23,0.18) 0%, transparent 70%); filter: blur(40px); pointer-events: none;"></div>

        <!-- Category Badge -->
        <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; background: rgba(212, 160, 23, 0.1); border: 1px solid rgba(212, 160, 23, 0.35); border-radius: 30px; color: var(--gold-primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.5rem;">
            📱 Official ShopX Global Mobile App
        </span>

        <!-- Main Title & Subtitle -->
        <h1 style="font-size: clamp(2.2rem, 5vw, 3.8rem); font-weight: 950; color: white; line-height: 1.15; margin-bottom: 1rem;">
            Shop Anywhere, Anytime with <span class="text-gold">ShopX App</span>
        </h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 640px; margin: 0 auto 2.5rem; line-height: 1.6;">
            Get instant access to over 1M+ global products, real-time order tracking, exclusive mobile deals, and 1-tap wallet checkout.
        </p>

        <!-- CTA Buttons -->
        <div style="display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
            <!-- Direct Android APK Download Button -->
            <a href="<?= url('/app-release.apk') ?>" download="app-release.apk" class="btn-gold" style="padding: 1rem 2rem; font-size: 1.05rem; font-weight: 800; border-radius: 16px; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 10px 30px rgba(212, 160, 23, 0.35); text-decoration: none;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.523 15.3414C17.062 15.3414 16.6873 14.9667 16.6873 14.5057C16.6873 14.0447 17.062 13.67 17.523 13.67C17.984 13.67 18.3587 14.0447 18.3587 14.5057C18.3587 14.9667 17.984 15.3414 17.523 15.3414ZM6.47702 15.3414C6.01602 15.3414 5.64136 14.9667 5.64136 14.5057C5.64136 14.0447 6.01602 13.67 6.47702 13.67C6.93802 13.67 7.31268 14.0447 7.31268 14.5057C7.31268 14.9667 6.93802 15.3414 6.47702 15.3414ZM17.9454 10.7417L19.7154 7.67602C19.8374 7.46469 19.7647 7.19436 19.5534 7.07236C19.342 6.95036 19.0717 7.02302 18.9497 7.23436L17.1334 10.3804C15.6327 9.69469 13.882 9.30002 12 9.30002C10.118 9.30002 8.36735 9.69469 6.86668 10.3804L5.05035 7.23436C4.92835 7.02302 4.65802 6.95036 4.44668 7.07236C4.23535 7.19436 4.16268 7.46469 4.28468 7.67602L6.05468 10.7417C2.61068 12.6154 0.230017 16.0384 0 20.0817H24C23.77 16.0384 21.3894 12.6154 17.9454 10.7417Z"/>
                </svg>
                <span>Download Android APK</span>
                <span style="font-size: 0.75rem; background: rgba(0,0,0,0.25); padding: 0.25rem 0.5rem; border-radius: 8px; font-family: monospace;">8.4 MB</span>
            </a>

            <!-- iOS Share / PWA Button -->
            <button type="button" onclick="installPWA()" class="btn-outline" style="padding: 1rem 2rem; font-size: 1.05rem; font-weight: 700; border-radius: 16px; display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,0.06); color: white; border: 1.5px solid rgba(255,255,255,0.2); cursor: pointer;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>iOS & Web App Installation</span>
            </button>
        </div>

        <!-- Platform Options Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; text-align: left; margin-top: 3.5rem;">
            
            <!-- Android Option -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 2rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2.2rem;">🤖</span>
                        <span style="font-size: 0.75rem; color: #10b981; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); padding: 0.25rem 0.65rem; border-radius: 12px; font-weight: 700;">Android APK</span>
                    </div>
                    <h3 style="color: white; font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">Android Package</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.5rem;">
                        Download the official ShopX Android application directly onto your mobile phone or tablet.
                    </p>
                </div>
                <a href="<?= url('/app-release.apk') ?>" download="app-release.apk" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.85rem; background: rgba(212,160,23,0.15); border: 1px solid var(--gold-primary); color: var(--gold-primary); font-weight: 700; border-radius: 12px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--gold-primary)'; this.style.color='#000';" onmouseout="this.style.background='rgba(212,160,23,0.15)'; this.style.color='var(--gold-primary)';">
                    📥 Download app-release.apk
                </a>
            </div>

            <!-- iOS Option -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 2rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2.2rem;">🍏</span>
                        <span style="font-size: 0.75rem; color: #a78bfa; background: rgba(167,139,250,0.15); border: 1px solid rgba(167,139,250,0.3); padding: 0.25rem 0.65rem; border-radius: 12px; font-weight: 700;">iPhone & iPad</span>
                    </div>
                    <h3 style="color: white; font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">iOS Safari App</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.5rem;">
                        Add ShopX Global to your iOS Home Screen instantly using the Safari share action.
                    </p>
                </div>
                <button type="button" onclick="installPWA()" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.85rem; background: rgba(124,58,237,0.15); border: 1px solid #7c3aed; color: #c4b5fd; font-weight: 700; border-radius: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#7c3aed'; this.style.color='#fff';" onmouseout="this.style.background='rgba(124,58,237,0.15)'; this.style.color='#c4b5fd';">
                    📤 Share to Home Screen
                </button>
            </div>

            <!-- Web App Option -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 2rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 2.2rem;">💻</span>
                        <span style="font-size: 0.75rem; color: #3b82f6; background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); padding: 0.25rem 0.65rem; border-radius: 12px; font-weight: 700;">Browser App</span>
                    </div>
                    <h3 style="color: white; font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">Progressive Web App</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.5rem;">
                        Install as a standalone desktop app on Chrome, Edge or Brave with offline caching.
                    </p>
                </div>
                <button type="button" onclick="installPWA()" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.85rem; background: rgba(59,130,246,0.15); border: 1px solid #3b82f6; color: #93c5fd; font-weight: 700; border-radius: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#3b82f6'; this.style.color='#fff';" onmouseout="this.style.background='rgba(59,130,246,0.15)'; this.style.color='#93c5fd';">
                    ⚡ Install PWA
                </button>
            </div>

        </div>

    </div>

    <!-- App Features Grid -->
    <div style="margin-top: 4rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        <div style="background: #111; border: 1px solid #222; border-radius: 18px; padding: 1.75rem; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🚀</div>
            <h4 style="color: white; font-weight: 700; font-size: 1rem;">Lightning Speed</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.35rem; line-height: 1.5;">Accelerated app experience with instant page loading.</p>
        </div>
        <div style="background: #111; border: 1px solid #222; border-radius: 18px; padding: 1.75rem; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🔔</div>
            <h4 style="color: white; font-weight: 700; font-size: 1rem;">Live Order Tracking</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.35rem; line-height: 1.5;">Track shipments and receive real-time package updates.</p>
        </div>
        <div style="background: #111; border: 1px solid #222; border-radius: 18px; padding: 1.75rem; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🔒</div>
            <h4 style="color: white; font-weight: 700; font-size: 1rem;">Encrypted Payments</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.35rem; line-height: 1.5;">Secure wallet system with single-tap instant checkout.</p>
        </div>
        <div style="background: #111; border: 1px solid #222; border-radius: 18px; padding: 1.75rem; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.75rem;">🎁</div>
            <h4 style="color: white; font-weight: 700; font-size: 1rem;">App-Only Discounts</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.35rem; line-height: 1.5;">Access mobile-exclusive sales and bonus reward points.</p>
        </div>
    </div>
</section>
