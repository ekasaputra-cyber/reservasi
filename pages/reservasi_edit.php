<?php 
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Query untuk mendapatkan data reservasi berdasarkan ID
    $sql = "SELECT r.ReservationID, p.FullName, k.RoomNumber, r.CheckInDate, r.CheckOutDate, r.Status
            FROM reservasi r
            JOIN pelanggan p ON r.CustomerID = p.CustomerID
            JOIN kamar k ON r.RoomID = k.RoomID
            WHERE r.ReservationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    if (!$reservation) {
        echo "Data tidak ditemukan!";
        exit;
    }

    // Proses form submission untuk update data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $customer_name = $_POST['customer_name'];
        $room_number = $_POST['room_number'];
        $checkin_date = $_POST['checkin_date'];
        $checkout_date = $_POST['checkout_date'];
        $status = $_POST['status'];

        // Query untuk update data
        $update_sql = "UPDATE reservasi 
                       SET CustomerID = (SELECT CustomerID FROM pelanggan WHERE FullName = ?), 
                           RoomID = (SELECT RoomID FROM kamar WHERE RoomNumber = ?),
                           CheckInDate = ?, CheckOutDate = ?, Status = ? 
                       WHERE ReservationID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $customer_name, $room_number, $checkin_date, $checkout_date, $status, $reservation_id);
        
        if ($update_stmt->execute()) {
            echo "Data berhasil diperbarui!";
            header("Location: reservasi.php");
            exit;
        } else {
            echo "Terjadi kesalahan saat memperbarui data.";
        }
    }
} else {
    echo "ID reservasi tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Edit Reservasi</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Data Reservasi</h5>
              
              <form method="POST">
                <div class="form-group">
                  <label for="customer_name">Nama Pelanggan</label>
                  <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $reservation['FullName']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="room_number">Nomor Kamar</label>
                  <input type="text" class="form-control" id="room_number" name="room_number" value="<?php echo $reservation['RoomNumber']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="checkin_date">Tanggal Check-In</label>
                  <input type="date" class="form-control" id="checkin_date" name="checkin_date" value="<?php echo $reservation['CheckInDate']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="checkout_date">Tanggal Check-Out</label>
                  <input type="date" class="form-control" id="checkout_date" name="checkout_date" value="<?php echo $reservation['CheckOutDate']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="status">Status</label>
                  <select class="form-control" id="status" name="status" required>
                    <option value="Confirmed" <?php if ($reservation['Status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                    <option value="Pending" <?php if ($reservation['Status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Cancelled" <?php if ($reservation['Status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update Reservasi</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <?php include('../includes/footer.php'); ?>
</body>
</html>
