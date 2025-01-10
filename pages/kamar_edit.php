<?php include('../includes/db_connect.php'); ?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM kamar WHERE RoomID = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RoomNumber = $_POST['RoomNumber'];
    $RoomTypeID = $_POST['RoomTypeID'];
    $Price = $_POST['Price'];
    $Status = $_POST['Status'];

    $sql = "UPDATE kamar SET RoomNumber='$RoomNumber', RoomTypeID='$RoomTypeID', Price='$Price', Status='$Status' WHERE RoomID=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: kamar_list.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main>
    <h1>Edit Kamar</h1>
    <form method="POST">
      <input type="text" name="RoomNumber" value="<?php echo $row['RoomNumber']; ?>" required>
      <input type="text" name="RoomTypeID" value="<?php echo $row['RoomTypeID']; ?>" required>
      <input type="text" name="Price" value="<?php echo $row['Price']; ?>" required>
      <select name="Status">
        <option value="Available" <?php echo $row['Status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
        <option value="Occupied" <?php echo $row['Status'] == 'Occupied' ? 'selected' : ''; ?>>Occupied</option>
        <option value="Maintenance" <?php echo $row['Status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
      </select>
      <button type="submit">Update</button>
    </form>
  </main>
</body>
</html>
