<?php require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $id = (int) $_POST['product_id'];
    $s = $conn->prepare('SELECT id FROM products WHERE id=?');
    $s->bind_param('i', $id);
    $s->execute();
    if ($s->get_result()->num_rows) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }
    header('Location: cart.php');
    exit;
}
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int) $_GET['remove']]);
    header('Location: cart.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['qty'] ?? [] as $id => $qty) {
        $id = (int) $id;
        $qty = (int) $qty;
        if ($qty < 1)
            unset($_SESSION['cart'][$id]);
        else
            $_SESSION['cart'][$id] = min($qty, 99);
    }
    header('Location: cart.php');
    exit;
}
$items = [];
$total = 0;
foreach ($_SESSION['cart'] ?? [] as $id => $qty) {
    $s = $conn->prepare('SELECT id,name,price,image FROM products WHERE id=?');
    $s->bind_param('i', $id);
    $s->execute();
    if ($p = $s->get_result()->fetch_assoc()) {
        $p['qty'] = $qty;
        $p['subtotal'] = $p['price'] * $qty;
        $total += $p['subtotal'];
        $items[] = $p;
    }
}
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cart</title>
</head>

<body>
    <h1>Shopping Cart</h1><a href="index.php">Continue Shopping</a>
    <?php if (!$items): ?>
        <p>Your cart is empty.</p><?php else: ?>
        <form method="post">
            <table border="1" cellpadding="8">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($items as $p): ?>
                    <tr>
                        <td><?= e($p['name']) ?></td>
                        <td>₹<?= number_format($p['price'], 2) ?></td>
                        <td><input type="number" min="0" max="99" name="qty[<?= $p['id'] ?>]" value="<?= $p['qty'] ?>"></td>
                        <td>₹<?= number_format($p['subtotal'], 2) ?></td>
                        <td><a href="cart.php?remove=<?= $p['id'] ?>">Remove</a></td>
                    </tr><?php endforeach; ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th colspan="2">₹<?= number_format($total, 2) ?></th>
                </tr>
            </table><button name="update">Update Cart</button>
        </form><?php endif; ?>
</body>

</html>