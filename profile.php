<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];
$success_msg = [];

// check seller login
$seller_id = $_SESSION['seller_id'] ?? '';
if($seller_id == ''){
   header('location:login.php');
   exit;
}

/* ---------------- PROFILE ---------------- */
$select_profile = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
$select_profile->execute([$seller_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

/* ---------------- PRODUCTS COUNT ---------------- */
$select_product = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
$select_product->execute([$seller_id]);
$total_product = $select_product->rowCount();

/* ---------------- ORDERS COUNT ---------------- */
$select_order = $conn->prepare("SELECT * FROM `orders` WHERE seller_id = ?");
$select_order->execute([$seller_id]);
$total_order = $select_order->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>Seller Profile</title>
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>My Profile</h1>
        <span>
            <a href="dashboard.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>
            my profile
        </span>
    </div>
</div>

<div class="profile">

    <div class="heading">
        <h1>Seller Profile</h1>
        <img src="../images/separator.png" alt="">
    </div>

    <div class="details">

        <!-- USER CARD -->
        <div class="user">

            <?php if($fetch_profile){ ?>
                <img src="../uploaded_files/<?= $fetch_profile['image']; ?>">
                <h3><?= $fetch_profile['name']; ?></h3>
            <?php } ?>

            <p>Seller</p>
            <a href="update.php" class="btn">Update Profile</a>
        </div>

        <!-- STATS -->
        <div class="box-container">

            <div class="box">
                <div class="flex">
                    <i class="bx bxs-food-menu"></i>
                    <h3><?= $total_order; ?></h3>
                </div>
                <a href="../components/order.php" class="btn">View Orders</a>
            </div>

            <div class="box">
                <div class="flex">
                    <i class="bx bxs-package"></i>
                    <h3><?= $total_product; ?></h3>
                </div>
                <a href="view_product.php" class="btn">View Products</a>
            </div>

        </div>

    </div>
</div>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal({
    title: "Oops!",
    text: "<?= implode("\n", $warning_msg); ?>",
    icon: "error",
    button: "Ok",
});
<?php elseif(!empty($success_msg)): ?>
swal({
    title: "Success!",
    text: "<?= implode("\n", $success_msg); ?>",
    icon: "success",
    button: "Ok",
});
<?php endif; ?>
</script>

<script src="../js/admin_script.js"></script>
<?php include __DIR__ . '/../components/alert.php'; ?>

</body>
</html>