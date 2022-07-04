<?php
define('WEBPAGE_TITLE', 'Home');

require_once('statics/website_info.php');
require_once('statics/database_info.php');
require_once('functions/functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('includes/head.php') ?>
</head>

<body>
    <?php include_once('includes/navbar.php') ?>

    <main>
        <div class="container-fluid">
            <div class="row mt-5 px-5">
                <div class="col-md-4">
                    <img src="images/profile.jpg" class="img-fluid shadow" style="border: 5px solid white; border-radius: 10%;">
                </div>
                <div class="col-md-8 mt-5">
                    <h1>SATISFY YOURSELF!</h1>
                    <p>Outfit of the day.</p>
                    <a class="btn btn-secondary" href="shop.php">Buy Now</a>
                </div>
            </div>
            <div class="row mt-5 bg-secondary" style="color: white;">
                <div class="col-md text-center mt-2">
                    <h2>DON'T LISTEN TO WHAT THEY SAY....WEAR IT.<br> - <b>Wendell Amante</b></h2>
                </div>
            </div>
            <div class="row mt-5 px-5">
                <div class="col-md-4">
                    <img src="images/banner.jpg" class="img-fluid shadow" style="border: 5px solid white; border-radius: 10%;">
                </div>
                <div class="col-md-8 mt-5">
                    <h2>Know more about Street Mavens</h2>
                    <p>
                        Our company aims to provide high quality streetwear without breaking the bank. We aims to build a large community and support local industry. <br><br> Morever, the company is composed of devoted and industrious individuals that aims to provide excellent product and services.
                        <br><br>We aims to bring the fresh and latest fashion trend in the town.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include_once('includes/section.php') ?>
    <?php include_once('includes/footer.php') ?>
    <?php include_once('includes/external-js.php') ?>
</body>

</html>