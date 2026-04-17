<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if(isset($_POST['place_order'])){

    if($user_id == ''){
        $warning_msg[] = 'please login first';
    } else {

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);

        $address = filter_var(
            $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].', '.$_POST['pin'],
            FILTER_SANITIZE_STRING
        );

        $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
        $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

        /* ===== ORDER FROM SINGLE PRODUCT ===== */
        if(isset($_GET['get_id'])){

            $get_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $get_products->execute([$_GET['get_id']]);

            if($get_products->rowCount() > 0){

                $fetch_p = $get_products->fetch(PDO::FETCH_ASSOC);
                $seller_id = $fetch_p['seller_id'];

                $insert_order = $conn->prepare("
                INSERT INTO orders 
                (id, user_id, number, seller_id, name, email, address, address_type, method, product_id, price, qty)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $insert_order->execute([
                    unique_id(),
                    $user_id,
                    $number,
                    $seller_id,
                    $name,
                    $email,
                    $address,
                    $address_type,
                    $method,
                    $fetch_p['id'],
                    $fetch_p['price'],
                    1
                ]);

                header('location:order.php');
                exit;
            }

        } 
        /* ===== ORDER FROM CART ===== */
        else {

            $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
            $verify_cart->execute([$user_id]);

            if($verify_cart->rowCount() > 0){

                while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

                    $s_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                    $s_products->execute([$f_cart['product_id']]);
                    $f_products = $s_products->fetch(PDO::FETCH_ASSOC);

                    $insert_order = $conn->prepare("
                    INSERT INTO orders 
                    (id, user_id, number, seller_id, name, email, address, address_type, method, product_id, price, qty)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $insert_order->execute([
                        unique_id(),
                        $user_id,
                        $number,
                        $f_products['seller_id'],
                        $name,
                        $email,
                        $address,
                        $address_type,
                        $method,
                        $f_cart['product_id'],
                        $f_products['price'],
                        $f_cart['qty']
                    ]);
                }

                $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                header('location:order.php');
                exit;
            }
        }
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

<title>Cosmika A Cosmetic Website Template</title>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="banner">
    <div class="detail">
      <h1>checkout</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>checkout</span>
    </div>
</div>

<div class="checkout">
    <div class="heading">
        <h1 style="font-size: 30px;">checkout summary</h1>
        <img src="../images/separator.png">
    </div>
    <div class="row">
        <!----------form-container start--------->
        <div style="margin-left: 300px; margin-top:50px; margin-bottom:50px;"class="form-container">
            <form action="" method="post" class="register">
                <input type="hidden" name="p_id" value="<?= $get_id;?>">
                <h3>billing details</h3>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>Your name <span>*</span></p>
                              <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
                        </div>
                        
                        <div class="input-field">
                            <p>Your number <span>*</span></p>
                              <input type="number" name="number" placeholder="Enter your number" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>Your email <span>*</span></p>
                            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>payment method <span>*</span></p>
                            <select name="method" class="box">
                                <option selected disabled>select payment method</option>
                                <option value="cash on delivery">cash on delivery</option>
                                <option value="credit or debit card">credit or debit card</option>
                                <option value="net banking">net banking</option>
                                <option value="UPI or RuPay">UPI or RuPay </option>
                                <option value="paytm">paytm</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>address type<span>*</span></p>
                            <select name="address_type" class="box">
                                <option selected disabled>select address type</option>
                                <option value="home">home</option>
                                <option value="office">office</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>address line 02 <span>*</span></p>
                            <input type="text" name="flat" placeholder="e.g flat or building" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>address line 02 <span>*</span></p>
                            <input type="text" name="street" placeholder="e.g street name" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>city name <span>*</span></p>
                            <input type="text" name="city" placeholder="e.g mumbai" maxlength="50" required class="box">
                        </div>

                        <div class="input-field">
                            <p>country name <span>*</span></p>
                            <input type="text" name="country" placeholder="e.g country name" maxlength="50" required class="box">
                        </div>
                        <div class="input-field">
                            <p>pincode <span>*</span></p>
                            <input type="text" name="pin" placeholder="110088" maxlength="50" required class="box">
                        </div>
                    </div>
                </div>
                <button type="submit" name="place_order" class="btn">place order</button>
            </form>
        </div>
        <!-------form container end------>
        <div class="summary">
            <h3>my bag</h3>
            <div class="box-container">
                <?php 
                $grand_total = 0;

                if(isset($_GET['get_id'])){
                    $select_get = $conn->prepare("SELECT * FROM  products WHERE id = ?");
                    $select_get->execute([$_GET['get_id']]);

                    while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                        $sub_total = $fetch_get['price'];
                        $grand_total+= $sub_total;
                    
                
                ?>
                <div class="flex">
                    <img src="../images/products/<?= $fetch_get['thumb_one']; ?>" class="image">
                    <div>
                     <h3 class="name"><?= $fetch_get['name']; ?></h3>
                     <p class="price"><?= $fetch_get['price']; ?></p>
                    </div>
                </div>
                <?php 
                    }
                }else{
                    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                    $select_cart->execute(['$user_id']);

                    if($select_cart->rowCount() > 0){
                        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                            $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");

                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            $sub_total = ($fetch_cart['qty'] * $fetch_products['price']);
                            $grand_total+= $sub_total;
                        
                
                ?>
                <div class="flex">
                    <img src="../images/products/<?= $fetch_products['thumb_one'] ?>" class="image">
                    <div>
                     <h3 class="name"><?= $fetch_products['name']; ?></h3>
                     <p class="price"><?= $fetch_products['price']; ?></p>
                    </div>
                </div>
                <?php 
                        }
                    }else{
                        echo'
                 <div class="empty">
               <p> no product found yet!</p>
                </div>
        
        ';
                    }
                }
                
                ?>
            </div>
            <div class="grand-total">
               <span>total amount payable :</span>$<?= $grand_total; ?>/-
            </div>
        </div>
    </div>
</div>









<?php include 'user_footer.php'; ?>

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

<script src="../js/user_script.js"></script>
</body>
</html>

