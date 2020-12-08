<?php

session_start();

include '../config/db_connect.php';

if (isset($_SESSION['LOGGED_IN_AS'])) {
    header("Location:/bloodbank/index.php");
}

$hospitalName = $email = $phoneNumber = $state = $city = $address = $password = $confirmPassword = '';
$errors = array('hospitalName' => '', 'email' => '', 'phoneNumber' => '', 'password' => '', 'confirmPassword' => '');

if (isset($_POST['submit'])) {
    // print_r($_POST);

    $hospitalName = trim($_POST['hospitalName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid email address';
    }
    if (!preg_match('/^[0-9]{10}/', $phoneNumber)) {
        $errors['phoneNumber'] = 'Enter a valid Phone Number';
    }
    if (!preg_match('/^[a-zA-Z0-9@#]{6,}/', $password)) {
        $errors['password'] = 'Password must be of atleast 6 characters and can only contain alphabets, digits and special characters like @,#.';
    }
    if ($password != $confirmPassword) {
        $errors['confirmPassword'] = 'Password do not match';
    }

    if (array_filter($errors)) { // error

    } else { // no error


        $sql = "SELECT * FROM users where Email = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usersTableRowCount = $stmt->rowCount();

        $sql = "SELECT * FROM hospitals where Email = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $hospitalsTableRowCount = $stmt->rowCount();

        if ($usersTableRowCount == 0 && $hospitalsTableRowCount == 0) {
            try {
                $sql = "INSERT INTO hospitals(HospitalName,Email,PhoneNumber,State,City,Address,Password) VALUES(?,?,?,?,?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hospitalName, $email, $phoneNumber, $state, $city, $address, $password]);
            } catch (Exception $e) {
                echo $e->getMessage();
                echo 'query failed';
            }

            header('Location: /bloodbank/login.php');
        } else {
            $errors['email'] = 'Email is already registered';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include '../templates/header.php'; ?>
<link rel="stylesheet" href="css/hospital-style.css" />


<body>

    <div class="container d-flex justify-content-center">

        <form class="rounded bg-light text-secondary" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="d-flex justify-content-center mb-4">
                <h3 class="text-secondary">Hospital Register</h3>
            </div>

            <div class="form-group">
                <label for="inputHospitalName"> Hospital Name</label>
                <input type="text" class="form-control <?php echo $errors['hospitalName'] == '' ? null : 'is-invalid' ?>" id="inputHospitalName" name="hospitalName" value="<?php echo $hospitalName ?>" aria-describedby="hospitalNameFeedback" required>
                <div id="hospitalNameFeedback" class="invalid-feedback">
                    <?php echo $errors['firstName'] ?>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" class="form-control <?php echo $errors['email'] == '' ? null : 'is-invalid' ?>" id="inputEmail" name="email" value="<?php echo $email ?>" aria-describedby="emailFeedback" required>
                <div id="emailFeedback" class="invalid-feedback">
                    <?php echo $errors['email'] ?>
                </div>
            </div>


            <div class="form-group">
                <label for="inputPhoneNumber"> Phone Number</label>
                <input type="number" class="form-control <?php echo $errors['phoneNumber'] == '' ? null : 'is-invalid' ?>" id="inputPhoneNumber" name="phoneNumber" value="<?php echo $phoneNumber ?>" aria-describedby="phoneNumberFeedback" required>
                <div id="phoneNumberFeedback" class="invalid-feedback">
                    <?php echo $errors['phoneNumber'] ?>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label for="inputState">State</label>
                        <select onchange="print_city('inputCity', this.selectedIndex);" id="inputState" name="state" class="form-control" required></select>
                    </div>
                    <div class="col">
                        <label for="inputcity">City</label>
                        <select class="form-control" id="inputCity" name="city" required></select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputAddress">Address</label>
                <textarea type="text" class="form-control" id="inputAddress" name="address" value="<?php echo $address ?>" required></textarea>
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" class="form-control <?php echo $errors['password'] == '' ? null : 'is-invalid' ?>" id="inputPassword" name="password" aria-describedby="" value="<?php echo $password ?>" aria-describedby="passwordFeedback" required>
                <div id="passwordFeedback" class="invalid-feedback">
                    <?php echo $errors['password'] ?>
                </div>
            </div>


            <div class="form-group">
                <label for="inputConfirmPassword">Confirm Password</label>
                <input type="password" class="form-control <?php echo $errors['confirmPassword'] == '' ? null : 'is-invalid' ?>" id="inputConfirmPassword" name="confirmPassword" aria-describedby="confirmPasswordFeedback" value="<?php echo $confirmPassword ?>" required>
                <div id="confirmPasswordFeedback" class="invalid-feedback">
                    <?php echo $errors['confirmPassword'] ?>
                </div>
            </div>

            <div class="d-flex justify-content-center m-4">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>

            <div class="d-flex">
                <p><a href="/bloodbank/register/user.php" class="text-primary">Register as a User ?</a></p>
                <p class="ml-auto"><a href="/bloodbank/login.php" class="text-primary">Already have an Account ?</a></p>
            </div>


        </form>

    </div>

    <?php include '../templates/footer.php' ?>
    <script src='js/cities.js'></script>
    <script language="javascript">
        print_state("inputState", <?php echo "'" . $state . "'" ?>, <?php echo "'" . $city . "'" ?>);
    </script>

</html>