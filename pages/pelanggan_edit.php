<?php include('../includes/db_connect.php'); ?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM pelanggan WHERE CustomerID = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FullName = $_POST['FullName'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];

    $sql = "UPDATE pelanggan SET FullName='$FullName', PhoneNumber='$PhoneNumber', Email='$Email', Address='$Address' WHERE CustomerID=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: pelanggan_list.php");
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
    <h1>Edit Pelanggan</h1>
    <form method="POST">
      <input type="text" name="FullName" value="<?php echo $row['FullName']; ?>" required>
      <input type="text" name="PhoneNumber" value="<?php echo $row['PhoneNumber']; ?>" required>
      <input type="email" name="Email" value="<?php echo $row['Email']; ?>" required>
      <textarea name="Address"><?php echo $row['Address']; ?></textarea>
      <button type="submit">Update</button>
    </form>
  </main>
</body>
</html>
