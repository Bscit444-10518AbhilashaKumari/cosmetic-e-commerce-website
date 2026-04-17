<?php
include_once 'connect.php';
$user_id = $_COOKIE['user_id'] ?? '';




include 'add_to_wishlist.php';
include 'add_to_cart.php';
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

    <!-- Slick CSS -->
    <link rel="stylesheet" href="../css/slick.css">
   

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

        <title>Cosmika | Popular Brands</title>

</head>
<body>

<div class="popular-brands">
    <h2>our popular brands</h2>
    <div class="controls">
        <i class="bi bi-chevron-left left"></i>
        <i class="bi bi-chevron-right right"></i>
     </div>

     <div class="popular-brands-content">
        <?php
        $select_products = $conn->prepare("SELECT * FROM products WHERE status = ?");
        $select_products->execute(['active']);

        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){

        ?>
        <form action="" method="post" class="box <?php  if($fetch_products['stock'] == 0){ echo 'disabled';} ?>">
            <div class="icon">
                <div class="box-icon">

              
                <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img1" >
                 
                  </div>
            </div>
            <?php if($fetch_products['stock'] > 9){ ?>
                <span class="stock" style="color: green;">in stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                  <span class="stock" style="color: red;">out of stock</span>
                <?php }else{?>
                  <span class="stock" style="color: red;">hurry only <?= $fetch_products['stock'] ;?>left</span>
            <?php } ?>
            <p class="price">$<?= $fetch_products['price'] ?>/-</p>
            <div class="content">
                <div class="button">
                    <div><h3><?= $fetch_products['name'] ?></h3></div>
               
                <button type="submit" name="add_to cart"><i class="bx bx-cart"></i></button>
                <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                <a href="view_page.php?get_id=<?= $fetch_products['id'] ?>" class="bx bxs-show"></a>
             </div>
             </div>
             <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
             ?>
             <div class="flex_btn">
                <a href="checkout.php?get_id=<?= $fetch_products['id'] ?>" class="btn">buy now</a>
                <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
            </div>
        </form>

        <?php
            }
        }else{
            echo'
            <div class="empty">
            <p> no products added yet</p>
            </div>
            ';
        }
        ?>
    </div>
</div>


<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<!-- jquery -->
<script src="../js/Jquery-3.2.1.min.js"></script>
<!-- slick -->
<script src="../js/slick.min.js"></script>

<!-- Main JS -->

<script>
     // Category slider
    $('.popular-brands-content').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: $('.left'),
        nextArrow: $('.right'),
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll:1, infinite:true } },
            { breakpoint: 768,  settings: { slidesToShow: 2, slidesToScroll:2 } },
            { breakpoint: 480,  settings: { slidesToShow: 1, slidesToScroll:1 } }
        ]
    });
</script>
<script>
<?php if(!empty($success_msg)): ?>
    swal("Success!", "<?= implode('\\n', $success_msg); ?>", "success");
<?php endif; ?>

<?php if(!empty($warning_msg)): ?>
    swal("Error!", "<?= implode('\\n', $warning_msg); ?>", "error");
<?php endif; ?>
</script>
</body>
</html>
