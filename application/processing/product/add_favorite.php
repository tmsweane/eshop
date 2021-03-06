<?php
session_start();
if(!isset($_SESSION['isUserCorrect'])){
    $_SESSION['isUserCorrect'] = false;
}

if ($_SESSION['isUserCorrect']) {
    echo json_encode("login_error");
    exit();
}

if ($_POST['ProductID'] <= 0 OR !is_int($_POST['ProductID'])) {
    echo json_encode(false);
    exit();
}

require('../../core/model.php');
try {
    $tmp_conn = new mysqli(Model::$host, Model::$username, Model::$password, Model::$dbname);
} catch (Exception $e) {
    echo json_encode("{error: connection_error},{error_msg: $tmp_conn->connect_error }");
    exit();
}

$favorite_prod_id = $tmp_conn->real_escape_string($_POST['ProductID']);
$user_id = $tmp_conn->real_escape_string($_POST['UserID']);

$stmt = $tmp_conn->prepare("INSERT INTO Favorite (ID_product_FK, ID_user_FK) VALUES (?,?)");
$stmt->bind_param('ii', $favorite_prod_id, $user_id);
if ($stmt->execute()) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}

$stmt->close();
$tmp_conn->close();
