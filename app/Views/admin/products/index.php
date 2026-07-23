<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="font-size: 1.25rem; font-weight: 700;">🛍️ Catalog Inventory (<?= count($products) ?> items)</h2>
    <a href="<?= url('/admin/products/create') ?>" class="btn-gold">
        + Add New Product
    </a>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>#<?= $p['id'] ?></td>
                        <td>
                            <img src="<?= e(url($p['image_url'])) ?>" alt="" style="width: 50px; height: 38px; object-fit: cover; border-radius: 4px; background: var(--bg-secondary);"
                                 onerror="this.src='https://placehold.co/50x38/1a1a1a/666'">
                        </td>
                        <td style="font-weight: 600; color: white;">
                            <?= e($p['title']) ?>
                            <?php if ($p['is_hot']): ?>
                                <span class="badge-hot" style="font-size: 0.6rem; padding: 1px 6px;">HOT</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($p['category_name']) ?></td>
                        <td><?= formatPrice($p['price']) ?></td>
                        <td>
                            <span style="font-weight: 700; color: <?= $p['stock'] <= 5 ? 'var(--red-badge)' : ($p['stock'] <= 15 ? 'var(--orange-badge)' : 'var(--green-save)') ?>;">
                                <?= $p['stock'] ?> units
                            </span>
                        </td>
                        <td>
                            <span class="badge-status <?= $p['is_active'] ? 'badge-delivered' : 'badge-cancelled' ?>">
                                <?= $p['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <a href="<?= url('/admin/products/edit/' . $p['id']) ?>" class="btn-outline btn-sm" style="padding: 0.4rem 0.8rem;">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="<?= url('/admin/products/delete/' . $p['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-danger btn-sm" style="padding: 0.4rem 0.8rem;">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
