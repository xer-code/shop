<section class="container fade-in" style="padding: 3rem 0; max-width: 600px;">
    <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; color: #fff;">👤 My Profile Settings</h1>
    
    <div class="card" style="padding: 2.5rem; background: #151515; border: 1px solid var(--border-dark); border-radius: 16px;">
        <form method="POST" action="<?= url('/profile/update') ?>" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            
            <!-- Avatar Edit Section -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1rem; padding-bottom: 1.5rem; border-b: 1px solid var(--border-dark); margin-bottom: 1.5rem;">
                <div style="position: relative; width: 100px; height: 100px; border-radius: 50%; border: 2px solid var(--border-gold); overflow: hidden; background: #222; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img id="avatar-preview" src="<?= url('/' . $user['avatar']) ?>" alt="Profile avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span id="avatar-initial" style="font-size: 2.5rem; font-weight: 800; color: var(--gold-primary);"><?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="profile-avatar-input" style="display: inline-block; padding: 6px 16px; background: #222; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold-primary)'" onmouseout="this.style.borderColor='var(--border-dark)'">
                        📷 Choose Profile Avatar
                    </label>
                    <input type="file" name="avatar" id="profile-avatar-input" accept="image/*" style="display: none;" onchange="previewUserAvatar(this)">
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.5rem;">Accepts PNG, JPG, or JPEG format</p>
                </div>
            </div>

            <!-- Profile Info Fields -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Display Name</label>
                    <input type="text" name="name" value="<?= e($user['name']) ?>" required class="input-dark" style="width: 100%; font-size: 0.9rem; padding: 0.8rem 1rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Email Address</label>
                    <input type="email" name="email" value="<?= e($user['email']) ?>" required class="input-dark" style="width: 100%; font-size: 0.9rem; padding: 0.8rem 1rem;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="••••••••" class="input-dark" style="width: 100%; font-size: 0.9rem; padding: 0.8rem 1rem;">
                </div>
            </div>

            <div style="padding-top: 1rem;">
                <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 0.95rem; font-weight: 700; border-radius: 10px;">
                    💾 Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</section>

<script>
function previewUserAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById('avatar-preview');
            if (!img) {
                // If avatar preview doesn't exist, create it and hide initial
                const wrapper = document.getElementById('avatar-initial').parentNode;
                document.getElementById('avatar-initial').style.display = 'none';
                img = document.createElement('img');
                img.id = 'avatar-preview';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                wrapper.appendChild(img);
            }
            img.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
