<div class="card fade-in" style="padding: 2.5rem; border-color: var(--border-gold); box-shadow: 0 10px 40px var(--gold-glow);">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div class="logo" style="display: inline-flex; flex-direction: column; align-items: center;">
            <div class="logo-main" style="font-size: 2.2rem; font-weight: 900;">SHOP<span>X</span></div>
            <div class="logo-sub" style="font-size: 0.65rem; letter-spacing: 0.35em;">C O N T R O L</div>
        </div>
    </div>
    
    <h2 style="font-size: 1.25rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem;">Administrator Authentication</h2>
    <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; margin-bottom: 2rem;">Authorized personnel only</p>
    
    <form method="POST" action="<?= url('/admin/login') ?>">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>Admin Email</label>
            <div style="position: relative;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">📧</span>
                <input type="email" name="email" class="input-dark" placeholder="admin@shopx.com" style="padding-left: 2.5rem;" required autofocus>
            </div>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <div style="position: relative;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">🔒</span>
                <input type="password" name="password" class="input-dark" placeholder="••••••••" style="padding-left: 2.5rem;" required>
            </div>
        </div>
        
        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; margin-top: 1rem; font-size: 1rem; padding: 0.9rem;">
            Authenticate Console
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 2rem;">
        <a href="<?= url('/') ?>" style="color: var(--text-muted); font-size: 0.85rem;">
            ← Return to public store
        </a>
    </div>
</div>
