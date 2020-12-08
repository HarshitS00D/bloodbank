<?php

session_start();

include 'config/db_connect.php';

$bloodGroupArray = array('1' => 'O+', '2' => 'O-', '3' => 'A+', '4' => 'A-', '5' => 'B+', '6' => 'B-', '7' => 'AB+', '8' => 'AB-');

$eligibleBloodGroupArray = array( // to find out all blood group with their eligible blood groups. array index denotes bloog group id.
    array(true, true, false, false, false, false, false, false), // 1
    array(false, true, false, false, false, false, false, false), // 2
    array(true, true, true, true, false, false, false, false),   //3
    array(false, true, false, true, false, false, false, false), //4
    array(true, true, false, false, true, true, false, false),   //5
    array(false, true, false, false, false, true, false, false), //6
    array(true, true, true, true, true, true, true, true),       //7
    array(false, true, false, true, false, true, false, true)    //8
);


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'templates/header.php' ?>
<script>
    var HospitalId, BloodGroup, Quantity, TotalQuantity;

    function onRequestSubmit() {
        let inputQuantity = document.getElementById('inputQuantity');
        Quantity = parseInt(inputQuantity.value);

        if (Quantity < 1 || Quantity > TotalQuantity || isNaN(Quantity)) {
            inputQuantity.classList.add("is-invalid");
        } else {
            inputQuantity.classList.remove("is-invalid");

            <?php
            if ($loggedInAsUser) {
            ?>
                axios.post('/handleBloodRequest.php', {
                        UserId: <?php echo $_SESSION['USER']['UserId'] ?>,
                        HospitalId,
                        BloodGroup,
                        Quantity
                    })
                    .then(function(response) {
                        document.getElementById('successAlert').classList.remove('d-none');
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

            <?php
            }
            ?>

        }
    }

    function onRequestClose() {
        document.getElementById('inputQuantity').classList.remove("is-invalid");
        document.getElementById('successAlert').classList.add('d-none');
    }

    function requestSample(hospitalId, bloodGroup, totalQuantity) {
        if (<?php echo $loggedInAsUser ? 'true' : 'false' ?>) {

            HospitalId = hospitalId;
            BloodGroup = bloodGroup;
            TotalQuantity = totalQuantity;

            $('#staticBackdrop').modal('toggle');

        } else {
            window.location.href = 'login.php';
        }
    }
</script>

<body>
    <div class="container">

        <div class="alert alert-secondary" role="alert">
            <p class="alert-heading font-weight-bold">Note</p>
            <hr>
            You can only request the Blood Sample for which you are eligible.

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="col">Recipient Blood Type</th>
                        <th scope="col">Matching Donor Blood Type</th>
                    </tr>
                    <tr>
                        <td>A+ </td>
                        <td>A+, A-, O+, O-</td>
                    </tr>
                    <tr>
                        <td>A-</td>
                        <td>A-, O-</td>
                    </tr>
                    <tr>
                        <td>B+ </td>
                        <td>B+, B-, O+, O-</td>
                    </tr>
                    <tr>
                        <td>B-</td>
                        <td>B-, O-</td>
                    </tr>
                    <tr>
                        <td>AB+</td>
                        <td>Compatible with all blood types</td>
                    </tr>
                    <tr>
                        <td>AB-</td>
                        <td>AB-, A-, B-, O-</td>
                    </tr>
                    <tr>
                        <td>O+</td>
                        <td>O+, O-</td>
                    </tr>
                    <tr>
                        <td>O-</td>
                        <td>O-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="table table-bordered table-hover table-light ">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hospital Name</th>
                    <th scope="col">Blood Group</th>
                    <th scope="col">Quantity (in bottles)</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Request</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * from blood_info";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();

                $count = 0;
                foreach ($rows as $row) {
                    if ($row['Quantity'] < 1)
                        continue;

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
                        <td><?php echo $row['Updated_At'] ?></td>

                        <?php
                        $disabled = false;
                        if ($loggedInAsUser) {
                            if (!$eligibleBloodGroupArray[$_SESSION['USER']['BloodGroup'] - 1][$row['BloodGroup'] - 1]) {
                                $disabled = true;
                            }
                        } else if ($loggedInAsHospital) {
                            $disabled = true;
                        }
                        ?>
                        <td><button onclick="requestSample(<?php echo $row['HospitalId'] ?>,<?php echo $row['BloodGroup'] ?>,<?php echo $row['Quantity'] ?>)" class="btn btn-block btn-primary <?php echo $disabled ? 'disabled' : null ?>" <?php echo $disabled ? 'disabled' : null ?>> Request Sample </button> </td>
                    </tr>

                <?php }

                if ($count == 0) { ?>
                    <tr>
                        <td class="text-secondary" colspan="7"> No Blood Available</td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>


        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Request Sample</h5>
                        <button onclick="onRequestClose()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputQuantity">Enter Quantity (in bottles) </label>
                            <input type="number" class="form-control" id="inputQuantity" name="quantity" aria-describedby="quantityFeedback" required>
                            <div id="quantityFeedback" class="invalid-feedback">
                                Quantity cannot be less than 1 or greater than total quantity.
                            </div>
                            <div class="alert alert-success d-none" id="successAlert" role="alert">
                                Request sent successfully!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="onRequestClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="onRequestSubmit()" class="btn btn-primary">Request</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include 'templates/footer.php' ?>

</html>