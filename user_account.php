<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../components/connect.php';

$warning_msg = [];
$success_msg = [];

/* ---------------- LOGIN CHECK ---------------- */
$seller_id = $_SESSION['seller_id'] ?? '';
if($seller_id == ''){
   header('location:login.php');
   exit;
}

/* ---------------- DELETE USER ---------------- */
if(isset($_POST['delete'])){

    $delete_id = $_POST['delete_id'] ?? '';

    if(!empty($delete_id)){

        // check user exists
        $verify_delete = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if($verify_delete->rowCount() > 0){

            $delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
            $delete_user->execute([$delete_id]);

            $success_msg[] = 'User deleted successfully';

        } else {
            $warning_msg[] = 'User already deleted or not found';
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

<link rel="stylesheet" href="../css/admin_style.css?v=<?= time(); ?>">

<title>Registered Users</title>
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>Registered Users</h1>
        <span>
            <a href="dashboard.php">dashboard</a>
            <i class="bx bx-right-arrow-alt"></i>
            users
        </span>
    </div>
</div>

<div class="user-container">

<div class="heading">
    <h1 style="font-size:50px;">Registered Users</h1>
</div>

<!-- SEARCH FORM -->
<form action="" method="post" class="search-form">
    <input type="text" name="search_box" placeholder="Search users..." maxlength="100">
    <button type="submit" name="search_btn" class="bx bx-search-alt"></button>
</form>

<div class="box-container">

<?php
/* ---------------- SEARCH ---------------- */
$search = '';

if(isset($_POST['search_btn']) && !empty($_POST['search_box'])){
    $search = trim($_POST['search_box']);
}

/* ---------------- QUERY ---------------- */
if($search != ''){
    $select_user = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
    $select_user->execute(["%$search%", "%$search%"]);
} else {
    $select_user = $conn->prepare("SELECT * FROM users");
    $select_user->execute();
}

if($select_user->rowCount() > 0){

    while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
?>

    <div class="box">
        <img src="../uploaded_files/<?= $fetch_user['image']; ?>">

        <div class="detail">
            <p>User ID: <span><?= $fetch_user['id']; ?></span></p>
            <p>Name: <span><?= $fetch_user['name']; ?></span></p>
            <p>Email: <span><?= $fetch_user['email']; ?></span></p>

            <form action="" method="post">
                <input type="hidden" name="delete_id" value="<?= $fetch_user['id']; ?>">
                <button type="submit" name="delete" class="btn"
                    onclick="return confirm('Delete this user?')">
                    Delete User
                </button>
            </form>
        </div>
    </div>

<?php
    }

} else {
    echo '<div class="empty"><p>No users found</p></div>';
}
?>

</div>
</div>

<?php include __DIR__ . '/../components/admin_footer.php'; ?>

<!-- SWEET ALERT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
<?php if(!empty($warning_msg)): ?>
swal({
    title: "Oops!",
    text: "<?= implode("\n", $warning_msg); ?>",
    icon: "error",
    button: "Ok",
});
<?php elseif(!empty($success_msg)): ?>
swal({
    title: "Success!",
    text: "<?= implode("\n", $success_msg); ?>",
    icon: "success",
    button: "Ok",
});
<?php endif; ?>
</script>

<script src="../js/admin_script.js"></script>
<?php include __DIR__ . '/../components/alert.php'; ?>

</body>
</html>