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
if(isset($_POST['delete'])){

    $delete_id = $_POST['delete_id'];

    // clean input
    $delete_id = htmlspecialchars(trim($delete_id));

    // check seller exists
    $verify_delete = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){

        $delete_seller = $conn->prepare("DELETE FROM sellers WHERE id = ?");
        $delete_seller->execute([$delete_id]);

        $success_msg[] = "Seller deleted successfully";

    }else{
        $warning_msg[] = "Seller already deleted or not found";
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
<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>Cosmika A Cosmetic Website Template</title>
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>
<div class="banner">
    <div class="detail">
      <h1>registered seller's</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">home</a><i class="bx bx-right-arrow-alt"></i>registered seller's</span>
    </div>
</div>

<div class="gride">

 <div class="box-container">
<div class="heading">
<h1 style="font-size:50px;padding-bottom:10px;">Registered Sellers</h1>
</div>

<form action="" method="post" class="search-form">
<input type="text" name="search_box" placeholder="Search sellers" maxlength="100">
<button type="submit" name="search_btn" class="bx bx-search-alt"></button>
</form>



<?php

/* SEARCH SELLER */
if(isset($_POST['search_btn'])){

$search_box = htmlspecialchars($_POST['search_box']);

$select_seller = $conn->prepare("SELECT * FROM sellers WHERE name LIKE ? OR email LIKE ?");
$select_seller->execute(["%$search_box%","%$search_box%"]);

}else{

$select_seller = $conn->prepare("SELECT * FROM sellers");
$select_seller->execute();

}

if($select_seller->rowCount() > 0){

while($fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC)){

?>

<div class="box">

<img src="../uploaded_files/<?= $fetch_seller['image']; ?>">

<p>name : <span><?= $fetch_seller['name']; ?></span></p>
<p>email : <span><?= $fetch_seller['email']; ?></span></p>

<?php if($fetch_seller['id'] == $seller_id){ ?>

<a href="update.php" class="btn">update account</a>

<?php }else{ ?>

<form action="" method="post">

<input type="hidden" name="delete_id" value="<?= $fetch_seller['id']; ?>">

<button type="submit" name="delete" onclick="return confirm('delete this account?')" class="btn">
delete user
</button>

</form>

<?php } ?>

</div>

<?php
}
}else{

echo '<div class="empty"><p>No sellers registered yet!</p></div>';

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

</body>
</html>


