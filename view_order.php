<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';
if($user_id == ''){
   header('location:login.php');
   exit;
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    header('location:order.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

<title>Order Details</title>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
      <h1 style="text-transform: uppercase;">orders detail</h1>
      <span><a href="home.php">home</a> → my order</span>
    </div>
</div>

<div class="view_order">
<div class="heading">
    <h1>orders details</h1>
    <img src="../images/separator.png">
</div>

<div class="box-container">

<?php
$grand_total = 0;

/* ===== GET ORDER ===== */
$select_order = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1");
$select_order->execute([$get_id, $user_id]);

if($select_order->rowCount() > 0){

    $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);
    $product_id = $fetch_order['product_id'];

/* ===== GET PRODUCT ===== */
    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $select_products->execute([$product_id]);

    if($select_products->rowCount() > 0){

        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);

        $sub_total = $fetch_products['price'] * $fetch_order['qty'];
        $grand_total = $sub_total;
?>

<div class="box">

<div class="col">
    <div class="thumb">
        <div class="big-image">
            <img src="../images/products/<?= $fetch_products['thumb_one']; ?>">
        </div>
        <div class="small-images">
            <img src="../images/products/<?= $fetch_products['thumb_two']; ?>">
            <img src="../images/products/<?= $fetch_products['thumb_three']; ?>">
            <img src="../images/products/<?= $fetch_products['thumb_four']; ?>">
            <img src="../images/products/<?= $fetch_products['thumb_one']; ?>">
        </div>
    </div>
    <div class="date">
        <i class="bx bxs-calendar-alt"></i>
        <?= $fetch_order['order_date']; ?>
    </div>
</div>

<div class="col">
    <div class="detail">
        <h3 class="name"><?= $fetch_products['name']; ?></h3>
        <p class="grand-total">total amount payable :
            <span>₹<?= $grand_total; ?>/-</span>
        </p>
    </div>

    <p class="title">billing address</p>

    <p class="user"><i class="bx bxs-user"></i> <?= $fetch_order['name']; ?></p>
    <p class="user"><i class="bx bxs-phone"></i> <?= $fetch_order['number']; ?></p>
    <p class="user"><i class="bx bxs-envelope"></i> <?= $fetch_order['email']; ?></p>
    <p class="user"><i class="bx bxs-map"></i> <?= $fetch_order['address']; ?></p>

    <p class="status" style="color:
    <?php
        if($fetch_order['status'] == 'confirm'){ echo 'green'; }
        elseif($fetch_order['status'] == 'canceled'){ echo 'red'; }
        else { echo 'orange'; }
    ?>">
    <?= $fetch_order['status']; ?>
    </p>

    <?php if($fetch_order['status'] == 'canceled'){ ?>
        <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">order again</a>
    <?php }else{ ?>
        <form action="" method="post" class="flex-btn">
            <button type="submit" name="delete" class="btn"
            onclick="return confirm('cancel this order?');">
            cancel order
            </button>
            <a href="order.php" class="btn">go back</a>
        </form>
    <?php } ?>

</div>
</div>

<?php
    }else{
        echo '<p class="empty">product not found!</p>';
    }
}else{
    echo '<p class="empty">order not found!</p>';
}
?>

</div>
</div>

<?php include 'user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/user_script.js"></script>

</body>
</html>