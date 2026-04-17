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

    $cart_id = (int)$_POST['cart_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);

    $success_msg[] = "Item removed from cart";
}

/* ================= UPDATE QTY ================= */
if (isset($_POST['update_cart'])) {

    $cart_id = (int)$_POST['cart_id'];
    $qty = (int)$_POST['qty'];

    if ($qty < 1) $qty = 1;

    $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$qty, $cart_id, $user_id]);

    $success_msg[] = "Cart updated successfully";
}

/* ================= EMPTY CART (FIXED) ================= */
if (isset($_POST['empty_cart'])) {

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $success_msg[] = "Cart emptied successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">
<title>Cart</title>
</head>

<body>

<?php include 'user_header.php'; ?>

<div class="products">

<h1 style="text-align:center;">Products in Cart</h1>

<div class="box-container">

<?php
$grand_total = 0;

$stmt = $conn->prepare("
    SELECT cart.id AS cart_id, cart.qty,
           products.*
    FROM cart
    INNER JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);

if ($stmt->rowCount() > 0) {

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $price = (float)$row['price'];
        $qty = (int)$row['qty'];
        $sub_total = $price * $qty;
        $grand_total += $sub_total;
?>

<form method="post" class="box <?= ($row['stock'] == 0) ? 'disabled' : '' ?>">

    <input type="hidden" name="cart_id" value="<?= $row['cart_id']; ?>">

    <img src="../images/products/<?= htmlspecialchars($row['thumb_one']); ?>" width="120">

    <?php if ($row['stock'] > 9) { ?>
        <span style="color:green;">In Stock</span>

    <?php } elseif ($row['stock'] > 0) { ?>
        <span style="color:orange;">Only <?= $row['stock']; ?> left</span>

    <?php } else { ?>
        <span style="color:red;">Out of Stock</span>
    <?php } ?>

    <h3><?= htmlspecialchars($row['name']); ?></h3>

    <p>Price: $<?= number_format($price, 2); ?></p>

    <p><strong>Sub Total: $<?= number_format($sub_total, 2); ?></strong></p>

    <input type="number" name="qty" min="1" max="99"
           value="<?= $qty; ?>"
           <?= ($row['stock'] == 0) ? 'disabled' : '' ?>>

    <button type="submit" name="update_cart">Update</button>

    <button type="submit" name="delete"
            onclick="return confirm('Remove from cart?');">
        Delete
    </button>

</form>

<?php
    }
} else {
    echo '<p style="text-align:center;">Your cart is empty!</p>';
}
?>

</div>

<?php if ($grand_total > 0) { ?>

<div style="text-align:center; margin-top:30px;">
    <h2>Total: $<?= number_format($grand_total, 2); ?></h2>

    <!-- FIXED EMPTY CART -->
    <form method="post">
        <button type="submit" name="empty_cart" class="btn"
                onclick="return confirm('Empty cart?');">
            Empty Cart
        </button>

        <a href="checkout.php" class="btn">
            Proceed to Checkout
        </a>
    </form>
</div>

<?php } ?>

</div>

<?php include 'user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>

</body>
</html>