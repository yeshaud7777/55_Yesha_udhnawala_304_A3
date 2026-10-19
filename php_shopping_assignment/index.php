<?php require_once 'config.php';
$cats = $conn->query("SELECT id,name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font: 16px Arial;
            margin: 24px;
            color: #222
        }

        nav a {
            margin-right: 14px
        }

        .layout {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 22px;
            margin-top: 20px
        }

        aside,
        main {
            border: 1px solid #aaa;
            padding: 14px
        }

        aside a {
            display: block;
            padding: 7px;
            color: #0645ad;
            cursor: pointer
        }

        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(140px, 1fr));
            gap: 12px
        }

        .product {
            border: 1px solid #ddd;
            padding: 10px
        }

        .product img {
            width: 100%;
            height: 130px;
            object-fit: contain
        }

        .pager button {
            margin: 5px;
            padding: 6px 10px
        }

        @media(max-width:650px) {
            .layout {
                grid-template-columns: 1fr
            }

            .products {
                grid-template-columns: repeat(2, 1fr)
            }
        }
    </style>
</head>

<body>
    <h1>Shopping Cart</h1>
    <nav>
        <a href="index.php">Home</a> <a href="register.php">Register</a> <a href="login.php">User Login</a>
        <a href="cart.php">Cart (<?= count($_SESSION['cart'] ?? []) ?>)</a>
        <?php if (!empty($_SESSION['user_id'])): ?><a href="logout.php">Logout</a><?php endif; ?>
    </nav>
    <div class="layout">
        <aside><b>Categories</b>
            <?php foreach ($cats as $c): ?><a href="#"
                    onclick="loadProducts(<?= (int) $c['id'] ?>,1);return false"><?= e($c['name']) ?></a><?php endforeach; ?>
        </aside>
        <main>
            <h2 id="heading">Products</h2>
            <div id="products">Select a category to view products.</div>
        </main>
    </div>
    <script>
        async function loadProducts(category, page) {
            document.getElementById('heading').textContent = 'Products';
            const box = document.getElementById('products'); box.textContent = 'Loading...';
            try { const r = await fetch('products_ajax.php?category=' + category + '&page=' + page); box.innerHTML = await r.text(); }
            catch (e) { box.textContent = 'Could not load products. Check Apache/MySQL.'; }
        }
    </script>
</body>

</html>