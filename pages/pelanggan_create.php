<?php include('../includes/db_connect.php'); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FullName = $_POST['FullName'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];

    $sql = "INSERT INTO pelanggan (FullName, PhoneNumber, Email, Address) VALUES ('$FullName', '$PhoneNumber', '$Email', '$Address')";
    if ($conn->query($sql) === TRUE) {
        header("Location: pelanggan_list.php"); // Redirect ke halaman daftar pelanggan
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
      <h1>Tambah Pelanggan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Forms</li>
          <li class="breadcrumb-item active">Tambah Pelanggan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form Tambah Pelanggan</h5>

              <!-- Form Tambah Pelanggan -->
              <form method="POST">
                <div class="row mb-3">
                  <label for="FullName" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="FullName" id="FullName" placeholder="Full Name" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="PhoneNumber" class="col-sm-2 col-form-label">Phone Number</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" placeholder="Phone Number" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Email" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Address" class="col-sm-2 col-form-label">Address</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="Address" id="Address" placeholder="Address" required></textarea>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
              <!-- End Form Tambah Pelanggan -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>
</body>
</html>
