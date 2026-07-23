<?php $pageTitle = 'Sign In — ShopX Global'; ?>
<div class="auth-container fade-in">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="<?= url('/') ?>" class="logo" style="display: inline-flex; flex-direction: column; align-items: center;">
                <div class="logo-main" style="font-size: 2rem;">SHOP<span>X</span></div>
                <div class="logo-sub">G L O B A L</div>
            </a>
        </div>
        
        <h1>Welcome to ShopX</h1>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Sign in to continue</p>
        
        <form method="POST" action="<?= url('/login') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>Email</label>
                <div style="position: relative;">
                    <svg style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <input type="email" name="email" class="input-dark" placeholder="you@example.com" value="<?= old('email') ?>" style="padding-left: 2.5rem;" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div style="position: relative;">
                    <svg style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <input type="password" name="password" class="input-dark" placeholder="••••••••" style="padding-left: 2.5rem;" required>
                </div>
            </div>
            
            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; margin-top: 0.5rem;">
                Sign in
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
            Don't have an account? <a href="<?= url('/register') ?>" class="text-gold" style="font-weight: 600;">Create Account</a>
        </p>
    </div>
</div>
