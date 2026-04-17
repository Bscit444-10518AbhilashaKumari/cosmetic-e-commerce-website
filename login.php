<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];

if (isset($_POST['login'])) {

    $email    = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $warning_msg[] = "All fields are required";
    } else {

        // GET USER
        $stmt = $conn->prepare("
            SELECT id, name, password 
            FROM sellers 
            WHERE email = :email 
            LIMIT 1
        ");

        $stmt->execute(['email' => $email]);
        $seller = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($seller) {

            // ✅ PLAIN PASSWORD CHECK (NO HASH)
            if ($password === $seller['password']) {

                session_regenerate_id(true);

                $_SESSION['seller_id'] = $seller['id'];
                $_SESSION['seller_name'] = $seller['name'];

                setcookie(
                    'seller_id',
                    $seller['id'],
                    time() + (60 * 60 * 24 * 30),
                    "/"
                );

                header("Location: dashboard.php");
                exit;

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
<link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

<title>Login</title>
</head>

<body>

<div class="banner">
    <div class="detail">
        <h1>Login</h1>
        <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i> login</span>
    </div>
</div>

<div class="form-container form" style="margin-left: 400px; margin-top: 50px;">
    <form action="" method="post">

        <h3>Login</h3>

        <input type="email" name="email" placeholder="Enter email" required class="box">

        <input type="password" name="password" placeholder="Enter password" required class="box">

        <button type="submit" name="login" class="btn">Login</button>

        <p>Don't have account? <a href="register.php">Register</a></p>

    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal({
    title: "Oops!",
    text: "<?= implode("\\n", $warning_msg); ?>",
    icon: "error",
    button: "Ok",
});
<?php endif; ?>
</script>

</body>
</html>