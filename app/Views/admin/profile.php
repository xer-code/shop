<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Admin Profile Settings</h3>
            <p class="text-xs text-gray-500">Update system credentials, passwords, and administrator profile picture</p>
        </div>
    </div>

    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-8 max-w-2xl mx-auto shadow-2xl">
        <form method="POST" action="<?= url('/admin/profile/update') ?>" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            
            <!-- Avatar Edit Section -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1rem; padding-bottom: 1.5rem; border-b: 1px solid #2a2a2a;" class="pb-6 mb-6">
                <div style="position: relative; width: 110px; height: 110px; border-radius: 50%; border: 2px solid var(--border-gold); overflow: hidden; background: #111; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img id="admin-avatar-preview" src="<?= url('/' . $user['avatar']) ?>" alt="Admin avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span id="admin-avatar-initial" style="font-size: 2.8rem; font-weight: 800; color: var(--gold-primary);"><?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="admin-avatar-input" style="display: inline-block; padding: 6px 16px; background: #222; border: 1px solid #2a2a2a; border-radius: 8px; color: #fff; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold-primary)'" onmouseout="this.style.borderColor='#2a2a2a'">
                        📷 Choose Administrator Photo
                    </label>
                    <input type="file" name="avatar" id="admin-avatar-input" accept="image/*" style="display: none;" onchange="previewAdminAvatar(this)">
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.5rem;">PNG, JPG, or JPEG format supported</p>
                </div>
            </div>

            <!-- Profile Info Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Display Name</label>
                    <input type="text" name="name" value="<?= e($user['name']) ?>" required class="input-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="<?= e($user['email']) ?>" required class="input-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="••••••••" class="input-dark">
                </div>
            </div>

            <div class="pt-4 border-t border-[#2a2a2a]">
                <button type="submit" class="btn-gold w-full py-2.5 justify-center font-bold text-sm">
                    💾 Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewAdminAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById('admin-avatar-preview');
            if (!img) {
                const wrapper = document.getElementById('admin-avatar-initial').parentNode;
                document.getElementById('admin-avatar-initial').style.display = 'none';
                img = document.createElement('img');
                img.id = 'admin-avatar-preview';
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
