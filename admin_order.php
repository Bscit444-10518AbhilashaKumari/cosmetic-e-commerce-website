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

// UPDATE PAYMENT STATUS
if(isset($_POST['update'])){

   $order_id = $_POST['delete_id'] ?? '';
   $payment_status = $_POST['update_payment'] ?? '';

   if(!empty($order_id) && !empty($payment_status)){

      $order_id = trim($order_id);
      $payment_status = trim($payment_status);

      $update_payment = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
      $update_payment->execute([$payment_status, $order_id]);

      $success_msg[] = 'Payment status updated successfully!';

   }else{
      $warning_msg[] = 'Something went wrong while updating!';
   }
}


// DELETE ORDER
if(isset($_POST['delete'])){

   $delete_id = $_POST['delete_id'] ?? '';

   if(!empty($delete_id)){

      $delete_id = trim($delete_id);

      $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
      $delete_order->execute([$delete_id]);

      $success_msg[] = 'Order deleted successfully!';

   }else{
      $warning_msg[] = 'Order not found!';
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
      <h1>total order's placed</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="dashboard.php">home</a><i class="bx bx-right-arrow-alt"></i>total order's placed</span>
    </div>
</div>

<div class="order-container">
    <div class="heading">
        <h1>total order's placed</h1>
        <img src="../images/separator.png">
    </div>
    <form action="" method="post"  class="search-form">
  <input type="text" name="search_box" placeholder="search_user's" maxlength="100" required>
    <button type="submit" name="search_btn" class="bx bx-search-alt"></button> 
</form>
      <div class="box-container">
        <?php 

          if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
        $search_box = $_POST['search_box'];
        $search_box = htmlspecialchars($_POST['search_box']);

        $select_order = $conn->prepare("SELECT * FROM orders WHERE name LIKE '%{$search_box}%' OR email LIKE '%{$search_box}%'");
        $select_order->execute([$seller_id]);

    }else{
        $select_order = $conn->prepare("SELECT * FROM orders WHERE seller_id = ?");
        $select_order->execute([$seller_id]);

    }
    if($select_order->rowCount() > 0){
        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){

     ?>
      <div class="box">
        <div class="status" style="color:  <?php if($fetch_order['status'] == 'in progress') {echo 'limegreen';}else{echo 'red';} ?>;">
            <?=  $fetch_order['status'] ;?>
        </div>
        <div class="detail">
            <p>user name : <span><?= $fetch_order['name']; ?></span></p>
            <p>user id : <span><?= $fetch_order['user_id']; ?></span></p>
            <p>placed on : <span><?= $fetch_order['date']; ?></span></p>
            <p>user number : <span><?= $fetch_order['number']; ?></span></p>
            <p>user email : <span><?= $fetch_order['email']; ?></span></p>
            <p>total price : <span>$<?= $fetch_order['price']; ?>/-</span></p>
            <p>payment method : <span><?= $fetch_order['method']; ?></span></p>
            <p>address : <span><?= $fetch_order['address']; ?></span></p>
        </div>
        <form action="" method="post">
        <input type="hidden" name="delete_id" value="<?= $fetch_order['id']; ?>">

         <select name="update_payment" class="box" style="width: 90%;">
            <option selected disabled><?= $fetch_order['payment_status'] ?></option>
            <option value="pending">pending</option>
            <option value="completed">complete</option>
         </select>

        <div class="flex-btn">
         <button type="submit" name="update" class="btn">update payment</button>
         <button type="submit" name="delete" onclick="return confirm('delete this order')" class="btn">delete order</button>
        </div>
      </form>
      </div>
    <?php 
        }
     }elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
        header('location:notfound.php');
     }else{
            echo'
         <div class="empty">
          <p>no order take placed yet! </p>
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
