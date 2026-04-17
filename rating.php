<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    header('location:order.php');
    exit;
}

if(isset($_POST['add_review'])){

    if($user_id != ''){

        $id = unique_id();

        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        $rating = (int)$_POST['ratings'];

        /* ================= IMAGE UPLOAD FIXED ================= */

        $image_name = $_FILES['image']['name'] ?? '';
        $rename = '';

        if(!empty($image_name)){

            $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg','jpeg','png','webp'];

            if(!in_array($ext, $allowed_ext)){
                $warning_msg[] = 'Invalid image format';
            }else{

                $rename = unique_id() . '.' . $ext;

                $image_tmp_name = $_FILES['image']['tmp_name'];

                $upload_dir = '../images/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_folder = $upload_dir . $rename;

                move_uploaded_file($image_tmp_name, $image_folder);
            }
        }

        /* ================= CHECK DUPLICATE REVIEW ================= */

        if(empty($warning_msg)){

            $verify_ratings = $conn->prepare(
                "SELECT * FROM reviews WHERE product_id = ? AND user_id = ?"
            );
            $verify_ratings->execute([$get_id, $user_id]);

            if($verify_ratings->rowCount() > 0){
                $warning_msg[] = 'You already posted a review';
            }else{

                $add_review = $conn->prepare(
                    "INSERT INTO reviews(id, product_id, user_id, rating, title, photo, description)
                     VALUES(?, ?, ?, ?, ?, ?, ?)"
                );

                if($add_review->execute([
                    $id,
                    $get_id,
                    $user_id,
                    $rating,
                    $title,
                    $rename,
                    $description
                ])){
                    $success_msg[] = 'Review added successfully';
                }else{
                    $warning_msg[] = 'Failed to add review';
                }
            }
        }

    }else{
        $warning_msg[] = 'Please login first';
    }
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
      <h1>post review</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>post review</span>
    </div>
    </div>
    <div class="review" style="padding: 5% 0;">
        <div class="heading">
            <h1>post your review</h1>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Libero esse a ullam corporis ipsam id nobis <br>
                inventore qui odit ad ipsum aliquam sequi eum, nesciunt, sapiente tempore placeat excepturi ratione!</p>
        </div>
        <?php 
         $select_products =$conn->prepare("SELECT * FROM products WHERE id = ?");
         $select_products->execute([$get_id]);

         if($select_products->rowCount() > 0){
           while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){

          
        ?>
        <div class="img-box">
            <img src="../images/products/<?= $fetch_products['thumb_one']; ?>" width="100px">
            <h3><?=  $fetch_products['name']; ?></h3>
        </div>
        <?php 
           } 
         }
        
        ?>
        <div style="padding-left:360px; padding-top:50px;" class="form-container">
            <form action="" method="post" enctype="multipart/form-data" class="login">
               <div class="col" style="display: flex;">
                <div class="input-field">
                    <p>title <span>*</span></p>
                     <input type="text" name="title" placeholder="enter title" required class="box">
                </div>
                <div class="input-field">
                    <p>upload image <span>*</span></p>
                     <input type="file" name="image" accept="image/*" required class="box">
                </div>
               </div>
               
               <div class="input-field">
                    <p>give rating <span>*</span></p>
                    <select name="ratings" class="box">
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                     <option value="5">5</option>
                     </select>
                </div>
                 <div class="input-field">
                    <p>description <span>*</span></p>
                    <textarea name="description" class="box" rows="6" cols="30" ></textarea>
                </div>
                <button type="submit" name="add_review" class="btn">post your review</button>
            </form>
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