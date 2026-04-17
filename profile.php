<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if($user_id == ''){
    header('location:login.php');
    exit;
}

/* ✅ FETCH USER PROFILE (IMPORTANT FIX) */
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

/* ✅ ORDER COUNT */
$select_order = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$select_order->execute([$user_id]);
$total_order = $select_order->rowCount();

/* ❌ FIXED TYPO: FRPM → FROM */
$select_message = $conn->prepare("SELECT * FROM message WHERE user_id = ?");
$select_message->execute([$user_id]);
$total_message = $select_message->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

<title>Cosmika Profile</title>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>profile</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>profile</span>
    </div>
</div>

<div class="profile user-form">
    <div class="heading">
        <h1>profile details</h1>
    </div>

    <div class="details">

        <div class="user">

            <!-- ✅ SAFE IMAGE FETCH -->
           <?php
$image_path = "../images/default.png";

if(!empty($fetch_profile['image'])){
    $file = "../uploaded_files/" . $fetch_profile['image'];

    if(file_exists($file)){
        $image_path = $file;
    }
}
?>

<img src="<?= $image_path ?>" alt="Profile Image">

            <!-- ❌ FIX QUOTE BUG -->
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>

            <p>user</p>

            <a href="update.php" class="btn">update profile</a>
        </div>

        <div class="box-container">

            <div class="box">
                <div class="flex">
                    <i class="bx bxs-food-menu"></i>
                    <h3><?= $total_order; ?></h3>
                </div>
                <a href="order.php" class="btn">view orders</a>
            </div>

            <div class="box">
                <div class="flex">
                    <i class="bx bxs-chat"></i>
                    <h3><?= $total_message; ?></h3>
                </div>
                <a href="contact.php" class="btn">send message</a>
            </div>

        </div>

    </div>
</div>

<?php include 'user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
    swal("Oops!", "<?= implode('\\n', $warning_msg); ?>", "error");
<?php elseif(!empty($success_msg)): ?>
    swal("Success!", "<?= implode('\\n', $success_msg); ?>", "success");
<?php endif; ?>
</script>

<script src="../js/user_script.js"></script>

</body>
</html>