<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];
$success_msg = [];

$seller_id = $_SESSION['seller_id'] ?? '';
if($seller_id == ''){
   header('location:login.php');
   exit;
}

/* =========================
   SELLER PROFILE FIX
========================= */
$seller_profile = [];

$stmt = $conn->prepare("SELECT name FROM sellers WHERE id = ?");
$stmt->execute([$seller_id]);
$seller_profile = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>Dashboard</title>
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<div class="banner">
    <div class="detail">
      <h1>dashboard</h1>
      <span><a href="dashboard.php">admin</a> <i class="bx bx-right-arrow-alt"></i> dashboard</span>
    </div>
</div>

<div class="dashboard">

    <div class="heading">
        <h1 style="padding-left:500px;">dashboard</h1>
        
    </div>

    <!-- PROFILE BOX -->
    <div class="box-container">
        <h3>welcome !</h3>
        <p><?= $seller_profile['name'] ?? 'Seller' ?></p>
        <a href="update.php" class="btn">update profile</a>
    </div>

    <!-- PENDING -->
    <div class="box">
        <?php 
        $total_pendings = 0;

        $stmt = $conn->prepare("SELECT price FROM orders WHERE payment_status = ? AND seller_id = ?");
        $stmt->execute(['pending', $seller_id]);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $row['price'];
        }
        ?>
        <h3>$ <?= $total_pendings; ?>/-</h3>
        <p>total pending</p>
        <a href="admin_order.php" class="btn">see orders</a>
    </div>

    <!-- COMPLETED -->
    <div class="box">
        <?php 
        $total_confirm = 0;

        $stmt = $conn->prepare("SELECT price FROM orders WHERE payment_status = ? AND seller_id = ?");
        $stmt->execute(['completed', $seller_id]);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total_confirm += $row['price'];
        }
        ?>
        <h3>$ <?= $total_confirm; ?>/-</h3>
        <p>total confirm</p>
        <a href="admin_order.php" class="btn">see orders</a>
    </div>

    <!-- MESSAGES -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM message");
        $stmt->execute();
        $total_message = $stmt->fetchColumn();
        ?>
        <h3><?= $total_message; ?></h3>
        <p>total message</p>
        <a href="admin_message.php" class="btn">see message</a>
    </div>

    <!-- SELLERS -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM sellers");
        $stmt->execute();
        $total_sellers = $stmt->fetchColumn();
        ?>
        <h3><?= $total_sellers; ?></h3>
        <p>total sellers</p>
        <a href="sellers.php" class="btn">registered sellers</a>
    </div>

    <!-- USERS -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        $total_users = $stmt->fetchColumn();
        ?>
        <h3><?= $total_users; ?></h3>
        <p>total users</p>
        <a href="user_account.php" class="btn">registered users</a>
    </div>

    <!-- PRODUCTS -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products");
        $stmt->execute();
        $total_products = $stmt->fetchColumn();
        ?>
        <h3><?= $total_products; ?></h3>
        <p>products added</p>
        <a href="add_product.php" class="btn">add new products</a>
    </div>

    <!-- ACTIVE PRODUCTS -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND status = ?");
        $stmt->execute([$seller_id, 'active']);
        $total_active_products = $stmt->fetchColumn();
        ?>
        <h3><?= $total_active_products; ?></h3>
        <p>active products</p>
        <a href="add_product.php" class="btn">active products</a>
    </div>

    <!-- DEACTIVE -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ? AND status = ?");
        $stmt->execute([$seller_id, 'deactive']);
        $total_deactive_products = $stmt->fetchColumn();
        ?>
        <h3><?= $total_deactive_products; ?></h3>
        <p>deactive products</p>
        <a href="add_product.php" class="btn">deactive products</a>
    </div>

    <!-- REVIEWS FIXED -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM reviews");
        $stmt->execute();
        $total_review = $stmt->fetchColumn();
        ?>
        <h3><?= $total_review; ?></h3>
        <p>total review</p>
        <a href="comments.php" class="btn">view reviews</a>
    </div>

    <!-- ORDERS -->
    <div class="box">
        <?php 
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE seller_id = ?");
        $stmt->execute([$seller_id]);
        $total_orders = $stmt->fetchColumn();
        ?>
        <h3><?= $total_orders; ?></h3>
        <p>total order</p>
        <a href="admin_order.php" class="btn">view orders</a>
    </div>

</div>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal({
    title: "Oops!",
    text: "<?= implode("\\n", $warning_msg); ?>",
    icon: "error",
    button: "Ok",
});
<?php elseif(!empty($success_msg)): ?>
swal({
    title: "Success!",
    text: "<?= implode("\\n", $success_msg); ?>",
    icon: "success",
    button: "Ok",
});
<?php endif; ?>
</script>

<script src="../js/admin_script.js"></script>

<?php include __DIR__ . '/../components/alert.php'; ?>

</body>
</html>