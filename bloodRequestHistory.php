<?php

session_start();

include 'config/db_connect.php';

if (!isset($_SESSION['USER'])) {
    header('Location:/bloodbank/login.php');
}

$bloodGroupArray = array('1' => 'O+', '2' => 'O-', '3' => 'A+', '4' => 'A-', '5' => 'B+', '6' => 'B-', '7' => 'AB+', '8' => 'AB-');

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'templates/header.php' ?>

<body>
    <div class="container">
        <table class="table table-bordered table-hover table-light">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hospital Name</th>
                    <th scope="col">Blood Group</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * from blood_request where UserId = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['USER']['UserId']]);

                $rows = $stmt->fetchAll();

                $count = 0;
                foreach ($rows as $row) {

                    $sql = "SELECT HospitalName from hospitals where HospitalId = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$row['HospitalId']]);

                    $hospital = $stmt->fetch();
                    $count++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $count ?></th>
                        <td><?php echo $hospital['HospitalName'] ?> </td>
                        <td><?php echo $bloodGroupArray[$row['BloodGroup']] ?> </td>
                        <td><?php echo $row['Quantity'] ?></td>
                        <td><?php echo $row['Status'] ?></td>
                    </tr>

                <?php }

                if ($count == 0) { ?>
                    <tr>
                        <td class="text-secondary" colspan="7"> No Data Available </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>



</body>

</html>