<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';
$seller_id = $_SESSION['seller_id'] ?? '';

if($seller_id == ''){
   header('location:login.php');
   exit;
}

/* DELETE REVIEW */
if(isset($_POST['delete'])){
   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){

      $delete_review = $conn->prepare("DELETE FROM reviews WHERE id = ?");
      $delete_review->execute([$delete_id]);

      $success_msg[] = 'Review deleted successfully!';

   }else{
      $warning_msg[] = 'Review already deleted!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>User Reviews</title>
</head>

<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<div class="banner">
    <div class="detail">
      <h1>user' reviews</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">home</a><i class="bx bx-right-arrow-alt"></i>user' reviews</span>
    </div>
</div>


<div class="reviews-container">

<div class="heading">
<h1>User Reviews</h1>
<img src="../images/separator.png">
</div>

<div class="box-container">

<?php

$select_review = $conn->prepare("SELECT * FROM reviews");
$select_review->execute();

if($select_review->rowCount() > 0){

while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)){

?>

<div class="box">

<?php
$select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_user->execute([$fetch_review['user_id']]);

while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
?>

<div class="user">

<?php if($fetch_user['image'] != ''){ ?>

<img src="../uploaded_files/<?= $fetch_user['image']; ?>">

<?php }else{ ?>

<h3><?= substr($fetch_user['name'],0,1); ?></h3>

<?php } ?>

<div>
<p><?= $fetch_user['name']; ?></p>
<span><?= $fetch_review['date']; ?></span>
</div>

</div>

<?php } ?>

<div class="ratings">

<?php if($fetch_review['rating'] <= 2){ ?>

<p style="background:red;color:#fff;">
<i class="bx bxs-star"></i>
<?= $fetch_review['rating']; ?>
</p>

<?php }elseif($fetch_review['rating'] == 3){ ?>

<p style="background:orange;color:#fff;">
<i class="bx bxs-star"></i>
<?= $fetch_review['rating']; ?>
</p>

<?php }else{ ?>

<p style="background:var(--main-color);color:#fff;">
<i class="bx bxs-star"></i>
<?= $fetch_review['rating']; ?>
</p>

<?php } ?>

</div>

<h3 class="title">
<?= $fetch_review['title']; ?>
</h3>

<?php if($fetch_review['description'] != ''){ ?>

<p class="description">
<?= $fetch_review['description']; ?>
</p>

<?php } ?>

<form action="" method="post">

<input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">

<button type="submit" name="delete"
onclick="return confirm('delete this review?')"
class="btn">

delete review

</button>

</form>

</div>

<?php
}
}else{

echo '
<div class="empty">
<p>No review posted yet!</p>
</div>
';

}
?>

</div>
</div>


<?php include __DIR__ . '/../components/admin_footer.php'; ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>

<?php if(!empty($warning_msg)): ?>

swal({
title:"Oops!",
text:"<?= implode("\\n",$warning_msg); ?>",
icon:"error",
button:"Ok",
});

<?php elseif(!empty($success_msg)): ?>

swal({
title:"Success!",
text:"<?= implode("\\n",$success_msg); ?>",
icon:"success",
button:"Ok",
});

<?php endif; ?>

</script>

<script src="../js/admin_script.js"></script>

<?php include __DIR__ . '/../components/alert.php'; ?>

</body>
</html>






































