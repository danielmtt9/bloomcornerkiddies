<?php

declare(strict_types=1);

$isEdit = isset($state);
$messages = admin_flash_messages();
?>
  <section class="panel">
    <div class="button-row" style="justify-content:space-between; align-items:flex-start;">
      <div>
        <h1 class="page-title"><?= $isEdit ? 'Edit Product' : 'Add Product' ?></h1>
        <p class="page-copy">Required: name, price, one or more categories, at least one size, and at least one photo.</p>
      </div>
      <a class="button-link" href="/admin/products.php">Back to Products</a>
    </div>
  </section>

  <?php if (isset($messages['success'])): ?>
    <div class="flash flash--success"><?= htmlspecialchars($messages['success'], ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php foreach ($errors as $error): ?>
    <div class="flash flash--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endforeach; ?>

  <section class="panel">
    <form method="post" enctype="multipart/form-data" id="product-form">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $state['id'] ?>">
      <?php endif; ?>

      <div class="field-grid">
        <div class="field">
          <label for="name">Product Name</label>
          <input id="name" name="name" type="text" maxlength="255" value="<?= htmlspecialchars((string) $form['name'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="price">Price (₦)</label>
          <input id="price" name="price" type="number" min="1" step="1" value="<?= htmlspecialchars((string) $form['price'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="original_price">Original Price (₦)</label>
          <input id="original_price" name="original_price" type="number" min="1" step="1" value="<?= htmlspecialchars((string) $form['original_price'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
            <?php foreach (['girls' => 'Girls', 'boys' => 'Boys', 'unisex' => 'Unisex', 'babies' => 'Babies'] as $value => $label): ?>
              <option value="<?= $value ?>" <?= $form['gender'] === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="categories">Categories</label>
        <select id="categories" name="categories[]" multiple size="<?= max(4, min(8, count($categories))) ?>" required>
          <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id'] ?>" <?= in_array((int) $category['id'], $form['categories'], true) ? 'selected' : '' ?>>
              <?= htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="hint">Use multi-select for one or many categories.</div>
      </div>

      <div class="field-grid" style="margin-top:14px;">
        <div class="field">
          <label for="brand">Brand</label>
          <input id="brand" name="brand" type="text" value="<?= htmlspecialchars((string) $form['brand'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
          <label for="material">Material</label>
          <input id="material" name="material" type="text" value="<?= htmlspecialchars((string) $form['material'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
          <label for="season_tag">Season / Occasion</label>
          <input id="season_tag" name="season_tag" type="text" value="<?= htmlspecialchars((string) $form['season_tag'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
          <label for="tiktok_url">TikTok URL</label>
          <input id="tiktok_url" name="tiktok_url" type="url" value="<?= htmlspecialchars((string) $form['tiktok_url'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
      </div>

      <div class="field" style="margin-top:14px;">
        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars((string) $form['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <div class="field checkbox-grid" style="margin-top:14px;">
        <label class="checkbox">
          <input type="checkbox" name="is_available" value="1" <?= !empty($form['is_available']) ? 'checked' : '' ?>>
          <span>Show this product on the storefront.</span>
        </label>
      </div>
    </form>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Size Manager</h2>
    <p class="page-copy">Add one or more sizes. Stock `0` automatically behaves as sold out.</p>
    <div class="stack-row" style="margin:14px 0;">
      <?php foreach (PRODUCT_AGE_SIZES as $size): ?>
        <button type="button" class="chip-button" data-add-size="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></button>
      <?php endforeach; ?>
    </div>
    <div class="stack-row" style="margin-bottom:14px;">
      <?php foreach (PRODUCT_NUMBER_SIZES as $size): ?>
        <button type="button" class="chip-button" data-add-size="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></button>
      <?php endforeach; ?>
    </div>
    <div class="field-grid" style="margin-bottom:14px;">
      <div class="field">
        <label for="custom-size">Custom Size</label>
        <input id="custom-size" type="text" placeholder="e.g. 12-18M">
      </div>
      <div class="field" style="align-content:end;">
        <button type="button" id="add-custom-size">Add Size</button>
      </div>
    </div>
    <div class="table-wrap">
      <table class="size-table" id="size-table">
        <thead>
          <tr>
            <th>Size</th>
            <th>Stock Count</th>
            <th>Sold Out</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($form['sizes'] as $index => $size): ?>
            <tr>
              <td><input form="product-form" type="text" name="sizes[<?= $index ?>][size_label]" value="<?= htmlspecialchars((string) $size['size_label'], ENT_QUOTES, 'UTF-8') ?>" required></td>
              <td><input form="product-form" type="number" min="0" name="sizes[<?= $index ?>][stock_qty]" value="<?= htmlspecialchars((string) $size['stock_qty'], ENT_QUOTES, 'UTF-8') ?>" required></td>
              <td><input form="product-form" type="checkbox" name="sizes[<?= $index ?>][is_sold_out]" value="1" <?= !empty($size['is_sold_out']) ? 'checked' : '' ?>></td>
              <td><button type="button" class="chip-button" data-remove-row>Remove</button></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="panel">
    <h2 class="page-title" style="font-size:1.25rem;">Product Photos</h2>
    <p class="page-copy">JPEG, PNG, and WebP only. Max 5MB each. Use 4:5 portrait images for the best storefront result (optional auto-crop can be enabled on supported servers).</p>
    <div class="field" style="margin-top:14px;">
      <label for="images">Upload Photos</label>
      <input form="product-form" id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple <?= $isEdit ? '' : 'required' ?>>
    </div>

    <?php if ($isEdit && !empty($state['images'])): ?>
      <div class="image-grid" style="margin-top:14px;">
        <?php foreach ($state['images'] as $image): ?>
          <div class="image-card">
            <img class="thumbnail" style="width:100%;height:160px;" src="/<?= htmlspecialchars((string) $image['file_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
            <label class="checkbox" style="margin-top:10px;">
              <input form="product-form" type="checkbox" name="remove_image_ids[]" value="<?= (int) $image['id'] ?>" <?= in_array((int) $image['id'], $form['remove_image_ids'], true) ? 'checked' : '' ?>>
              <span>Remove this photo</span>
            </label>
            <div class="hint">Sort order <?= (int) $image['sort_order'] ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="panel">
    <div class="button-row">
      <button form="product-form" type="submit"><?= $isEdit ? 'Save Changes' : 'Create Product' ?></button>
      <a class="button-link" href="/admin/products.php">Cancel</a>
    </div>
  </section>

  <script>
    (() => {
      const tbody = document.querySelector('#size-table tbody');
      const customInput = document.querySelector('#custom-size');
      let rowIndex = <?= count($form['sizes']) ?>;

      function addRow(sizeLabel = '') {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><input form="product-form" type="text" name="sizes[${rowIndex}][size_label]" value="${sizeLabel.replace(/"/g, '&quot;')}" required></td>
          <td><input form="product-form" type="number" min="0" name="sizes[${rowIndex}][stock_qty]" value="1" required></td>
          <td><input form="product-form" type="checkbox" name="sizes[${rowIndex}][is_sold_out]" value="1"></td>
          <td><button type="button" class="chip-button" data-remove-row>Remove</button></td>
        `;
        rowIndex += 1;
        tbody.appendChild(row);
      }

      document.querySelectorAll('[data-add-size]').forEach((button) => {
        button.addEventListener('click', () => addRow(button.dataset.addSize || ''));
      });

      document.querySelector('#add-custom-size')?.addEventListener('click', () => {
        const value = (customInput.value || '').trim();
        if (!value) return;
        addRow(value);
        customInput.value = '';
      });

      tbody.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement) || !target.hasAttribute('data-remove-row')) return;
        const rows = tbody.querySelectorAll('tr');
        if (rows.length <= 1) return;
        target.closest('tr')?.remove();
      });
    })();
  </script>
