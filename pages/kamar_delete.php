<?php include('../includes/db_connect.php'); ?>
<?php
$id = $_GET['id'];
$sql = "DELETE FROM kamar WHERE RoomID = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: kamar_list.php");
} else {
    echo "Error: " . $conn->error;
}
?>
