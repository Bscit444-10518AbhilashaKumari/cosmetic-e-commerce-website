<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

$warning_msg = [];
$success_msg = [];

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['send_message'])) {

    // user must be logged in
    if ($user_id === '') {
        $warning_msg[] = 'Please login to send message';
    } else {

        // unique message id
        $id = uniqid('msg_', true);

        // form data
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // validation
        if ($name === '' || $email === '' || $subject === '' || $message === '') {
            $warning_msg[] = 'All fields are required';
        }

        // duplicate check
        if (empty($warning_msg)) {

            $check = $conn->prepare(
                "SELECT id FROM `message`
                 WHERE user_id = :user_id
                   AND name = :name
                   AND email = :email
                   AND subject = :subject
                   AND message = :message
                 LIMIT 1"
            );

            $check->execute([
                ':user_id' => $user_id,
                ':name'    => $name,
                ':email'   => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);

            if ($check->fetch()) {
                $warning_msg[] = 'Message already sent';
            }
        }

        // insert message
        if (empty($warning_msg)) {

            $insert = $conn->prepare(
                "INSERT INTO `message`
                 (id, user_id, name, email, subject, message)
                 VALUES (:id, :user_id, :name, :email, :subject, :message)"
            );

            if ($insert->execute([
                ':id'      => $id,
                ':user_id' => $user_id,
                ':name'    => $name,
                ':email'   => $email,
                ':subject' => $subject,
                ':message' => $message
            ])) {
                $success_msg[] = 'Message sent successfully';
            } else {
                $warning_msg[] = 'Failed to send message';
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
      <h1>Contact Us</h1>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium <br>
        modi neque aut, voluptatem saepe non nemo sequi quas, animi reiciendis <br>
        vitae doloremque sed facilis est illo quisquam fugit obcaecati omnis.</p>
         <span><a href="home.php">home</a><i class="bx bx-right-arrow-alt"></i>contact us</span>
    </div>
</div>

<div class="service">
    <div class="heading">
        <h1>Our Service</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Harum tenetur accusantium at provident optio sint
            praesentium! <br> Numquam maxime quae quam, vel distinctio, repudiandae at sapiente commodi dolore vero deleniti laborum!</p>
        <img src="../images/separator.png">
    </div>
    <div class="box-container">
        <div class="box">
            <img src="../images/m-icon0.png">
            <h1>Free Shipping Fast</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div class="box">
            <img src="../images/icon0.png">
            <h1>Money Back Guarantee</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div class="box">
            <img src="../images/icon1.png">
            <h1>Online Support 24/7</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
    </div>
</div>

<div class="contact">
    <div class="heading">
        <h1>Drop a Line</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Harum tenetur accusantium at provident optio sint
            praesentium! <br> Numquam maxime quae quam, vel distinctio, repudiandae at sapiente commodi dolore vero deleniti laborum!</p>
        <img src="../images/separator.png">
    </div>
    <div class="box-container">
       <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2219776.8427819926!2d86.81967653160515!3d25.391085155020196
        !2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1768832379161!5m2!1sen!2sin"
         width="100%" height="850" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        <div class="form-container">
            <form action="" method="post" class="login">
                <div class="input-field">
                    <p>Your name <span>*</span></p>
                    <input type="text" name="name" placeholder="Enter your name" required class="box">
                </div>
                <div class="input-field">
                    <p>Your email <span>*</span></p>
                    <input type="email" name="email" placeholder="Enter your email" maxlength="100" required class="box">
                </div>
                <div class="input-field">
                    <p>Subject <span>*</span></p>
                    <input type="text" name="subject" placeholder="Enter your subject" required class="box">
                </div>
                <div class="input-field">
                    <p>Your message <span>*</span></p>
                    <textarea name="message" placeholder="Enter your message" required class="box"></textarea>
                </div>
                <button type="submit" name="send_message" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</div>

<div class="address">
    <div class="heading">
        <h1>Our Contact Details</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim corporis rem reprehenderit qui harum perspiciatis nobis <br>
            totam maiores exercitationem aliquid deserunt nostrum quis sunt dolores, quaerat adipisci deleniti soluta cumque?</p>
        <img src="../images/separator.png">
    </div>
    <div class="box-container">
        <div class="box">
            <img src="../images/s-icon0.png">
            <div>
                <h4>Address</h4>
                <p>1093 Marigold Lane, Coral Way <br> Miami, Florida, 33169</p>
            </div>
        </div>
        <div class="box">
            <img src="../images/s-icon1.png">
            <div>
                <h4>Phone Number</h4>
                <p>3344456789</p>
                <p>3344456789</p>
            </div>
        </div>
        <div class="box">
            <img src="../images/s-icon.png">
            <div>
                <h4>Email</h4>
                <p>abhilasharay648@gmail.com</p>
                <p>shardaabhilasha648@gmail.com</p>
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
