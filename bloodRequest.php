<?php

session_start();

if (!isset($_SESSION['HOSPITAL'])) {
    header('Location:/bloodbank/login.php');
}

include 'config/db_connect.php';

$bloodGroupArray = array('1' => 'O+', '2' => 'O-', '3' => 'A+', '4' => 'A-', '5' => 'B+', '6' => 'B-', '7' => 'AB+', '8' => 'AB-');

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'templates/header.php' ?>

<script>
    function approveBloodRequest(RequestId, HospitalId, BloodGroup, Quantity) {

        axios.post('/bloodbank/handleBloodRequest.php', {
                Approve: 'Approve',
                RequestId,
                HospitalId,
                BloodGroup,
                Quantity
            })
            .then(function(response) {
                if (response.data.message) {
                    location.reload();
                } else if (response.data.error) {
                    alert(response.data.error);
                }

            })
            .catch(function(response) {
                console.log(response);
            });
    }

    function declineBloodRequest(RequestId) {

        axios.post('/bloodbank/handleBloodRequest.php', {
                Decline: 'Decline',
                RequestId
            })
            .then(function(response) {
                if (response.data.message) {
                    location.reload();
                }
            })
            .catch(function(response) {
                console.log(response);
            });
    }
</script>

<body>
    <div class="container">
        <table class="table table-bordered table-hover table-light">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Receiver's Name</th>
                    <th scope="col">Receiver's Phone Number</th>
                    <th scope="col">Receiver's Email</th>
                    <th scope="col">Blood Group Requested</th>
                    <th scope="col">Quantity (in bottles)</th>
                    <th scope="col">Approve / Decline </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * from blood_request";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();

                $count = 0;
                foreach ($rows as $row) {

                    if ($row['Status'] != 'pending')
                        continue;

                    $sql = "SELECT FirstName, LastName, PhoneNumber, Email from users where UserId = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$row['UserId']]);

                    $user = $stmt->fetch();
                    $count++;
                ?>
                    <tr>
                        <th scope="row"><?php echo $count ?></th>
                        <td><?php echo $user['FirstName'] . " " . $user['LastName'] ?> </td>
                        <td><?php echo $user['PhoneNumber'] ?></td>
                        <td><?php echo $user['Email'] ?></td>
                        <td><?php echo $bloodGroupArray[$row['BloodGroup']] ?> </td>
                        <td><?php echo $row['Quantity'] ?></td>
                        <td>
                            <button onclick="approveBloodRequest(<?php echo $row['RequestId'] . ',' . $row['HospitalId'] . ',' . $row['BloodGroup'] . ',' . $row['Quantity'] ?>)" type="button" class="btn btn-primary">Approve</button>
                            <button onclick="declineBloodRequest(<?php echo $row['RequestId'] ?>)" type="button" class="btn btn-secondary"><strong>X</strong></button>
                        </td>
                    </tr>

                <?php }

                if ($count == 0) { ?>
                    <tr>
                        <td class="text-secondary" colspan="7"> No Request </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>

    </div>



    <?php include 'templates/footer.php' ?>

</html>