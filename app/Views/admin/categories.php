<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Category Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Create Category</h3>
            <p class="text-xs text-gray-500 mb-6">Create a product catalog category</p>
            
            <form action="<?= url('/admin/categories/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Category Name</label>
                    <input type="text" name="name" required placeholder="e.g. Gaming Gear" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">URL Slug (Optional)</label>
                    <input type="text" name="slug" placeholder="e.g. gaming-gear" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Visual Icon (Emoji)</label>
                    <input type="text" name="icon" placeholder="e.g. 🎧" class="input-dark">
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🏷️ Add Catalog Category
                </button>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Icon</th>
                        <th>Category Name</th>
                        <th>URL Slug</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 font-semibold">No catalog categories found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $c): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $c['id'] ?></td>
                                <td class="text-lg"><?= e($c['icon']) ?></td>
                                <td class="font-semibold text-white"><?= e($c['name']) ?></td>
                                <td class="font-mono text-xs text-gray-400">/shop?category=<?= e($c['slug']) ?></td>
                                <td>
                                    <form action="<?= url('/admin/categories/delete/' . $c['id']) ?>" method="POST" onsubmit="return confirm('Delete this category? Products in this category will also be affected.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
