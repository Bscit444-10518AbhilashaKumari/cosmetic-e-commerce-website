<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

include '../components/add_to_cart.php';
include '../components/add_to_wishlist.php';



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
      <h1>search result</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>search result</span>
    </div>
</div>

<div class="products">
    <div class="heading">
        <h1>search products result</h1>
        <img src="../images/separator.png">
    </div>
    <div class="box-container">
        <?php 
        
        if(isset($_POST['search_product']) or isset($_POST['search_product_btn'])){
            $search_product = $_POST['search_product'];
            $select_product = $conn->prepare("SELECT * FROM products WHERE name LIKE '%{search_product}%' AND status = ?");
            $select_product->execute(['active']);

            if($select_product->rowCount() > 0){
                while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

             
            
            ?>
            <form action="" method="post" class="box <?php  if($fetch_products['stock'] == 0){ echo 'disabled';} ?>">
            <div class="icon">
                <div class="box-icon">

              
                <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img1" >
                 <img src="../images/products/<?= $fetch_products['thumb_two']; ?>" class="img2" >
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
                header('location:notfound.php');
            }
        } else{
            echo'
            <div class="empty">
            <p> no products added yet</p>
            </div>
            ';
        }
        ?>
    </div>
</div>





<?php include 'user_footer.php'; ?>

<!-- SweetAlert -->
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

<script src="../js/user_script.js"></script>
</body>
</html>
