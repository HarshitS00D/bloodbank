<?php

session_start();

include 'config/db_connect.php';


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'templates/header.php' ?>

<body>
    <div class="container mt-5">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/1.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Donate Blood</h5>
                        <p>“Bring a life back to power. Make blood donation your responsibility”</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/2.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Blood Donation Fact</h5>
                        <p>"The average red blood cell transfusion is 3 pints"</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/3.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Blood Donation Fact</h5>
                        <p>"Just 1 donation can save up to 3 lives."</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <?php include 'templates/footer.php' ?>

</html>