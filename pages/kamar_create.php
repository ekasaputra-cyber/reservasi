<?php
include('../includes/db_connect.php');

// Ambil daftar room types untuk dropdown
$sqlRoomTypes = "SELECT RoomTypeID, RoomType, DefaultPrice FROM roomtype";
$resultRoomTypes = $conn->query($sqlRoomTypes);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RoomNumber = $_POST['RoomNumber'];
    $RoomTypeID = $_POST['RoomTypeID'];
    $Price = $_POST['Price']; // Harga akan diambil dari DefaultPrice
    $Status = $_POST['Status'];

    // Insert kamar
    $sql = "INSERT INTO kamar (RoomNumber, RoomTypeID, Price, Status) VALUES ('$RoomNumber', '$RoomTypeID', '$Price', '$Status')";
    if ($conn->query($sql) === TRUE) {
        header("Location: kamar_list.php"); // Redirect ke daftar kamar
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
      <h1>Tambah Kamar</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form Tambah Kamar</h5>

              <!-- Form Tambah Kamar -->
              <form method="POST">
                <div class="row mb-3">
                  <label for="RoomNumber" class="col-sm-2 col-form-label">Room Number</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="RoomNumber" id="RoomNumber" placeholder="Room Number" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="RoomTypeID" class="col-sm-2 col-form-label">Room Type</label>
                  <div class="col-sm-10">
                    <select name="RoomTypeID" id="RoomTypeID" class="form-control" required>
                      <option value="">Select Room Type</option>
                      <?php
                      while ($row = $resultRoomTypes->fetch_assoc()) {
                          echo "<option value='{$row['RoomTypeID']}' data-price='{$row['DefaultPrice']}'>{$row['RoomType']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Price" class="col-sm-2 col-form-label">Price</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="Price" id="Price" placeholder="Price" required readonly>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Status" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                    <select name="Status" id="Status" class="form-control" required>
                      <option value="Available">Available</option>
                      <option value="Occupied">Occupied</option>
                      <option value="Maintenance">Maintenance</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
              <!-- End Form Tambah Kamar -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    document.getElementById('RoomTypeID').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        document.getElementById('Price').value = price;
    });
  </script>

  <?php include('../includes/footer.php'); ?>
</body>
</html>
