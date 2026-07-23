<div style="margin-bottom: 2rem;">
    <a href="<?= url('/admin/products') ?>" style="color: var(--text-muted); font-size: 0.9rem;">
        ← Back to Inventory
    </a>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">🛍️ Add New Product</h2>
    
    <form method="POST" action="<?= url('/admin/products/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>Product Title</label>
            <input type="text" name="title" class="input-dark" placeholder="e.g. Sony WH-1000XM5 Headphones" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="input-dark" rows="4" placeholder="Enter product description..." style="resize: vertical;"></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Sale Price ($)</label>
                <input type="number" name="price" class="input-dark" step="0.01" min="0.01" placeholder="299.99" required>
            </div>
            
            <div class="form-group">
                <label>Original Price ($) <span style="font-size: 0.75rem; color: var(--text-muted);">(Optional - for strikethrough)</span></label>
                <input type="number" name="original_price" class="input-dark" step="0.01" min="0.01" placeholder="349.99">
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Stock Level</label>
                <input type="number" name="stock" class="input-dark" min="0" value="50" required>
            </div>
            
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="select-dark" required style="width: 100%;">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>Product Image Upload</label>
            <input type="file" name="image" class="input-dark" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif" style="padding: 0.6rem;">
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Select an image file from your device (PNG, JPG, WEBP, or GIF).</p>
        </div>
        
        <div class="form-group" style="margin-top: 1rem;">
            <label>OR Image URL <span style="font-size: 0.75rem; color: var(--text-muted);">(Optional - if not uploading a file)</span></label>
            <input type="text" name="image_url" class="input-dark" placeholder="e.g. /assets/images/products/sony.jpg">
        </div>
        
        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.5rem;">
            <input type="checkbox" name="is_hot" id="isHot" value="1" style="width: 18px; height: 18px; cursor: pointer;">
            <label for="isHot" style="cursor: pointer; margin-bottom: 0;">✨ Mark as Hot product (featured on homepage)</label>
        </div>
        
        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; margin-top: 2rem; padding: 1rem;">
            Create Product
        </button>
    </form>
</div>
