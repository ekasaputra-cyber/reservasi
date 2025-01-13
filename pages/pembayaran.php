<?php
include('../includes/db_connect.php');

// Proses pembayaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['payment_amount']) && isset($_POST['payment_method'])) {
    $reservation_id = $_POST['reservation_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];

    // Query untuk mengambil data reservasi berdasarkan ID
    $sql = "SELECT * FROM reservasi WHERE ReservationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Menyimpan data pembayaran ke tabel pembayaran
        $payment_sql = "INSERT INTO pembayaran (ReservationID, Amount, PaymentMethod) VALUES (?, ?, ?)";
        $payment_stmt = $conn->prepare($payment_sql);
        $payment_stmt->bind_param("ids", $reservation_id, $payment_amount, $payment_method);
        $payment_stmt->execute();

        // Mengubah status reservasi menjadi 'Confirmed' setelah pembayaran
        $update_sql = "UPDATE reservasi SET Status = 'Confirmed' WHERE ReservationID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $reservation_id);
        $update_stmt->execute();

        echo "Pembayaran berhasil! Status reservasi telah diperbarui menjadi 'Confirmed'.";
    } else {
        echo "Reservasi tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Pembayaran Reservasi</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pembayaran Reservasi</h5>
              
              <form method="POST">
                <!-- Pilih ID Reservasi dari dropdown -->
                <div class="form-group">
                  <label for="reservation_id">Pilih ID Reservasi</label>
                  <select class="form-control" id="reservation_id" name="reservation_id" onchange="fetchReservationDetails()" required>
                    <option value="">Pilih Reservasi</option>
                    <?php
                    // Query untuk mengambil daftar reservasi yang masih Pending
                    $query = "SELECT ReservationID FROM reservasi WHERE Status = 'Pending'";
                    $result = $conn->query($query);

                    // Tampilkan semua reservasi yang statusnya 'Pending'
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ReservationID']}'>Reservasi ID: {$row['ReservationID']}</option>";
                        }
                    } else {
                        echo "<option value=''>Tidak ada reservasi Pending</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Menampilkan detail reservasi -->
                <div id="reservation_details"></div>

                <!-- Input jumlah pembayaran -->
                <div class="form-group">
                  <label for="payment_amount">Jumlah Pembayaran</label>
                  <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
                </div>

                <!-- Pilih metode pembayaran -->
                <div class="form-group">
                  <label for="payment_method">Metode Pembayaran</label>
                  <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="CreditCard">Kartu Kredit</option>
                    <option value="BankTransfer">Transfer Bank</option>
                    <option value="EWallet">E-Wallet</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Proses Pembayaran</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>

  <script>
    function fetchReservationDetails() {
      var reservation_id = document.getElementById('reservation_id').value;

      if (reservation_id) {
        // Gunakan AJAX untuk mengambil detail reservasi berdasarkan ID
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_reservation_details.php?reservation_id=' + reservation_id, true);
        xhr.onload = function () {
          if (xhr.status == 200) {
            document.getElementById('reservation_details').innerHTML = xhr.responseText;
          }
        };
        xhr.send();
      } else {
        document.getElementById('reservation_details').innerHTML = '';
      }
    }
  </script>
</body>
</html>
