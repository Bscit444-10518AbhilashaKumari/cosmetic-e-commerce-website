<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$user_id = $_SESSION['user_id'] ?? '';

$get_id = $_GET['get_id'] ?? '';

if($get_id == ''){
    echo "Invalid product ID";
    exit;
}

include '../components/add_to_cart.php';
include '../components/add_to_wishlist.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

<title>View Product</title>
</head>

<body>

<?php include 'user_header.php'; ?>


<div class="banner">
    <div class="detail">
      <h1>products</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>view-products</span>
    </div>
</div>
<div class="view_page">

<?php
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$get_id]);

if($stmt->rowCount() > 0){

    $fetch_product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form action="" method="post" class="box">

    <div class="thumb">

        <div class="big-image">
            <img src="../images/products/<?= $fetch_product['thumb_one']; ?>">
        </div>

        <div class="small-image">
            <img src="../images/products/<?= $fetch_product['thumb_two']; ?>">
            <img src="../images/products/<?= $fetch_product['thumb_three']; ?>">
            <img src="../images/products/<?= $fetch_product['thumb_four']; ?>">
            <img src="../images/products/<?= $fetch_product['thumb_one']; ?>">
        </div>

    </div>

    <div class="detail">

        <?php if($fetch_product['stock'] > 9){ ?>
            <span class="stock" style="color:green;">In Stock</span>
        <?php } elseif($fetch_product['stock'] > 0){ ?>
            <span class="stock" style="color:orange;">
                Hurry only <?= $fetch_product['stock'] ?>
            </span>
        <?php } else { ?>
            <span class="stock" style="color:red;">Out of Stock</span>
        <?php } ?>

        <p class="price">₹<?= $fetch_product['price']; ?></p>

        <h3 class="name"><?= $fetch_product['name']; ?></h3>

        <p class="product-detail">
            <?= $fetch_product['product_detail']; ?>
        </p>

        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">

        <input type="number" name="qty" value="1" min="1" max="99">

        <button type="submit" name="add_to_cart" class="btn">
            <i class="bx bx-cart"></i> Add to Cart
        </button>

        <button type="submit" name="add_to_wishlist" class="btn">
            <i class="bx bx-heart"></i> Add to Wishlist
        </button>

    </div>

</form>

<?php
} else {
    echo "<div class='empty'><p>Product not found</p></div>";
}
?>

</div>

<?php include 'user_footer.php'; ?>

<script src="../js/user_script.js"></script>

</body>
</html>