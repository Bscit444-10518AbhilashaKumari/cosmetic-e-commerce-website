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

$get_id = $_GET['post_id'] ?? '';

/* DELETE PRODUCT */
if(isset($_POST['delete'])){

    $p_id = trim($_POST['product_id']);

    /* GET PRODUCT IMAGE */
    $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $select_product->execute([$p_id]);

    if($select_product->rowCount() > 0){

        $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

        /* DELETE IMAGE */
        if(!empty($fetch_product['thumb_one']) && file_exists('../uploaded_files/'.$fetch_product['thumb_one'])){
            unlink('../uploaded_files/'.$fetch_product['thumb_one']);
        }

        /* DELETE PRODUCT */
        $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_product->execute([$p_id]);

    }

    header('location:view_product.php');
    exit;
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
<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>Cosmika A Cosmetic Website Template</title>
</head>
<body>


<?php include __DIR__ . '/../components/admin_header.php'; ?>

<div class="banner">
    <div class="detail">
      <h1>read product</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">dashboard</a><i class="bx bx-right-arrow-alt"></i>read product</span>
    </div>
</div>

<div style="margin: 3rem;" class="read_product">
    <div class="heading">
        <h1 style="font-size: 3rem;">read product</h1>
        <img src="../images/separator.png">
    </div>
    <div class="container">
        <?php 
        $select_product =$conn->prepare("SELECT * FROM products WHERE id = ?");
        $select_product->execute([$get_id]);
        if($select_product->rowCount() > 0){
            while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

          
        ?>
        <form action="" method="post" class="box">
            <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">

            <div class="status" style="color : <?php if($fetch_product['status'] == 'active'){echo "limegreen";}else{echo "red";} ?>"><?= $fetch_product['staus']; ?>

            </div>
            <div class="big-image">
                <img src="../images/products/<?= $fetch_product['thumb_one']; ?>">
            </div>
            <div class="small-images">
                <img src="../images/products/<?= $fetch_product['thumb_one']; ?>">
                <img src="../images/products/<?= $fetch_product['thumb_two']; ?>">
                <img src="../images/products/<?= $fetch_product['thumb_three']; ?>">
                <img src="../images/products/<?= $fetch_product['thumb_four']; ?>">
            </div>
            </div>
            <div class="price">$<?=  $fetch_product['price']; ?>/-</div>
            <div class="title"><?=  $fetch_product['name']; ?></div>
            <div class="content"><?=  $fetch_product['product_detail']; ?></div>
            <div class="flex-btn">
                <a href="edit_product.php?id<?=  $fetch_product['id']; ?>" class="btn">edit product</a>
                <button type="submit" name="delete" onclick="return confirm('delete this product')" class="btn">delete</button>
                <a href="view_product.php?post_id=<?= $fetch_product['id']; ?>" class="btn">go back</a>
            </div>
        </form>
        <?php 
          }
        } else{
            echo'
         <div class="empty">
          <p>no products added yet! <br> <a href="add_products.php" class="btn">add product</a> </p>
          </div>';
        }
        ?>
    
 







</div>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>


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

<script>
    <?php include '../js/admin_script.js'?>
</script>

<?php include __DIR__ . '/../components/alert.php'; ?>


</body>
</html>

