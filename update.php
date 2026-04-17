<?php
session_start();
include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if($user_id == ''){
    header('location:login.php');
    exit;
}

/* FETCH PROFILE FOR DISPLAY */
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC) ?: [];

if (isset($_POST['update'])) {

   $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_user['password'];
   $prev_img = $fetch_user['image'];

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

   /* UPDATE NAME */
   if(!empty($name)){
        $update_name = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $update_name->execute([$name, $user_id]);
        $success_msg[] ='name updated successfully';
   }

   /* UPDATE EMAIL */
   if(!empty($email)){
        $select_email = $conn->prepare("SELECT email FROM users WHERE email = ? AND id != ?");
        $select_email->execute([$email, $user_id]);

        if($select_email->rowCount() > 0){
           $warning_msg[] ='email already exist';
        }else{
            $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id ]);
            $success_msg[] ='email updated successfully';
        }
   }

   /* UPDATE IMAGE */
   if(!empty($_FILES['image']['name'])){
        $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];

        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;

        $image_folder = '../uploaded_files/'.$rename;

        if(!is_dir('../uploaded_files')){
            mkdir('../uploaded_files', 0777, true);
        }

        if($image_size > 2000000){
            $warning_msg[] ='image size is too large';
        }else{
            $update_image = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $user_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] ='image updated successfully';
        }
   }

   /* UPDATE PASSWORD */
   $old_pass = $_POST['old_pass'] ?? '';
   $new_pass = $_POST['new_pass'] ?? '';
   $c_pass = $_POST['c_pass'] ?? '';

   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

   if(!empty($old_pass) || !empty($new_pass) || !empty($c_pass)){
        if($old_pass != $prev_pass){
            $warning_msg[] = 'old password not matched';
        }elseif($new_pass != $c_pass){
            $warning_msg[] = 'confirm password not matched';
        }else{
            $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $user_id ]);
            $success_msg[] ='password updated successfully';
        }
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

<title>Update Profile | Cosmika</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>update profile</h1>
        <p>Update your account information.</p>
        <span><a href="home.php">Home</a> → update profile</span>
    </div>
</div>

<div class="form-container user-form" style="margin: 20px 300px;">
<form action="" method="post" enctype="multipart/form-data" class="register">

    <div class="img-box">
        <img src="../uploaded_files/<?= $fetch_profile['image'] ?? 'default.png'; ?>">
    </div>

    <h3>update profile</h3>

    <div class="flex">
        <div class="col">
            <div class="input-field">
                <p>Your name</p>
                <input type="text" name="name"
                value="<?= htmlspecialchars($fetch_profile['name'] ?? '') ?>"
                maxlength="50" required class="box">
            </div>

            <div class="input-field">
                <p>Your email</p>
                <input type="email" name="email"
                value="<?= htmlspecialchars($fetch_profile['email'] ?? '') ?>"
                maxlength="100" required class="box">
            </div>

            <div class="input-field">
                <p>Profile Image</p>
                <input type="file" name="image" accept="image/*" class="box">
            </div>
        </div>

        <div class="col">
            <div class="input-field">
                <p>Old Password</p>
                <input type="password" name="old_pass" class="box">
            </div>

            <div class="input-field">
                <p>New Password</p>
                <input type="password" name="new_pass" class="box">
            </div>

            <div class="input-field">
                <p>Confirm Password</p>
                <input type="password" name="c_pass" class="box">
            </div>
        </div>
    </div>

    <button type="submit" name="update" class="btn">update profile</button>
</form>
</div>

<?php include 'user_footer.php'; ?>

<script>
<?php if(!empty($warning_msg)): ?>
swal("Oops!", "<?= implode(', ', $warning_msg); ?>", "error");
<?php elseif(!empty($success_msg)): ?>
swal("Success!", "<?= implode(', ', $success_msg); ?>", "success");
<?php endif; ?>
</script>

<script src="../js/user_script.js"></script>
</body>
</html>