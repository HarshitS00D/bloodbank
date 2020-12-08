<!DOCTYPE html>
<html lang="en">

<?php

session_start();

if (!isset($_SESSION['HOSPITAL'])) {
    header('Location:/bloodbank/login.php');
}

include 'config/db_connect.php';
include 'templates/header.php';


function showToast()
{
    echo    "<script>
                $(document).ready(function() {
                    $('.toast').toast('show'); 
                    });
                </script>";
}
$bloodGroupArray = array('1' => 'O+', '2' => 'O-', '3' => 'A+', '4' => 'A-', '5' => 'B+', '6' => 'B-', '7' => 'AB+', '8' => 'AB-');
$bloodGroup = $quantity = '';
$errors = array('quantity' => '');

if (isset($_POST['submit'])) {
    $bloodGroup = $_POST['bloodGroup'];
    $quantity = $_POST['quantity'];

    if ($quantity < 1) {
        $errors['quantity'] = 'Quantity cannot be less than 1';
    } else { // no errors

        $hospitalId = $_SESSION['HOSPITAL']['HospitalId'];
        try {
            $sql = "SELECT * from blood_info WHERE HospitalId = ? and BloodGroup = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hospitalId, $bloodGroup]);

            $rowCount = $stmt->rowCount();

            if ($rowCount == 0) {
                $sql = "INSERT INTO blood_info(HospitalId,BloodGroup,Quantity) VALUES(?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hospitalId, $bloodGroup, $quantity]);

                showToast();
            } else {
                $record = $stmt->fetch();

                $newQuantity = $record['Quantity'] + $quantity;

                $sql = "UPDATE blood_info SET Quantity = ? where HospitalId = ? and BloodGroup = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$newQuantity, $hospitalId, $bloodGroup]);

                showToast();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}


?>
<link rel="stylesheet" href="css/addBlood-style.css" />


<body>
    <div style="width:100%; height:80%; position:relative">
        <div class="container d-flex justify-content-center">

            <form class="rounded bg-light text-secondary" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="d-flex justify-content-center p-3">
                    <h3 class="text-secondary">Add Blood</h3>
                </div>

                <div class="form-group">
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

                <div class="form-group">
                    <label for="inputQuantity"> Quantity (in bottles) </label>
                    <input type="number" class="form-control <?php echo $errors['quantity'] == '' ? null : 'is-invalid' ?>" id="inputQuantity" name="quantity" value="<?php echo $quantity ?>" aria-describedby="quantityFeedback" required>
                    <div id="quantityFeedback" class="invalid-feedback">
                        <?php echo $errors['quantity'] ?>
                    </div>
                </div>

                <div class="d-flex justify-content-center m-3">
                    <button type="submit" class="btn btn-primary" name="submit">Add</button>
                </div>

            </form>

        </div>

        <div class="toast" data-delay="3000" style="position: absolute; top: 0; right: 40px;">
            <div class="toast-header">
                <strong class="mr-auto text-danger">Message</strong>
            </div>
            <div class="toast-body">
                <span class="text-danger font-weight-bold"> <?php echo $bloodGroupArray[$bloodGroup] . " " ?> </span>
                Blood of Quantity <?php echo " " . $quantity . " " ?> added Succesfully!
            </div>
        </div>

    </div>

    <?php include 'templates/footer.php' ?>

</html>