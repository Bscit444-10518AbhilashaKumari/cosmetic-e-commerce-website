<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? '';

if ($user_id == '') {
    header('location:login.php');
    exit;
}

$success_msg = [];
$warning_msg = [];

/* ================= DELETE ITEM ================= */
if (isset($_POST['delete'])) {

    $wishlist_id = (int)$_POST['wishlist_id'];

    $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ? AND user_id = ?");
    $stmt->execute([$wishlist_id, $user_id]);

    $success_msg[] = "Wishlist item deleted";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">
<title>Wishlist</title>
</head>

<body>

<?php include 'user_header.php'; ?>

<div class="products">
    <div class="heading">
        <h1 style="text-align:center;">Products in your wishlist</h1>
        <img src="../images/separator.png">
    </div>

    <div class="box-container">

<?php
$select_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
$select_wishlist->execute([$user_id]);

if ($select_wishlist->rowCount() > 0) {

    while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {

        $product_id = $fetch_wishlist['product_id'];

        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $select_products->execute([$product_id]);

        if ($select_products->rowCount() > 0) {

            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
?>

<form action="" method="post" class="box <?php if ($fetch_products['stock'] == 0) echo 'disabled'; ?>">

    <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">

    <div class="icon">
        <div class="icon-box">
            <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img1">
            <img src="../images/products/<?= $fetch_products['thumb_two']; ?>" class="img2">
        </div>
    </div>

    <!-- STOCK STATUS -->
    <?php if ($fetch_products['stock'] > 9) { ?>
        <span class="stock" style="color:green;">In stock</span>

    <?php } elseif ($fetch_products['stock'] > 0) { ?>
        <span class="stock" style="color:orange;">
            Hurry only <?= $fetch_products['stock']; ?> left
        </span>

    <?php } else { ?>
        <span class="stock" style="color:red;">Out of stock</span>
    <?php } ?>

    <!-- PRICE -->
    <div class="flex">
        <p class="price">₹<?= $fetch_products['price']; ?></p>
    </div>

    <!-- PRODUCT NAME -->
    <div class="content">
        <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>

        <!-- ACTION BUTTONS -->
        <div class="button">

            <button type="submit" name="delete" onclick="return confirm('Remove from wishlist?');">
                🗑 Remove
            </button>

            <a href="view_page.php?get_id=<?= $fetch_products['id']; ?>" class="btn">
                View
            </a>

        </div>

        <!-- BUY NOW -->
        <div class="flex-btn">
            <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn" style="width:100%;">
                Buy Now
            </a>
        </div>

    </div>

</form>

<?php
        }
    }

} else {
    echo '
    <div class="empty">
        <p>No products added in your wishlist</p>
    </div>
    ';
}
?>

    </div>
</div>

<?php include 'user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>

</body>
</html>