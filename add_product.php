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

/* =========================
   IMAGE FOLDER
========================= */
$folder = __DIR__ . '/../uploaded_files/';
if(!is_dir($folder)){
    mkdir($folder, 0777, true);
}

/* =========================
   SAVE PRODUCT (PUBLISH)
========================= */
if(isset($_POST['publish'])){

    $id = uniqid('P_');

    $name = htmlspecialchars(trim($_POST['title']));
    $price = htmlspecialchars(trim($_POST['price']));
    $content = htmlspecialchars(trim($_POST['content']));
    $category = htmlspecialchars(trim($_POST['category']));
    $brand = htmlspecialchars(trim($_POST['brand']));
    $stock = htmlspecialchars(trim($_POST['stock']));
    $status = 'active';

    // FILES
    $thumb_one_name = $_FILES['thumb_one']['name'];
    $thumb_two_name = $_FILES['thumb_two']['name'];
    $thumb_three_name = $_FILES['thumb_three']['name'];
    $thumb_four_name = $_FILES['thumb_four']['name'];

    $thumb_one_tmp = $_FILES['thumb_one']['tmp_name'];
    $thumb_two_tmp = $_FILES['thumb_two']['tmp_name'];
    $thumb_three_tmp = $_FILES['thumb_three']['tmp_name'];
    $thumb_four_tmp = $_FILES['thumb_four']['tmp_name'];

    $thumb_one_path = $folder . $thumb_one_name;
    $thumb_two_path = $folder . $thumb_two_name;
    $thumb_three_path = $folder . $thumb_three_name;
    $thumb_four_path = $folder . $thumb_four_name;

    // INSERT
    $insert_product = $conn->prepare("
        INSERT INTO products
        (id, seller_id, name, price, category, brand,
         thumb_one, thumb_two, thumb_three, thumb_four,
         stock, product_detail, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert_product->execute([
        $id,
        $seller_id,
        $name,
        $price,
        $category,
        $brand,
        $thumb_one_name,
        $thumb_two_name,
        $thumb_three_name,
        $thumb_four_name,
        $stock,
        $content,
        $status
    ]);

    // UPLOAD FILES
    move_uploaded_file($thumb_one_tmp, $thumb_one_path);
    move_uploaded_file($thumb_two_tmp, $thumb_two_path);
    move_uploaded_file($thumb_three_tmp, $thumb_three_path);
    move_uploaded_file($thumb_four_tmp, $thumb_four_path);

    $success_msg[] = 'Product added successfully';
}

/* =========================
   SAVE DRAFT
========================= */
if(isset($_POST['draft'])){

    $id = uniqid('P_');

    $name = htmlspecialchars(trim($_POST['title']));
    $price = htmlspecialchars(trim($_POST['price']));
    $content = htmlspecialchars(trim($_POST['content']));
    $category = htmlspecialchars(trim($_POST['category']));
    $brand = htmlspecialchars(trim($_POST['brand']));
    $stock = htmlspecialchars(trim($_POST['stock']));
    $status = 'deactive';

    // FILES
    $thumb_one_name = $_FILES['thumb_one']['name'];
    $thumb_two_name = $_FILES['thumb_two']['name'];
    $thumb_three_name = $_FILES['thumb_three']['name'];
    $thumb_four_name = $_FILES['thumb_four']['name'];

    $thumb_one_tmp = $_FILES['thumb_one']['tmp_name'];
    $thumb_two_tmp = $_FILES['thumb_two']['tmp_name'];
    $thumb_three_tmp = $_FILES['thumb_three']['tmp_name'];
    $thumb_four_tmp = $_FILES['thumb_four']['tmp_name'];

    $thumb_one_path = $folder . $thumb_one_name;
    $thumb_two_path = $folder . $thumb_two_name;
    $thumb_three_path = $folder . $thumb_three_name;
    $thumb_four_path = $folder . $thumb_four_name;

    // INSERT
    $insert_product = $conn->prepare("
        INSERT INTO products
        (id, seller_id, name, price, category, brand,
         thumb_one, thumb_two, thumb_three, thumb_four,
         stock, product_detail, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert_product->execute([
        $id,
        $seller_id,
        $name,
        $price,
        $category,
        $brand,
        $thumb_one_name,
        $thumb_two_name,
        $thumb_three_name,
        $thumb_four_name,
        $stock,
        $content,
        $status
    ]);

    // UPLOAD FILES
    move_uploaded_file($thumb_one_tmp, $thumb_one_path);
    move_uploaded_file($thumb_two_tmp, $thumb_two_path);
    move_uploaded_file($thumb_three_tmp, $thumb_three_path);
    move_uploaded_file($thumb_four_tmp, $thumb_four_path);

    $success_msg[] = 'Product saved as draft';
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
      <h1>add products</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>add products</span>
    </div>
</div>

<div style="margin: 3rem;" class="add-product">
    <div class="heading">
        <h1 style="font-size: 3rem;">add products</h1>
        <img src="../images/separator.png">
    </div>
    <div style="padding-left: 300px; padding-top:50px; padding-bottom:50px;" class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>product name <span>*</span></p>
                        <input type="text" name="title" maxlength="100" placeholder="add product name" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product price <span>*</span></p>
                        <input type="number" name="price" maxlength="10" placeholder="add product price" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product stock <span>*</span></p>
                        <input type="number" name="stock" maxlength="10" placeholder="add product stock" min="0" max="99999999999" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product brand<span>*</span></p>
                        <input type="text" name="brand" maxlength="100" placeholder="add product name" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>product thumb one <span>*</span></p>
                        <input type="file" name="thumb_one" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb two <span>*</span></p>
                        <input type="file" name="thumb_two" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb three <span>*</span></p>
                        <input type="file" name="thumb_three" accept="image/*" required class="box">
                    </div>
                    <div class="input-field">
                        <p>product thumb four <span>*</span></p>
                        <input type="file" name="thumb_four" accept="image/*" required class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
             <p>product category <span>*</span></p>
             <input type="text" name="category" maxlength="100" placeholder="add product category" required class="box">
            </div>
            <div class="input-field">
             <p>product description <span>*</span></p>
             <textarea class="box" name="content"></textarea>
            </div>
            <div class="flex-btn">
                <button type="submit" name="publish" class="btn">publish now</button>
                 <button type="submit" name="draft" class="btn">save as draft</button>
            </div>
        </form>
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
