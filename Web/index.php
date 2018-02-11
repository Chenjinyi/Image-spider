<?php

include_once 'config.php';

$connect = mysqli_connect($DataHost,$DataUser,$DataPass,$DataBase)or die('连接数据库失败！');
mysqli_select_db($connect, $DataName) or die('选择数据库失败！');
mysqli_set_charset($connect,'utf8');

$sql="SELECT * FROM image";
$result = mysqli_query($connect,$sql);

$row = mysqli_fetch_all($result);

?>
<!DOCTYPE html>

<html class="no-js"  lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>:: Picxa :: A Photographer Portfolio Template</title>

    <!-- Normalize -->

    <link rel="stylesheet" href="css/assets/normalize.css" type="text/css">

    <!-- Bootstrap -->

    <link href="css/assets/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Font-awesome.min -->

    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Effet -->

    <link rel="stylesheet" href="css/gallery/foundation.min.css"  type="text/css">
    <link rel="stylesheet" type="text/css" href="css/gallery/set1.css" />

    <!-- Main Style -->

    <link rel="stylesheet" href="css/main.css" type="text/css">

    <!-- Responsive Style -->

    <link href="css/responsive.css" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

    <script src="js/assets/modernizr-2.8.3.min.js" type="text/javascript"></script>
</head>

<body>

<!-- header -->

<header id="header" class="header">
    <div class="container-fluid">
        <hgroup>

            <!-- logo -->

            <h1> <a href="index3.html" title="Picxa"><img src="images/logo.png" alt="Picxa" title="Picxa"/></a> </h1>

            <!-- logo -->

            <!-- nav -->

            <nav>
                <div class="menu-expanded">
                    <div class="nav-icon">
                        <div id="menu" class="menu"></div>
                        <p>menu</p>
                    </div>
                    <div class="cross"> <span class="linee linea1"></span> <span class="linee linea2"></span> <span class="linee linea3"></span> </div>
                    <div class="main-menu">
                        <ul>
                            <li class="active"><a href="index.html">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="blog.html">blog</a></li>
                            <li><a href="contact.html">contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- nav -->

        </hgroup>
    </div>
</header>

<!-- header -->

<main class="main-wrapper" id="container">

    <!-- image Gallery -->

    <div class="wrapper">
        <div class="">
            <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-5 masonry">
                <?php foreach ($row as $table){ ?>
                    <li class="masonry-item grid">
                        <figure class="effect-sarah"> <img src="./<?php echo $table[1] ?>" alt="" />
                            <figcaption>
                                <h2><?php echo $table[2] ?></h2>
                                <a href="<?php echo $table[3] ?>">View more</a> </figcaption>
                        </figure>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</main>

<!-- Image Gallery -->

<!-- footer -->

<footer class="footer">
    <h3>Stay connected with us</h3>
    <div class="container footer-bot">
        <div class="row">

            <!-- logo -->

            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"> <img src="images/footer-logo.png" alt="Picxa" title="Picxa"/>
                <p class="copy-right">&copy; Reserved Picxa inc 2016.</p>
            </div>

            <!-- logo -->

            <!-- address -->

            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 padding-top">
                <address>
                    <p>200 Broadway Av</p>
                    <p>West Beach SA 5024  Australia</p>
                </address>
            </div>

            <!-- address -->

            <!-- email -->

            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 padding-top">
                <p><a href="mailto:contact@Picxa.com">contact@Picxa.com</a></p>
                <p>01 (2) 34 56 78</p>
            </div>

            <!-- email -->

            <!-- social -->

            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 padding-top">
                <ul class="social">
                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-flickr" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-tripadvisor" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-delicious" aria-hidden="true"></i></a></li>
                </ul>
                <p class="made-by">Made with by <i class="fa fa-heart" aria-hidden="true"></i> <a href="http:///" target="_blank">Designstub</a>
                <p>
            </div>

            <!-- social -->

        </div>
    </div>
</footer>

<!-- footer -->

<!-- jQuery -->

<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/assets/jquery.min.js"><\/script>')</script>
<script src="js/assets/plugins.js" type="text/javascript"></script>
<script src="js/assets/bootstrap.min.js" type="text/javascript"></script>
<script src="js/maps.js" type="text/javascript"></script>
<script src="js/custom.js" type="text/javascript"></script>
<script src="js/jquery.contact.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
<script src="js/gallery/masonry.pkgd.min.js" type="text/javascript"></script>
<script src="js/gallery/imagesloaded.pkgd.min.js" type="text/javascript"></script>
<script src="js/gallery/jquery.infinitescroll.min.js" type="text/javascript"></script>
<script src="js/gallery/main.js" type="text/javascript"></script>
<script src="js/jquery.nicescroll.min.js" type="text/javascript"></script>
</body>
</html>