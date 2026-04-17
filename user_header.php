<?php

$user_id = $_SESSION['user_id'] ?? null;
?>

<header class="header">
    <section class="flex">

        <a href="home.php" class="logo">
            <img src="../images/logo0.svg" width="130px">
        </a>

        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="shop.php">shop</a>
            <a href="order.php">order</a>
            <a href="contact.php">contact us</a>
        </nav>

        <form action="search_product.php" method="post" class="search-form">
    <input type="text" name="search_product" placeholder="search product..." required maxlength="100">
    <button type="submit" name="search_product_btn">
        <i class="bx bx-search-alt-2"></i>
    </button>
</form>

<div class="icons">
    <div id="menu-btn"><i class="bx bx-list-plus"></i></div>
    <div id="search-btn"><i class="bx bx-search-alt-2"></i></div>
    <?php  
    $count_wishlist_item = $conn->prepare("select * from wishlist where user_id
    =?");
    $count_wishlist_item->execute([$user_id]);
    $total_wishlist_item = $count_wishlist_item->rowCount();
    ?>
    <a href="wishlist.php">
        <i class="bx bx-heart"></i><sup><?= $total_wishlist_item; ?></sup>
    </a>


    <?php  
    $count_cart_item = $conn->prepare("select * from cart where user_id
    =?");
    $count_cart_item->execute([$user_id]);
    $total_cart_item = $count_cart_item->rowCount();
    ?>
    <a href="cart.php">
        <i class="bx bx-cart"></i><sup><?= $total_cart_item;  ?></sup>
    </a>

    <div id="user-btn" class="bx bxs-user"></div>
</div>


<div class="profile">
<?php
// assume session_start() and connect.php already included
// $conn is your PDO connection

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {

    $stmt = $conn->prepare("SELECT name, image FROM users WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $fetch_profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fetch_profile) {

        // if image empty, show default
        $profile_img = !empty($fetch_profile['image'])
            ? 'uploaded_files/' . htmlspecialchars($fetch_profile['image'])
            : '../images/man.png';
        ?>
        
        <img src="<?= $profile_img; ?>" alt="User Image">
        <h3 style="margin-bottom: 1rem;">
            <?= htmlspecialchars($fetch_profile['name']); ?>
        </h3>

        <div class="flex-btn">
            <a href="profile.php" class="btn"> View Profile</a>
            <a href="../components/user_logout.php" class="btn">Logout</a>
        </div>

        <?php
    } else {
        // user_id exists but no record found
        showLoginRegister();
    }

} else {
    // user not logged in
    showLoginRegister();
}

/* reusable function */
function showLoginRegister() {
    ?>
    <img src="../images/man.png" alt="Guest">
    <h3 style="margin-bottom: 1rem;">Please login or register</h3>
    <div class="flex-btn">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    </div>
    <?php
}
?>

</div>






    </section>
</header>


