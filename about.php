<?php
session_start();
include_once 'connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    include '../components/add_to_cart.php';
    include '../components/add_to_wishlist.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

    <title>Cosmika A Cosmetic Website Template</title>
</head>

<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>about us</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit...</p>
        <span>
            <a href="home.php">home</a>
            <i class="bx bx-right-arrow-alt"></i>
            about us
        </span>
    </div>
</div>

<div class="who">
  <div class="box-container">
    <div class="box">
      <div class="heading">
        <span style="color:red;">who we are</span>
        <h1>We are passionate about making beautiful more beautiful</h1>
      </div>

      <p>Maria is a Roman-born pastry chef...</p>

      <div class="flex-btn">
        <a href="shop.php" class="btn">explore more menu</a>
        <a href="shop.php" class="btn">visit our shop</a>
      </div>
    </div>

    <div class="img-box">
      <img src="../images/about0.jpg" class="img">
    </div>
  </div>
</div>

<!-- (ALL YOUR HTML STAYS SAME BELOW — no PHP errors anymore) -->

<?php include 'user_footer.php'; ?>

<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script type="text/javascript" src="../js/user_script.js"></script>

</body>
</html>