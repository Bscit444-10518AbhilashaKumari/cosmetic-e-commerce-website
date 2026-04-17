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

// DELETE PRODUCT
if(isset($_POST['delete'])){

    $p_id = trim($_POST['product_id']);

    $verify_delete = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $verify_delete->execute([$p_id]);

    if($verify_delete->rowCount() > 0){

        $select_images = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $select_images->execute([$p_id]);

        while($fetch_images = $select_images->fetch(PDO::FETCH_ASSOC)){

            $image_01 = $fetch_images['thumb_one'];
            $image_02 = $fetch_images['thumb_two'];
            $image_03 = $fetch_images['thumb_three'];
            $image_04 = $fetch_images['thumb_four'];

            if(!empty($image_01) && file_exists('../uploaded_files/'.$image_01)){
                unlink('../uploaded_files/'.$image_01);
            }

            if(!empty($image_02) && file_exists('../uploaded_files/'.$image_02)){
                unlink('../uploaded_files/'.$image_02);
            }

            if(!empty($image_03) && file_exists('../uploaded_files/'.$image_03)){
                unlink('../uploaded_files/'.$image_03);
            }

            if(!empty($image_04) && file_exists('../uploaded_files/'.$image_04)){
                unlink('../uploaded_files/'.$image_04);
            }
        }

        // DELETE PRODUCT
        $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_product->execute([$p_id]);

        // DELETE REVIEWS
        $delete_review = $conn->prepare("DELETE FROM reviews WHERE product_id = ?");
        $delete_review->execute([$p_id]);

        $success_msg[] = 'Product deleted successfully';

    }else{
        $warning_msg[] = 'Product already deleted';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head> 
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
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
                <h1>view product</h1> 
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br> 
                modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
                 vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p> 
                 <span><a href="dashboard.php">admin</a><i class="bx bx-right-arrow-alt"></i>view product</span> 
            </div>
             </div>
             <div class="show_product"> 
                <div class="heading"> 
                    <h1 style="font-size: 50px;">all products</h1> 
                    <img src="../images/separator.png"> </div> 
                    <form action="" method="post" class="search-form">
                        <input type="text" name="search_box" placeholder="search product.." maxlength="100" required> 
                        <button type="submit" name="search_btn" class="bx bx-search-alt"></button> </form> 
                        <div class="box-container">
                           <?php 
if(isset($_POST['search_box']) || isset($_POST['search_btn'])){

    $search_box = trim($_POST['search_box']);
    $search_box = htmlspecialchars($search_box);

    $select_products = $conn->prepare("SELECT * FROM products WHERE name LIKE ? AND seller_id = ?");
    $select_products->execute(["%$search_box%", $seller_id]);

}else{

    $select_products = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
    $select_products->execute([$seller_id]);
}

if($select_products->rowCount() > 0){
    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
?>
         
         <form action="" method="post" class="box"> 
            <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>"> 
            <div class="icon"> 
             <div class="icon-box">
                <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img1">
                <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" class="img2" >
             </div>
            </div>
            <div class="status" style="color: <?php if($fetch_products['status'] == 'active'){echo"limegreen";}else{echo"red";} ?>;"><?= $fetch_products['status']; ?></div>
            <p class="price"><?= $fetch_products['price']; ?></p>
            <div class="content">
                <div class="title"><?= $fetch_products['name']; ?></div>
                <div class="flex-btn"> 
                    <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">edit</a>
                       <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product')">delete</button>
                       <a href="read_product.php?post_id=<?= $fetch_products['id']; ?>" class="btn">read</a>
                       </div> 
                    </div>
                    </form> <?php } 
                    } else{ echo' <div class="empty">
                     <p>no products added yet! <br> 
                     <a href="add_products.php" class="btn">add product</a> </p> </div>'; } ?>
                 </div> 
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
<script src="../js/admin_script.js"></script>
<?php include __DIR__ . '/../components/alert.php'; ?>
</body>
</html>