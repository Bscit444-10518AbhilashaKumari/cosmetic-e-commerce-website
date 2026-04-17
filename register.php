<?php
session_start();
include 'connect.php';

$warning_msg = [];
$success_msg = [];

if(isset($_POST['register'])){

    $id = unique_id();

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

    // ❌ PLAIN PASSWORD (NO HASH)
    $pass = $_POST['pass'];
    $c_pass = $_POST['c_pass'];

    // check confirm password (FIXED)
    if($pass !== $c_pass){
        $warning_msg[] = 'Confirm password not match!';
    }

    // check email exists
    $select_users = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $select_users->execute([$email]);

    if($select_users->rowCount() > 0){
        $warning_msg[] = 'Email already exists!';
    }

    // image upload
    $rename = '';
    if(!empty($_FILES['image']['name'])){

        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if(!in_array($ext, $allowed)){
            $warning_msg[] = 'Invalid image format';
        }elseif($image_size > 2000000){
            $warning_msg[] = 'Image size too large';
        }else{
            $rename = unique_id().'.'.$ext;
            $upload_dir = __DIR__ . '/../uploaded_files/';
            $image_folder = $upload_dir.$rename;

            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            move_uploaded_file($image_tmp, $image_folder);
        }
    }

    // insert user
    if(empty($warning_msg)){
        $insert_users = $conn->prepare(
            "INSERT INTO users (id, name, email, password, image) 
             VALUES (?, ?, ?, ?, ?)"
        );

        $insert_users->execute([$id, $name, $email, $pass, $rename]);

        $success_msg[] = 'New user registered! Please login now.';
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

<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

<title>Register | Cosmika</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>

<?php include 'user_header.php'; ?>

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
                    <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
                </div>

                <div class="input-field">
                    <p>Confirm Password <span>*</span></p>
                    <input type="password" name="c_pass" placeholder="Confirm password" maxlength="50" required class="box">
                </div>

            </div>

        </div>

        <div class="input-field">
            <p>Profile Image</p>
            <input type="file" name="image" accept="image/*" class="box">
        </div>

        <p class="link">Already have an account? <a href="login.php">Login now</a></p>

        <button type="submit" name="register" class="btn">Register</button>

    </form>
</div>

<?php include 'user_footer.php'; ?>

<script>
<?php if(!empty($warning_msg)): ?>
    swal("Oops!", "<?= implode(', ', $warning_msg); ?>", "error");
<?php elseif(!empty($success_msg)): ?>
    swal("Success!", "<?= implode(', ', $success_msg); ?>", "success").then(() => {
        window.location.href = 'login.php';
    });
<?php endif; ?>
</script>

<script src="../js/user_script.js"></script>

</body>
</html>