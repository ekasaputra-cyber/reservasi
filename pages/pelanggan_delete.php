<?php include('../includes/db_connect.php'); ?>
<?php
$id = $_GET['id'];
$sql = "DELETE FROM pelanggan WHERE CustomerID = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: pelanggan_list.php");
} else {
    echo "Error: " . $conn->error;
}
?>
