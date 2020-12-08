<?php

session_start();

include 'config/db_connect.php';

if (isset($_SESSION['LOGGED_IN_AS'])) {
    header("Location:index.php");
}

$email = $password = $loginAs = '';
$errors = array('email' => '', 'password' => '');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $loginAs = $_POST['radio'];

    if ($loginAs === 'user') {
        try {

            $sql = "SELECT * from users where Email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            $rowCount = $stmt->rowCount();

            if ($rowCount == 1) {
                $sql = "SELECT * FROM users where Email = ? and Password = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email, $password]);

                $rowCount = $stmt->rowCount();

                if ($rowCount == 1) {
                    $user = $stmt->fetch();

                    //Session Varibales
                    $_SESSION['LOGGED_IN_AS'] = $loginAs;
                    $_SESSION['USER'] = $user;

                    header('Location: index.php');
                } else {
                    $errors['password'] = "Wrong password";
                }
            } else {
                $errors['email'] = "Sorry, we couldn't find any account with this Email";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else if ($loginAs == 'hospital') {
        try {

            $sql = "SELECT * from hospitals where Email = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            $rowCount = $stmt->rowCount();

            if ($rowCount == 1) {
                $sql = "SELECT * FROM hospitals where Email = ? and Password = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email, $password]);

                $rowCount = $stmt->rowCount();

                if ($rowCount == 1) {
                    $hospital = $stmt->fetch();

                    //Session Variables
                    $_SESSION['LOGGED_IN_AS'] = $loginAs;
                    $_SESSION['HOSPITAL'] = $hospital;

                    header('Location: index.php');
                } else {
                    $errors['password'] = "Wrong password";
                }
            } else {
                $errors['email'] = "Sorry, we couldn't find any account with this Email";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'templates/header.php' ?>
<link rel="stylesheet" href="css/login-style.css" />

<body>

    <div class="container d-flex justify-content-center">
        <form class="rounded bg-light text-secondary" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

            <div class="d-flex justify-content-center mb-4">
                <h3 class="text-secondary">Login</h3>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            </svg>
                        </span>
                    </div>
                    <input type="email" class="form-control <?php echo $errors['email'] == '' ? null : 'is-invalid' ?>" id="inputEmail" name="email" value="<?php echo $email ?>" placeholder="email" aria-describedby="emailFeedback" required>
                    <div id="emailFeedback" class="invalid-feedback">
                        <?php echo $errors['email'] ?>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-lock-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z" />
                                <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z" />
                            </svg> </span>
                    </div>
                    <input type="password" class="form-control <?php echo $errors['password'] == '' ? null : 'is-invalid' ?>" id="inputPassword" name="password" value="<?php echo $password ?>" placeholder="password" aria-describedby="passwordFeedback" required>
                    <div id="passwordFeedback" class="invalid-feedback">
                        <?php echo $errors['password'] ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <div class="form-check form-check-inline">
                    Login as
                </div>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radio" id="radio1" value="user" <?php echo $loginAs === 'user' ? 'checked' : null ?> required>
                        <label class="form-check-label" for="radio1">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radio" id="radio2" value="hospital" <?php echo $loginAs === 'hospital' ? 'checked' : null ?>>
                        <label class="form-check-label" for="radio2">Hospital</label>
                    </div>
                </div>
            </div>


            <div class="d-flex justify-content-center m-3">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>

            <div class="d-flex">
                <p><a href="register/user.php" class="text-primary">Register as a User ?</a></p>
                <p class="ml-auto"><a href="register/hospital.php" class="text-primary">Register as a Hospital ?</a></p>
            </div>


        </form>
    </div>


    <?php include 'templates/footer.php' ?>

</html>