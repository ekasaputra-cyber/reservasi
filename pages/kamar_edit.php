<?php include('../includes/db_connect.php'); ?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM kamar WHERE RoomID = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Ambil data RoomType berdasarkan RoomTypeID
$sqlRoomType = "SELECT * FROM roomtype WHERE RoomTypeID = " . $row['RoomTypeID'];
$resultRoomType = $conn->query($sqlRoomType);
$roomType = $resultRoomType->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RoomTypeID = $_POST['RoomTypeID'];
    $Status = $_POST['Status'];

    // Update harga kamar otomatis berdasarkan RoomType yang dipilih
    $sqlRoomType = "SELECT * FROM roomtype WHERE RoomTypeID = $RoomTypeID";
    $resultRoomType = $conn->query($sqlRoomType);
    $selectedRoomType = $resultRoomType->fetch_assoc();
    $Price = $selectedRoomType['DefaultPrice'];

    // Update status dan RoomType, harga mengikuti RoomType
    if ($row['Status'] != 'Occupied') {
        $sql = "UPDATE kamar SET RoomTypeID='$RoomTypeID', Price='$Price', Status='$Status' WHERE RoomID=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: kamar_list.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Edit Kamar</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item"><a href="kamar_list.php">Kamar</a></li>
          <li class="breadcrumb-item active">Edit Kamar</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Data Kamar</h5>
              <p>Silakan ubah status kamar yang ingin diperbarui.</p>
              
              <!-- Form Edit Data Kamar -->
              <form method="POST">
                <div class="mb-3">
                  <label for="RoomNumber" class="form-label">Room Number</label>
                  <input type="text" class="form-control" id="RoomNumber" name="RoomNumber" value="<?php echo $row['RoomNumber']; ?>" disabled>
                  <small class="form-text text-muted">Room Number tidak dapat diubah.</small>
                </div>

                <div class="mb-3">
                  <label for="RoomType" class="form-label">Room Type</label>
                  <select class="form-select" id="RoomType" name="RoomTypeID" required>
                    <?php
                    // Menampilkan semua tipe kamar dari tabel roomtype
                    $sqlRoomTypes = "SELECT * FROM roomtype";
                    $resultRoomTypes = $conn->query($sqlRoomTypes);
                    while ($type = $resultRoomTypes->fetch_assoc()) {
                        echo "<option value='" . $type['RoomTypeID'] . "' " . ($row['RoomTypeID'] == $type['RoomTypeID'] ? 'selected' : '') . ">" . $type['RoomType'] . "</option>";
                    }
                    ?>
                  </select>
                  <small class="form-text text-muted">Pilih tipe kamar yang sesuai.</small>
                </div>

                <div class="mb-3">
                  <label for="Price" class="form-label">Price</label>
                  <input type="text" class="form-control" id="Price" name="Price" value="<?php echo $roomType['DefaultPrice']; ?>" disabled>
                  <small class="form-text text-muted">Harga kamar mengikuti tipe kamar yang dipilih.</small>
                </div>

                <div class="mb-3">
                  <label for="Status" class="form-label">Status</label>
                  <select class="form-select" id="Status" name="Status" <?php echo ($row['Status'] == 'Occupied' ? 'disabled' : ''); ?> required>
                    <option value="Available" <?php echo $row['Status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                    <option value="Occupied" <?php echo $row['Status'] == 'Occupied' ? 'selected' : ''; ?>>Occupied</option>
                    <option value="Maintenance" <?php echo $row['Status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                  </select>
                  <?php if ($row['Status'] == 'Occupied'): ?>
                    <small class="form-text text-muted">Status kamar tidak dapat diubah karena sedang Occupied.</small>
                  <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" <?php echo ($row['Status'] == 'Occupied' ? 'disabled' : ''); ?>>Update</button>
                <a href="kamar_list.php" class="btn btn-secondary">Cancel</a>
              </form>
              <!-- End Form Edit Data Kamar -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>
</body>
</html>
