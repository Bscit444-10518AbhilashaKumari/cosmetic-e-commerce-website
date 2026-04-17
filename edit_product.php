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

function clean($data){
   return htmlspecialchars(trim($data));
}

/* ---------- COMMON IMAGE FUNCTION ---------- */
function getImageData($file){
   $name = basename($file['name']);
   $tmp  = $file['tmp_name'];
   $folder = '../uploaded_files/'.$name;
   return [$name, $tmp, $folder];
}

/* ---------- SAVE PRODUCT ---------- */
if(isset($_POST['update'])){

    $product_id =$_POST['product_id'];

    $name = clean($_POST['title']);
    $price = clean($_POST['price']);
    $content = clean($_POST['content']);
    $category = clean($_POST['category']);
    $brand = clean($_POST['brand']);
    $stock = clean($_POST['stock']);

    $status = clean($_POST['status']);

    $update_product = $conn->prepare("UPATE products SET name = ?, price = ?, categorty = ?, brand = ?, product_detail = ?, stock = ?, status = ? WHERE id = ?");
    $update_product->execute([$name, $price, $category, $brand, $content, $stock, $status, $product_id]);
    $success_msg[] ='product updated successfully';


    /* images */
    list($thumb_one, $tmp1, $folder1) = getImageData($_FILES['thumb_one']);
    list($thumb_two, $tmp2, $folder2) = getImageData($_FILES['thumb_two']);
    list($thumb_three, $tmp3, $folder3) = getImageData($_FILES['thumb_three']);
    list($thumb_four, $tmp4, $folder4) = getImageData($_FILES['thumb_four']);

   $update_image = $conn->prepare("UPDATE products SET thumb_one = ?, thumb_two = ?, thumb_three = ?, thumb_four = ? WHERE id = ?");
   $update_image->execute([$thumb_one, $thumb_two, $thumb_three, $thumb_four]);

    /* MOVE FILES */
    move_uploaded_file($tmp1, $folder1);
    move_uploaded_file($tmp2, $folder2);
    move_uploaded_file($tmp3, $folder3);
    move_uploaded_file($tmp4, $folder4);

  $success_msg[] = 'product updated successfully';
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
      <h1>edit products</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">dashboard</a><i class="bx bx-right-arrow-alt"></i>edit products</span>
    </div>
</div>

<div style="margin: 3rem;" class="add_product">
    <div class="heading">
        <h1 style="font-size: 3rem;">edit products</h1>
        <img src="../images/separator.png">
    </div>
     
    <div class="container">
      <?php 
      $product_id = $_GET['id'];

      $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $select_product->execute([$product_id]);

      if($select_product->rowCount() > 0){
        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

     
      
      ?>
    


     <div style="padding-left: 300px; padding-top:50px; padding-bottom:50px;" class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>product name <span>*</span></p>
                        <input type="text" name="title" maxlength="100" value="<?= $fetch_product['name']; ?>"class="box">
                    </div>
                    <div class="input-field">
                        <p>product price <span>*</span></p>
                        <input type="number" name="price" maxlength="10" value="<?= $fetch_product['price']; ?>"class="box">
                    </div>
                    <div class="input-field">
                        <p>product stock <span>*</span></p>
                        <input type="number" name="stock" maxlength="10" value="<?= $fetch_product['stock']; ?>" min="0" max="99999999999" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product brand<span>*</span></p>
                        <input type="text" name="brand" maxlength="100" value="<?= $fetch_product['brand']; ?>" class="box">
                    </div>
                    <div class="input-field">
                       <p> product status <span>*</span></p>
                           <select name="status" class="box">
                           <option selected disabled value="<?= $fetch_product['status'] ?>" ><?= $fetch_product['status'] ?></option>
                           <option value="active">active</option>
                           <option value="deactive">deactive</option>
                           </select>
                              </div>
                    <div class="input-field">
                         <p>product category <span>*</span></p>
                            <input type="text" name="category" maxlength="100" value="<?= $fetch_product['category']; ?>" class="box">
                      </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>product thumb one <span>*</span></p>
                        <input type="file" name="thumb_one" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb two <span>*</span></p>
                        <input type="file" name="thumb_two" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb three <span>*</span></p>
                        <input type="file" name="thumb_three" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb four <span>*</span></p>
                        <input type="file" name="thumb_four" accept="image/*" required class="box">
                    </div>
                </div>
            </div>
            
            <div class="input-field">
             <p>product description <span>*</span></p>
             <textarea class="box" name="content">value="<?= $fetch_product['product_detail']; ?>"</textarea>
            </div>
            
            <div class="input-field">
                <div class="flex-box">
                     <img src="../images/products/<?= $fetch_product['thumb_one']; ?>">
                      <img src="../images/products/<?= $fetch_product['thumb_two']; ?>">
                       <img src="../images/products/<?= $fetch_product['thumb_three']; ?>">
                        <img src="../images/products/<?= $fetch_product['thumb_four']; ?>">
                </div>
            </div>
            <div class="flex-btn">
                <button type="submit" name="upadate" class="btn">update product</button>
                 <button type="submit" name="delete" onclick="return confirm('delete this product')" class="btn">delete</button>

                 <a href="view_product.php" class="btn">go back</a>
            </div>
        </form>
     </div>
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

