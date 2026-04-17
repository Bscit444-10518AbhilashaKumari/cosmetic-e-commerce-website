<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if ($user_id == '') {
    header('location:login.php');
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

<title>My Orders</title>
</head>

<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>my order</h1>
        <p>Track your orders and status here</p>
        <span>
            <a href="home.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>
            my order
        </span>
    </div>
</div>

<div class="orders">

    <div class="heading">
        <h1>my orders</h1>
        <img src="../images/separator.png">
    </div>

    <div class="box-container">

<?php
$select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$select_orders->execute([$user_id]);

if ($select_orders->rowCount() > 0) {

    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {

        $product_id = $fetch_orders['product_id'];

        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $select_products->execute([$product_id]);

        if ($select_products->rowCount() > 0) {

            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);

            // fallback image (safe)
            $img2 = !empty($fetch_products['thumb_three']) 
                ? $fetch_products['thumb_three'] 
                : $fetch_products['thumb_one'];
?>

<div class="box">

    <a href="view_order.php?get_id=<?= $fetch_orders['id']; ?>">

        <div class="icon-box">
            <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img1">
            
        </div>

    </a>

    <p class="date">
        <i class="bx bxs-calendar-alt"></i>
        <span><?= $fetch_orders['date']; ?></span>
    </p>

    <div class="content">

        <h3 class="name">
            <?= htmlspecialchars($fetch_products['name']); ?>
        </h3>

        <p class="price">
            ₹<?= number_format($fetch_products['price'], 2); ?>
        </p>

        <?php
        $status = $fetch_orders['status'];
        $color = "orange";

        if ($status == "confirm") $color = "green";
        elseif ($status == "canceled") $color = "red";
        ?>

        <p class="status" style="color:<?= $color; ?>">
            <?= ucfirst($status); ?>
        </p>

        <a href="rating.php?get_id=<?= $fetch_products['id']; ?>" class="btn">
            Give Rating
        </a>

    </div>

</div>

<?php
        }
    }

} else {
    echo '<p class="empty">No orders placed yet!</p>';
}
?>

    </div>
</div>

<?php include 'user_footer.php'; ?>

<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal("Oops!", "<?= implode('\\n', $warning_msg); ?>", "error");
<?php elseif(!empty($success_msg)): ?>
swal("Success!", "<?= implode('\\n', $success_msg); ?>", "success");
<?php endif; ?>
</script>

<script src="../js/user_script.js"></script>

</body>
</html>