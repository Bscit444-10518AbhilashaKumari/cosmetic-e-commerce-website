<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];
$success_msg = [];

/* UNIQUE ID FUNCTION (safe fix) */
function unique_id() {
    return uniqid('seller_', true);
}

if(isset($_POST['register'])){

    $id = unique_id();

    $name  = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));

    // ❌ PLAIN PASSWORD (as you requested)
    $pass   = $_POST['pass'];
    $c_pass = $_POST['c_pass'];

    /* PASSWORD CHECK */
    if($pass !== $c_pass){
        $warning_msg[] = "Confirm password not match!";
    }

    /* EMAIL CHECK */
    $check = $conn->prepare("SELECT id FROM sellers WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $warning_msg[] = "Email already exists!";
    }

    /* IMAGE UPLOAD */
    $rename = '';
    if(!empty($_FILES['image']['name'])){

        $image_name = $_FILES['image']['name'];
        $image_tmp  = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if(!in_array($ext, $allowed)){
            $warning_msg[] = "Invalid image format";
        } elseif($image_size > 2000000){
            $warning_msg[] = "Image size too large (max 2MB)";
        } else {

            $rename = unique_id().'.'.$ext;

            $upload_dir = __DIR__ . '/../uploaded_files/';
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            move_uploaded_file($image_tmp, $upload_dir.$rename);
        }
    }

    /* INSERT DATA */
    if(empty($warning_msg)){

        $insert = $conn->prepare("
            INSERT INTO sellers (id, name, email, password, image)
            VALUES (?, ?, ?, ?, ?)
        ");

        $insert->execute([$id, $name, $email, $pass, $rename]);

        $success_msg[] = "Registration successful!";
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

<title>Register | Cosmika</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>




<div class="form-container form" style="margin: 20px 300px;">
    <form action="" method="post" enctype="multipart/form-data" class="register">
        <h3>Register Now</h3>
    <div class="flex">
    <div class="col">
        <div class="input-field">
            <p>Your name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
        </div>

        <div class="input-field">
            <p>Your email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="100" required class="box">
        </div>
       </div>
       <div class="col">
        <div class="input-field">
            <p>Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter your password"  maxlength="50" required class="box">
        </div>

        <div class="input-field">
            <p>Confirm Password <span>*</span></p>
            <input type="password" name="c_pass" placeholder="Confirm password" maxlength="50" required class="box">
        </div>
     </div>
 </div>
        <div class="input-field">
            <p>Profile Image <span>*</span></p>
            <input type="file" name="image" accept="image/*" class="box">
        </div>
 
        <p class="link">Already have an account? <a href="login.php">Login now</a></p>
        <button type="submit" name="register" class="btn">Register</button>
    </form>
</div>



<script>
<?php if(!empty($warning_msg)): ?>
    swal("Oops!", "<?= implode(', ', $warning_msg); ?>", "error");
<?php elseif(!empty($success_msg)): ?>
    swal("Success!", "<?= implode(', ', $success_msg); ?>", "success").then(() => {
        window.location.href = 'login.php';
    });
<?php endif; ?>
</script>

<script src="../js/admin_script.js"></script>
<?php include __DIR__ . '/../alert.php'; ?>
</body>
</html>