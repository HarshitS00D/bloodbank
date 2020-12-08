<?php

session_start();

include '../config/db_connect.php';

if (isset($_SESSION['LOGGED_IN_AS'])) {
    header("Location:../index.php");
}

$firstName = $lastName = $email = $phoneNumber = $bloodGroup = $password = $confirmPassword = '';
$errors = array('firstName' => '', 'lastName' => '', 'email' => '', 'phoneNumber' => '', 'password' => '', 'confirmPassword' => '');


if (isset($_POST['submit'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $bloodGroup = $_POST['bloodGroup'];
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);


    if (!preg_match('/^[A-Za-z]+$/', $firstName)) {
        $errors['firstName'] = 'Name can only contain alphabets';
    }
    if (!preg_match('/^[A-Za-z]+$/', $lastName)) {
        $errors['lastName'] = 'Name can only contain alphabets';
    }
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

    if (array_filter($errors)) { //errors

    } else { // no errors


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
                $sql = "INSERT INTO users(FirstName,LastName,Email,PhoneNumber,BloodGroup,Password) VALUES(?,?,?,?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $bloodGroup, $password]);
            } catch (Exception $e) {
                echo $e->getMessage();
                echo 'query failed';
            }

            header('Location: ../login.php');
        } else {
            $errors['email'] = 'Email is already registered';
        }
    };
}



?>

<!DOCTYPE html>
<html lang="en">

<?php include '../templates/header.php'; ?>
<link rel="stylesheet" href="css/user-style.css" />


<body>

    <div class="container d-flex justify-content-center">
        <form class="rounded bg-light text-secondary" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

            <div class="d-flex justify-content-center mb-4">
                <h3 class="text-secondary">User Register</h3>
            </div>

            <div class="form-group">
                <label for="inputFirstName"> First Name</label>
                <input type="text" class="form-control <?php echo $errors['firstName'] == '' ? null : 'is-invalid' ?>" id="inputFirstName" name="firstName" value="<?php echo $firstName ?>" aria-describedby="firstNameFeedback" required>
                <div id="firstNameFeedback" class="invalid-feedback">
                    <?php echo $errors['firstName'] ?>
                </div>
            </div>

            <div class="form-group">
                <label for="inputLastName"> Last Name</label>
                <input type="text" class="form-control <?php echo $errors['lastName'] == '' ? null : 'is-invalid' ?>" id="inputLastName" name="lastName" value="<?php echo $lastName ?>" aria-describedby="lastNameFeedback" required>
                <div id="lastNameFeedback" class="invalid-feedback">
                    <?php echo $errors['lastName'] ?>
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
                <div class="form-row">
                    <div class="col">
                        <label for="inputPhoneNumber"> Phone Number</label>
                        <input type="number" class="form-control <?php echo $errors['phoneNumber'] == '' ? null : 'is-invalid' ?>" id="inputPhoneNumber" name="phoneNumber" value="<?php echo $phoneNumber ?>" aria-describedby="phoneNumberFeedback" required>
                        <div id="phoneNumberFeedback" class="invalid-feedback">
                            <?php echo $errors['phoneNumber'] ?>
                        </div>
                    </div>
                    <div class="col">
                        <label for="bloodGroup">Blood Group</label>
                        <select class="custom-select" name="bloodGroup" required>
                            <option value="">Select Option</option>
                            <option <?php echo $bloodGroup === "1" ? 'selected' : null ?> value="1">O+</option>
                            <option <?php echo $bloodGroup === "2" ? 'selected' : null ?> value="2">O-</option>
                            <option <?php echo $bloodGroup === "3" ? 'selected' : null ?> value="3">A+</option>
                            <option <?php echo $bloodGroup === "4" ? 'selected' : null ?> value="4">A-</option>
                            <option <?php echo $bloodGroup === "5" ? 'selected' : null ?> value="5">B+</option>
                            <option <?php echo $bloodGroup === "6" ? 'selected' : null ?> value="6">B-</option>
                            <option <?php echo $bloodGroup === "7" ? 'selected' : null ?> value="7">AB+</option>
                            <option <?php echo $bloodGroup === "8" ? 'selected' : null ?> value="8">AB-</option>
                        </select>
                    </div>
                </div>
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
                <p><a href="hospital.php" class="text-primary">Register as a Hospital ?</a></p>
                <p class="ml-auto"><a href="../login.php" class="text-primary">Already have an Account ?</a></p>
            </div>

        </form>
    </div>


    <?php include '../templates/footer.php' ?>

</html>