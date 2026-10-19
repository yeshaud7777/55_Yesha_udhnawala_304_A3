<?php require_once __DIR__ . '/../config.php';
require_admin();
$msg = '';
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $s = $conn->prepare('SELECT image FROM products WHERE id=?');
    $s->bind_param('i', $id);
    $s->execute();
    $old = $s->get_result()->fetch_assoc();
    $d = $conn->prepare('DELETE FROM products WHERE id=?');
    $d->bind_param('i', $id);
    $d->execute();
    if ($old && $old['image'] && is_file(__DIR__ . '/../uploads/' . $old['image']))
        unlink(__DIR__ . '/../uploads/' . $old['image']);
    header('Location: products.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $cat = (int) ($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $image = null;
    if ($name === '' || $cat < 1 || $price < 0)
        $msg = 'Enter valid product details.';
    else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['image']['size'] > 4 * 1024 * 1024)
                $msg = 'Image must be smaller than 4 MB.';
            else {
                $info = getimagesize($_FILES['image']['tmp_name']);
                $mime = $info['mime'] ?? '';
                $ext = match ($mime) { 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp', default => ''};
                if (!$ext)
                    $msg = 'Only JPG, PNG, GIF, WEBP images allowed.';
                else {
                    $image = bin2hex(random_bytes(8)) . '.' . $ext;
                    $target = __DIR__ . '/../uploads/' . $image;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $image = null;
                        $msg = 'Could not upload image.';
                    } elseif (function_exists('imagecreatetruecolor')) {
                        $src = null;
                        switch ($mime) {
                            case 'image/jpeg':
                                $src = @imagecreatefromjpeg($target);
                                break;
                            case 'image/png':
                                $src = @imagecreatefrompng($target);
                                break;
                            case 'image/gif':
                                $src = @imagecreatefromgif($target);
                                break;
                            case 'image/webp':
                                if (function_exists('imagecreatefromwebp'))
                                    $src = @imagecreatefromwebp($target);
                                break;
                        }
                        if ($src) {
                            $w = imagesx($src);
                            $h = imagesy($src);
                            $nw = min(600, $w);
                            $nh = (int) max(1, $h * $nw / $w);
                            $dst = imagecreatetruecolor($nw, $nh);
                            if ($mime === 'image/png' || $mime === 'image/gif') {
                                imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                                imagealphablending($dst, false);
                                imagesavealpha($dst, true);
                            }
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
                            switch ($mime) {
                                case 'image/jpeg':
                                    imagejpeg($dst, $target, 85);
                                    break;
                                case 'image/png':
                                    imagepng($dst, $target, 7);
                                    break;
                                case 'image/gif':
                                    imagegif($dst, $target);
                                    break;
                                case 'image/webp':
                                    if (function_exists('imagewebp'))
                                        imagewebp($dst, $target, 85);
                                    break;
                            }
                            imagedestroy($src);
                            imagedestroy($dst);
                        }
                    }
                }
            }
        }
        if (!$msg) {
            if ($id) {
                if ($image) {
                    $s = $conn->prepare('SELECT image FROM products WHERE id=?');
                    $s->bind_param('i', $id);
                    $s->execute();
                    $old = $s->get_result()->fetch_assoc();
                    $s = $conn->prepare('UPDATE products SET category_id=?,name=?,description=?,price=?,image=? WHERE id=?');
                    $s->bind_param('issdsi', $cat, $name, $desc, $price, $image, $id);
                    $s->execute();
                    if ($old && $old['image'] && is_file(__DIR__ . '/../uploads/' . $old['image']))
                        unlink(__DIR__ . '/../uploads/' . $old['image']);
                } else {
                    $s = $conn->prepare('UPDATE products SET category_id=?,name=?,description=?,price=? WHERE id=?');
                    $s->bind_param('issdi', $cat, $name, $desc, $price, $id);
                    $s->execute();
                }
            } else {
                $s = $conn->prepare('INSERT INTO products(category_id,name,description,price,image) VALUES(?,?,?,?,?)');
                $s->bind_param('issds', $cat, $name, $desc, $price, $image);
                $s->execute();
            }
            header('Location: products.php');
            exit;
        }
    }
}
$cats = $conn->query('SELECT id,name FROM categories ORDER BY name')->fetch_all(MYSQLI_ASSOC);
$rows = $conn->query('SELECT p.*,c.name category FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC')->fetch_all(MYSQLI_ASSOC);
$edit = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    $es = $conn->prepare('SELECT * FROM products WHERE id=?');
    $es->bind_param('i', $eid);
    $es->execute();
    $edit = $es->get_result()->fetch_assoc();
}
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Products</title>
</head>

<body>
    <h1>Manage Products</h1><a href="index.php">Dashboard</a>
    <p style="color:red"><?= e($msg) ?></p>
    <form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <p>Category <select name="category_id" required><?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ((int) ($edit['category_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['name']) ?></option><?php endforeach; ?>
            </select></p>
        <p>Name <input name="name" value="<?= e($edit['name'] ?? '') ?>" required></p>
        <p>Description <textarea name="description"><?= e($edit['description'] ?? '') ?></textarea></p>
        <p>Price <input type="number" min="0" step="0.01" name="price" value="<?= e($edit['price'] ?? '') ?>" required></p>
        <p>Picture <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <?php if (!empty($edit['image'])): ?><img width="60" src="../uploads/<?= e($edit['image']) ?>"><?php endif; ?>
        </p><button><?= $edit ? 'Update Product' : 'Save Product' ?></button>
    </form>
    <h2>Existing Products</h2>
    <table border="1" cellpadding="7">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?php if ($r['image']): ?><img width="70" src="../uploads/<?= e($r['image']) ?>"><?php endif; ?></td>
                <td><?= e($r['name']) ?></td>
                <td><?= e($r['category']) ?></td>
                <td>₹<?= number_format($r['price'], 2) ?></td>
                <td><a href="?edit=<?= $r['id'] ?>">Edit</a> | <a href="?delete=<?= $r['id'] ?>"
                        onclick="return confirm('Delete product?')">Delete</a></td>
            </tr><?php endforeach; ?>
    </table>
</body>

</html>