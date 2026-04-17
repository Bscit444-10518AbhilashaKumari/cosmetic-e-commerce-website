<?php
session_start();
include_once 'connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    include '../components/add_to_cart.php';
    include '../components/add_to_wishlist.php';
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

    <!-- Slick CSS -->
    <!-- Slick CSS (HEAD) -->
    <link rel="stylesheet" href="../css/slick.css">



    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/user_style.css?v=<?= time(); ?>">

    <title>Cosmika | Cosmetic Website</title>
</head>

<body>

<?php include 'user_header.php'; ?>

<div class="container-fluid">
    <div class="hero-slider">
        <!-----slider start----->
        <div class="slider-item">
            <img src="../images/slider0.webp">
            <div class="slider-caption">
                <h1>makeup has its own rules</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Shop now</a>
            </div>
        </div>
        <!----------slider end------->
         <!----------slider start------->
         <div class="slider-item">
            <img src="../images/slider0.jpg">
            <div class="slider-caption">
                <h1>early birds sale</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Explore now</a>
            </div>
         </div>
         <!----------slider end------->
          <!----------slider start------->
         <div class="slider-item">
            <img src="../images/slider.webp">
            <div class="slider-caption">
                <h1>face glame has its own rules</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Shop now</a>
            </div>
         </div>
         <!----------slider end------->
          <!----------slider start------->
         <div class="slider-item">
            <img src="../images/slider3.jpg">
            <div class="slider-caption">
                <h1>makeup has its own rules</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Shop now</a>
            </div>
         </div>
         <!----------slider end------->
          <!----------slider start------->
         <div class="slider-item">
            <img src="../images/slider4.jpg">
            <div class="slider-caption">
                <h1>final clearence <br> sale</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Shop now</a>
            </div>
         </div>
         <!----------slider end------->
          <!----------slider start------->
         <div class="slider-item">
            <img src="../images/slider1.jpg">
            <div class="slider-caption">
                <h1>makeup has its own rules</h1>
                <p>Lorem ipsum dolor sit amet consectetur,<br> adipisicing elit. Eum totam 
                exercitationem nesciunt fugit, labore nostrum ex <br>expedita. Totam distinctio saepe <br>
                quisquam provident officia, sint magni dolorum, molestiae aspernatur nesciunt eaque!</p>
                <a href="shop.php" class="btn">Shop now</a>
            </div>
         </div>
         <!------slider end---->
    </div>
    <div class="controls">
        <i class="bx bx-left-arrow-alt prev"></i>
        <i class="bx bx-right-arrow-alt next"></i>
    </div>
</div>
<!-------home slider section end----->
<div class="category">
    <div class="head">
        <div><h2>choose by category</h2></div>
        <div class="controls">
            <i class="bi bi-chevron-left left_1"></i>
            <i class="bi bi-chevron-right right_1"></i>
        </div>
    </div>

    <div class="category-item">
        <div class="box"><img src="../images/cat.webp"></div>
        <div class="box"><img src="../images/cat0.webp"></div>
        <div class="box"><img src="../images/cat1.webp"></div>
        <div class="box"><img src="../images/cat2.webp"></div>
        <div class="box"><img src="../images/cat3.webp"></div>
        <div class="box"><img src="../images/cat4.webp"></div>
        <div class="box"><img src="../images/cat5.webp"></div>
        <div class="box"><img src="../images/cat6.webp"></div>
        <div class="box"><img src="../images/cat7.webp"></div>
        <div class="box"><img src="../images/cat8.webp"></div>
        <div class="box"><img src="../images/cat9.webp"></div>
        <div class="box"><img src="../images/cat10.webp"></div>
        <div class="box"><img src="../images/cat11.webp"></div>
    </div>
</div>

<div class="about-us">
    <div class="box-container">
        <div class="img-box"></div>
            <div class="box">
                <div class="heading">
                    <span>why choose us</span>
                    <h1>why cosmika cosmetics website</h1>
                    <img src="../images/separator.png">
                   </div>
                  <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. <br>
                     Esse reiciendis saepe delectus ex, iure quod quisquam similique <br>
                      aut cumque nulla magnam ab enim consequuntur consectetur dolorum <br>
                      dolorem corrupti explicabo ipsa. Laborum ratione magnam sequi <br>
                       corporis eligendi itaque, sint voluptas velit odit molestiae, doloribus <br>
                        non iste quo natus ipsam autem praesentium sunt ea.
                    </p>
                    <a href="about.php" class="btn">Know more</a>
                    <a href="contact.php" class="btn">Contact Us</a>
                </div>
        </div>
</div>

<!--about section end -->
<div class="category">
    <div class="head">
        <div><h2>shop by brands</h2></div>
        <div class="controls">
            <i class="bi bi-chevron-left left_2"></i>
            <i class="bi bi-chevron-right right_2"></i>
        </div>
    </div>

    <div class="brand-item">
        <div class="box"><img src="../images/brand.webp"></div>
        <div class="box"><img src="../images/brand0.webp"></div>
        <div class="box"><img src="../images/brand1.webp"></div>
        <div class="box"><img src="../images/brand2.webp"></div>
        <div class="box"><img src="../images/brand3.webp"></div>
        <div class="box"><img src="../images/brand4.webp"></div>
        <div class="box"><img src="../images/brand5.webp"></div>
        <div class="box"><img src="../images/brand6.webp"></div>
        <div class="box"><img src="../images/brand7.webp"></div>
        <div class="box"><img src="../images/brand8.webp"></div>
        
    </div>
</div>
<!-- brand section end -->
<div class="frame-container">
    <div class="box-container">
       <div class="frame">
        <div class="detail">
          <span>shop seasonal</span> 
          <h2>50% off</h2> 
          <h1>all makeup brands</h1>
          <a href="shop.php" class="btn">shop now</a>
        </div>
       </div> 
       <div class="box">
        <div class="box-detail">
            <img src="../images/lookbook4.jpg" class="img">
            <div class="img-detail">
                <span>wide range</span>
                <h1>new latest collections</h1>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
        <div class="box-detail">
            <img src="../images/lookbook1.jpg" class="img">
            <div class="img-detail">
                <span>wide range</span>
                <h1>new latest collections</h1>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
       </div>
    </div>
</div>
<!-------frame container end------>
<div class="offer-2">
    <div class="detail">
        <h1>we pride ourselves on <br> exceptional cosmetics shop</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. <br>
             Ea id porro exercitationem quae voluptatum adipisci magni. <br>
              Impedit iure modi, fuga deserunt, nihil, placeat molestiae <br>
              accusamus vero pariatur aut quia facilis.</p>
              <a href="shop.php" class="btn">shop now</a>
    </div>
</div>
<!-------offer section end------>
<div class="popular-cart">
    <div class="heading">
        <h1>Selections from popular categories</h1>
    </div>
    <div class="box-container">
        <div class="box">
            <img src="../images/cat00.webp">
            <p>moisturizer</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat01.jpg">
            <p>powder concelar</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat02.jpg">
            <p>brightening</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat03.jpg">
            <p>lip glose</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat04.jpg">
            <p>eye shadow</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat05.jpg">
            <p>lips oil</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat06.jpg">
            <p>powder concelor</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat07.jpg">
            <p>face wash</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat08.jpg">
            <p>face massager</p>
            <a href="shop.php">shop now</a>
        </div>
        <div class="box">
            <img src="../images/cat09.webp">
            <p>face serum</p>
            <a href="shop.php">shop now</a>
        </div>
    </div>
</div>

<!------popular card end---->
<div class="frame-container frame-container-2">
    <div class="box-container">
       <div class="frame">
        <div class="detail">
          <span>shop seasonal</span> 
          <h2>50% off</h2> 
          <h1>all makeup brands</h1>
          <a href="shop.php" class="btn">shop now</a>
        </div>
       </div> 
       <div class="box">
        <div class="box-detail">
            <img src="../images/lookbook.jpg" class="img">
            <div class="img-detail">
                <span>wide range</span>
                <h1>new latest collections</h1>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
        <div class="box-detail">
            <img src="../images/lookbook5.jpg" class="img">
            <div class="img-detail">
                <span>wide range</span>
                <h1>new latest collections</h1>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
       </div>
    </div>
</div>
<!-------frame conatainer-2 end------>
<div class="cms-banners">
    <div class="box-container">
        <div class="box">
            <img src="../images/cms-banner01.jpg">
            <div class="detail">
                <h4>beauty bonaza sale</h4>
                <p>starting from</p>
                <span>$399</span><br>
                <a href="shop.php">shop now</a>
            </div>
        </div>
        <div class="box">
            <img src="../images/cms-banner02.jpg">
            <div class="detail">
                <h4>limit beauty offer</h4>
                <p>starting from</p>
                <span>$399</span><br>
                <a href="shop.php">shop now</a>
            </div>
        </div>
        <div class="box">
            <img src="../images/cms-banner03.jpg">
            <div class="detail">
                <h4>beauty deals inside</h4>
                <p>starting from</p>
                <span>$199</span><br>
                <a href="shop.php">shop now</a>
            </div>
        </div>
        <div class="box">
            <img src="../images/cms-banner04.jpg">
            <div class="detail">
                <h4>glam at discount</h4>
                <p>starting from</p>
                <span>$399</span><br>
                <a href="shop.php">shop now</a>
            </div>
        </div>
        <div class="box">
            <img src="../images/cms-banner01.jpg">
            <div class="detail">
                <h4>glow more pay less</h4>
                <p>starting from</p>
                <span>$299</span><br>
                <a href="shop.php">shop now</a>
            </div>
        </div>
    </div>
</div>
<!-------cms banner section end------>
<div class="offer-1">
    <div class="detail">
        <h1>special discount for all <br> latest makeup products</h1>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. <br>
             Dolores obcaecati inventore magnam quis! Hic, reiciendis <br>
              omnis? Perferendis dicta eaque sint dolorem aperiam <br>
              accusamus quae ut id deserunt qui, architecto officiis! <br>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. <br>
               Cumque, laborum. Doloremque, nesciunt esse nihil, unde <br>
               laboriosam nemo qui id ratione fugiat nisi recusandae, quos <br>
                ipsa delectus numquam iusto ducimus optio?
            </p>
            <div class="container">
                <div class="id" style="color: #666;">
                    <ul>
                        <li><span id="days"></span>days</li>
                        <li><span id="hours"></span>hours</li>
                        <li><span id="minutes"></span>minutes</li>
                        <li><span id="seconds"></span>seconds</li>
                    </ul>
                </div>
            </div>
            <a href="shop.php" class="btn">buy now</a>
    </div>
</div>

<?php include 'homeshop.php'; ?>

<!-------offer-1 section end------>
<div class="frame-container frame-container-3">
    <div class="box-container">
      <div class="frame">
        <div class="details">
          <p>hotest beauty trend</p> 
          <h1>shop best skin kit makeup</h1> 
          <span>limited time offer</span> <br>
          <a href="shop.php" class="btn">shop now</a>
        </div>
      </div>
      <div class="box">
        <div class="card">
            <img src="../images/cms-banner08.jpg">
            <div class="detail">
                <h2>get gorgeous</h2>
                <h2>35%</h2>
                <p>weeked sale !</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
        <div class="card">
            <div>
            <img src="../images/cms-banner09.jpg">
            <div class="detail">
                <h2>on face serum</h2>
                <h2>35%</h2>
                <p>weeked sale !</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>
        </div>
           <div>
             <img src="../images/cms-banner10.jpg">
              <div class="detail">
                <h2>on night creams</h2>
                <h2>45%</h2>
                <p>weeked sale !</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!-------frame-container-3 section end------>
<div class="box-contaier con">
    <img src="../images/sub-slider.webp" >
    <img src="../images/sub-slider0.webp" >
    <img src="../images/sub-slider1.webp" >
    <img src="../images/sub-slider2.webp" >
</div>
<div class="box-contaier con">
    <img src="../images/sub-slider3.webp" >
    <img src="../images/sub-slider4.webp" >
    <img src="../images/sub-slider5.webp" >
    <img src="../images/sub-slider6.webp" >
</div>





<!-------cms banner section end------>
<?php include 'user_footer.php'; ?>




<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>




<!-- jquery -->
<script src="../js/Jquery-3.2.1.min.js"></script>
<!-- slick -->
<script src="../js/slick.min.js"></script>

<!-- Main JS -->

<script>
    <?php include '../js/main.js' ?>
</script>

<!-----alert--->
<?php include 'alert.php' ?>


</body>

</html>
