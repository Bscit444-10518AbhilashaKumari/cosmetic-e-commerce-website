<?php
session_start();
include 'connect.php';

$warning_msg = [];
$success_msg = [];

if (isset($_POST['login'])) {

    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $warning_msg[] = "All fields are required";
    } else {

        $stmt = $conn->prepare("
            SELECT id, name, password 
            FROM users 
            WHERE email = :email 
            LIMIT 1
        ");

        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if ($password === $user['password']) {

                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];

                // ✅ SUCCESS MESSAGE
                $success_msg[] = "Login successful! Welcome " . $user['name'];

                // redirect after 1 sec
                echo "<script>
                    setTimeout(function(){
                        window.location.href = 'home.php';
                    }, 1000);
                </script>";

            } else {
                $warning_msg[] = "Incorrect password";
            }

        } else {
            $warning_msg[] = "Email not found";
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

<title>Login</title>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>Login</h1>
        <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i> login</span>
    </div>
</div>

<div class="form-container" style="margin-left:300px; margin-top:20px; margin-bottom:20px;">
    <form action="" method="post" class="login">

        <h3>Login</h3>

        <div class="input-field">
            <p>Your email <span>*</span></p>
            <input type="email" name="email" required class="box">
        </div>

        <div class="input-field">
            <p>Your password <span>*</span></p>
            <input type="password" name="password" required class="box">
        </div>

        <p>Don't have an account? <a href="register.php">Register now</a></p>

        <button type="submit" name="login" class="btn">Login</button>

    </form>
</div>

<?php include 'user_footer.php'; ?>

<!-- SWEETALERT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal({
    title: "Oops!",
    text: <?= json_encode(implode("\n", $warning_msg)) ?>,
    icon: "error",
    button: "Ok",
});

<?php elseif(!empty($success_msg)): ?>
swal({
    title: "Success!",
    text: <?= json_encode(implode("\n", $success_msg)) ?>,
    icon: "success",
    button: "Ok",
});
<?php endif; ?>
</script>

<script src="../js/user_script.js"></script>

</body>
</html>