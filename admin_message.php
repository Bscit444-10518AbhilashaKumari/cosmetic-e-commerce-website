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

/* DELETE MESSAGE */
if(isset($_POST['delete'])){
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM messages WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){

        $delete_message = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $delete_message->execute([$delete_id]);

        $success_msg[] = 'Message deleted successfully';

    }else{
        $warning_msg[] = 'Message already deleted';
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
      <h1>user's message</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">home</a><i class="bx bx-right-arrow-alt"></i>user's message</span>
    </div>
</div>

<div class="gride">

<div class="heading">
<h1>User's Message</h1>
<img src="../images/seperator.png">
</div>

<form action="" method="post" class="search-form">

<input type="text" name="search_box" placeholder="Search message" maxlength="100">

<button type="submit" name="search_btn" class="bx bx-search-alt"></button>

</form>

<div class="box-container" id="box">

<?php

/* SEARCH MESSAGE */
if(isset($_POST['search_btn'])){

$search_box = htmlspecialchars($_POST['search_box']);

$select_message = $conn->prepare("SELECT * FROM messages WHERE name LIKE ? OR email LIKE ?");
$select_message->execute(["%$search_box%","%$search_box%"]);

}else{

$select_message = $conn->prepare("SELECT * FROM message");
$select_message->execute();

}

if($select_message->rowCount() > 0){

while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){

?>

<div class="box">

<p>name : <span><?= $fetch_message['name']; ?></span></p>

<p>email : <span><?= $fetch_message['email']; ?></span></p>

<p>subject : <span><?= $fetch_message['subject']; ?></span></p>

<p>message : <span><?= $fetch_message['message']; ?></span></p>

<form action="" method="post">

<input type="hidden" name="delete_id" value="<?= $fetch_message['id']; ?>">

<button type="submit" name="delete" onclick="return confirm('delete this message?')" class="btn">
delete message
</button>

</form>

</div>

<?php
}
}else{

echo '<div class="empty"><p>No messages found!</p></div>';

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
    



