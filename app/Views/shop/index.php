<?php $pageTitle = 'Shop — ShopX Global'; ?>

<style>
.shop-filter-bar {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 1rem;
    margin-bottom: 2rem;
    align-items: center;
}
@media (max-width: 768px) {
    .shop-filter-bar {
        grid-template-columns: 1fr !important;
    }
    .shop-filter-bar select {
        width: 100%;
    }
}
</style>

<section class="container fade-in" style="padding: 2rem 0;">
    <!-- Search & Filter Bar -->
    <form method="GET" action="<?= url('/shop') ?>" class="shop-filter-bar">
        <div style="position: relative;">
            <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" class="input-dark" placeholder="Search products..." value="<?= e($filters['search'] ?? '') ?>" style="padding-left: 2.75rem;">
        </div>
        
        <select name="category" class="select-dark">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <select name="sort" class="select-dark" onchange="this.form.submit()">
            <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest First</option>
            <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
            <option value="rating" <?= ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' ?>>Top Rated</option>
            <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>Most Popular</option>
        </select>
    </form>
    
    <!-- Product Count -->
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
        Showing <strong style="color: var(--gold-primary);"><?= $totalProducts ?></strong> products
    </p>
    
    <!-- Product Grid -->
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 4rem 0;">
            <p style="font-size: 1.2rem; color: var(--text-muted);">No products found.</p>
            <a href="<?= url('/shop') ?>" class="btn-gold" style="margin-top: 1rem;">View All Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; flex-wrap: wrap;">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= url('/shop?page=' . ($currentPage - 1) . '&search=' . urlencode($filters['search'] ?? '') . '&category=' . ($filters['category_id'] ?? '') . '&sort=' . ($filters['sort'] ?? '')) ?>" 
                       class="btn-outline btn-sm">← Prev</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= url('/shop?page=' . $i . '&search=' . urlencode($filters['search'] ?? '') . '&category=' . ($filters['category_id'] ?? '') . '&sort=' . ($filters['sort'] ?? '')) ?>" 
                       class="<?= $i === $currentPage ? 'btn-gold btn-sm' : 'btn-outline btn-sm' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('/shop?page=' . ($currentPage + 1) . '&search=' . urlencode($filters['search'] ?? '') . '&category=' . ($filters['category_id'] ?? '') . '&sort=' . ($filters['sort'] ?? '')) ?>" 
                       class="btn-outline btn-sm">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
