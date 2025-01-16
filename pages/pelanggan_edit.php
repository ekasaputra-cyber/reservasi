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
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Edit Pelanggan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a href="pelanggan_list.php">Pelanggan</a></li>
          <li class="breadcrumb-item active">Edit Pelanggan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Data Pelanggan</h5>
              <p>Silakan ubah data pelanggan yang ingin diperbarui.</p>
              
              <!-- Form Edit Data Pelanggan -->
              <form method="POST">
                <div class="mb-3">
                  <label for="FullName" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="FullName" name="FullName" value="<?php echo $row['FullName']; ?>" required>
                </div>

                <div class="mb-3">
                  <label for="PhoneNumber" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="PhoneNumber" name="PhoneNumber" value="<?php echo $row['PhoneNumber']; ?>" required>
                </div>

                <div class="mb-3">
                  <label for="Email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="Email" name="Email" value="<?php echo $row['Email']; ?>" required>
                </div>

                <div class="mb-3">
                  <label for="Address" class="form-label">Address</label>
                  <textarea class="form-control" id="Address" name="Address" rows="3"><?php echo $row['Address']; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="pelanggan_list.php" class="btn btn-secondary">Cancel</a>
              </form>
              <!-- End Form Edit Data Pelanggan -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>
</body>
</html>
