<header>

<?php
// SAFE seller_id check
$seller_id = $_SESSION['seller_id'] ?? '';

$fetch_profile = null;

if($seller_id != ''){

    $select_profile = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
    $select_profile->execute([$seller_id]);

    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="logo">
    <img src="../images/logo0.svg" width="100">
</div>

<div class="right">
    <div class="bx bxs-user" id="user-btn"></div>
    <div class="toggle-btn"><i class="bx bx-menu"></i></div>
</div>

<!-- PROFILE DROPDOWN -->
<div class="profile-detail">

    <?php if($fetch_profile){ ?>

        <div class="profile">
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img">
            <p><?= $fetch_profile['name']; ?></p>
        </div>

        <div class="flex-btn">
            <a href="profile.php" class="btn">Profile</a>
            <a href="../components/admin_logout.php"
               onclick="return confirm('Logout from this website?')">
               Logout
            </a>
        </div>

    <?php }else{ ?>

        <p>No profile found</p>

    <?php } ?>

</div>

</header>

<!-- SIDEBAR -->
<div class="sidebar">

<?php if($fetch_profile){ ?>
    <div class="profile">
        <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" class="logo-img">
        <p><?= $fetch_profile['name']; ?></p>
    </div>
<?php } ?>

<h5>Menu</h5>

<div class="navbar">
    <ul>
        <li><a href="dashboard.php"><i class="bx bxs-home-smile"></i>Home</a></li>
        <li><a href="add_product.php"><i class="bx bxs-shopping-bags"></i>Add Product</a></li>
        <li><a href="view_product.php"><i class="bx bxs-food-menu"></i>View Product</a></li>
        <li><a href="user_account.php"><i class="bx bxs-user-detail"></i>Accounts</a></li>
        <li><a href="../components/admin_logout.php"
               onclick="return confirm('Logout from this website');">
               <i class="bx bxs-log-out"></i>Log out
        </a></li>
    </ul>
</div>

<h5>Find Us</h5>
<div class="social-link">
    <i class="bx bxl-facebook"></i>
    <i class="bx bxl-instagram"></i>
    <i class="bx bxl-linkedin"></i>
    <i class="bx bxl-twitter"></i>
    <i class="bx bxl-pinterest-alt"></i>
</div>

</div>