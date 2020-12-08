<?php

$loginLogoutLabel = $loginLogoutRedirect = $loggedInAsUser = $loggedInAsHospital = '';

$path = $_SERVER['PHP_SELF'];
$pathArray = explode("/", $path);
$sizePathArray = sizeof($pathArray);
$pathEnd = $pathArray[$sizePathArray - 1];
echo $pathEnd;


if (isset($_SESSION['LOGGED_IN_AS'])) {
    $loginLogoutLabel = 'Logout';
    $loginLogoutRedirect = 'logout.php';

    if (isset($_SESSION['USER'])) {
        $loggedInAsUser = true;
        $loggedInAsHospital = false;
    } else if (isset($_SESSION['HOSPITAL'])) {
        $loggedInAsHospital = true;
        $loggedInAsUser = false;
    }
} else {
    $loginLogoutLabel = 'Login';
    $loginLogoutRedirect = 'login.php';

    $loggedInAsUser = $loggedInAsHospital = false;
}


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank</title>
    <!-- axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Bootsrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Open Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            width: 100%;
            background-image: linear-gradient(135deg, #FF9D6C 10%, #BB4E75 100%);
        }

        footer {
            width: 100%;
        }

        .logo {
            color: #EB3349;
        }

        .btn-header {
            background-image: linear-gradient(to right, #EB3349 0%, #F45C43 51%, #EB3349 100%)
        }

        .btn-header {
            padding: 10px 45px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            border-radius: 10px;
            display: block;
        }

        .btn-header:hover {
            background-position: right center;
            color: #fff;
            text-decoration: none;
        }
    </style>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark p-4 mb-4">

        <div class="d-flex">

            <h2 class="logo align-self-baseline font-weight-bold"> BloodBank </h2>
            <svg width="2em" height="2em" viewBox="0 0 16 16" class="align-self-baseline bi bi-droplet-fill" fill="#EB3349" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6zM6.646 4.646c-.376.377-1.272 1.489-2.093 3.13l.894.448c.78-1.559 1.616-2.58 1.907-2.87l-.708-.708z" />
            </svg>

        </div>


        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto mr-auto">
                <li class="nav-item <?php echo $pathEnd == 'index.php' ? 'active' : null ?> mr-3">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo $pathEnd == 'availableBlood.php' ? 'active' : null ?> mr-3">
                    <a class="nav-link" href="availableBlood.php">Available Blood</a>
                </li>

                <?php
                if ($loggedInAsUser) {
                    $active = $pathEnd == 'bloodRequestHistory.php' ? 'active' : '';
                ?>
                    <li class="nav-item <?php echo $active ?> mr-3">
                        <a class="nav-link" href="bloodRequestHistory.php">Blood Request History</a>
                    </li>

                <?php }


                if ($loggedInAsHospital) {
                    $active = $pathEnd == 'addBlood.php' ? ' active ' : '';

                ?>
                    <li class="nav-item <?php echo $active ?> mr-3">
                        <a class="nav-link" href="addBlood.php">Add Blood</a>
                    </li>

                    <?php
                    $active = $pathEnd == 'bloodRequest.php' ? ' active ' : '';
                    ?>
                    <li class="nav-item <?php echo $active ?> mr-3">
                        <a class="nav-link" href="bloodRequest.php">Blood Requests</a>
                    </li>

                <?php } ?>

            </ul>
            <a href=<?php echo "'" . $loginLogoutRedirect . "'" ?> class="btn-header btn btn-danger"><?php echo $loginLogoutLabel ?></a>
        </div>

    </nav>

</head>

<script>
    axios.defaults.baseURL = 'http://localhost/bloodbank/'; // comment off when working locally
</script>