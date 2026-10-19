<?php
require_once 'config.php';
$category = max(0, (int) ($_GET['category'] ?? 0));
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 6;
$offset = ($page - 1) * $perPage;
$stmt = $conn->prepare("SELECT COUNT(*) total FROM products WHERE category_id=?");
$stmt->bind_param('i', $category);
$stmt->execute();
$total = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt = $conn->prepare("SELECT id,name,description,price,image FROM products WHERE category_id=? ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param('iii', $category, $perPage, $offset);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$pages = max(1, (int) ceil($total / $perPage));
if (!$products)
    echo '<p>No products in this category yet. Admin can add products.</p>';
echo '<div class="products">';
foreach ($products as $p) {
    echo '<div class="product">';
    if ($p['image'] && is_file(__DIR__ . '/uploads/' . $p['image']))
        echo '<img src="uploads/' . e($p['image']) . '" alt="">';
    else
        echo '<div style="height:130px;display:grid;place-items:center;background:#f4f4f4">No image</div>';
    echo '<h3>' . e($p['name']) . '</h3><p>' . e($p['description']) . '</p><b>₹' . number_format((float) $p['price'], 2) . '</b>';
    echo '<form method="post" action="cart.php"><input type="hidden" name="product_id" value="' . (int) $p['id'] . '"><button name="add" value="1">Add to Cart</button></form></div>';
}
echo '</div><div class="pager">';
for ($i = 1; $i <= $pages; $i++)
    echo '<button onclick="loadProducts(' . $category . ',' . $i . ')">' . $i . '</button>';
echo '</div>';
