<?php

include 'config/db_connect.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['UserId'])) {
    try {
        $sql = "INSERT INTO blood_request(UserId,HospitalId,BloodGroup,Quantity) VALUES(?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['UserId'], $_POST['HospitalId'], $_POST['BloodGroup'], $_POST['Quantity']]);
        echo "Request Added!";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['Approve'])) {
    try {
        $RequestId = $_POST['RequestId'];
        $HospitalId = $_POST['HospitalId'];
        $BloodGroup = $_POST['BloodGroup'];
        $Quantity = $_POST['Quantity'];

        $sql = "SELECT Quantity from blood_info where HospitalId = ? and BloodGroup = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$HospitalId, $BloodGroup]);

        $totalQuantity = $stmt->fetch()['Quantity'];
        $newQuantity = $totalQuantity - $Quantity;

        if ($newQuantity < 0) {
            echo  json_encode(array('error' => 'Quantity exceeds total quantity.'));
        } else {
            $sql = "UPDATE blood_info SET Quantity = ? WHERE HospitalId = ? and BloodGroup = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newQuantity, $HospitalId, $BloodGroup]);

            $sql = "UPDATE blood_request SET Status = ? WHERE RequestId = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['approved', $RequestId]);

            echo json_encode(array('message' => 'Request Approved Successfully'));
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['Decline'])) {
    try {
        $RequestId = $_POST['RequestId'];

        $sql = "UPDATE blood_request SET Status = ? WHERE RequestId = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['declined', $RequestId]);

        echo json_encode(array('message' => 'Request Declined Succesfully'));
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
