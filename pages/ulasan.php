<?php
include('../includes/db_connect.php');

// Proses pengiriman ulasan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $reservation_id = $_POST['reservation_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Query untuk menyimpan ulasan ke tabel ulasan
    $sql = "INSERT INTO ulasan (ReservationID, Rating, Comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $reservation_id, $rating, $comment);
    if ($stmt->execute()) {
        echo "Ulasan berhasil disimpan!";
    } else {
        echo "Terjadi kesalahan saat menyimpan ulasan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>

<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Halaman Ulasan</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Beri Ulasan untuk Reservasi Anda</h5>

              <form method="POST">
                <!-- Pilih ID Reservasi dari dropdown -->
                <div class="form-group">
                  <label for="reservation_id">Pilih ID Reservasi</label>
                  <select class="form-control" id="reservation_id" name="reservation_id" required>
                    <option value="">Pilih Reservasi</option>
                    <?php
                    // Query untuk mengambil daftar reservasi yang sudah dikonfirmasi
                    $query = "SELECT ReservationID FROM reservasi WHERE Status = 'Confirmed'";
                    $result = $conn->query($query);

                    // Tampilkan semua reservasi yang sudah dikonfirmasi
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ReservationID']}'>Reservasi ID: {$row['ReservationID']}</option>";
                        }
                    } else {
                        echo "<option value=''>Tidak ada reservasi yang dikonfirmasi</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Input Rating (1-5) -->
                <div class="form-group">
                  <label for="rating">Rating (1-5)</label>
                  <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                </div>

                <!-- Input Komentar -->
                <div class="form-group">
                  <label for="comment">Komentar</label>
                  <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Kirim Ulasan</button>
              </form>

              <!-- Tampilkan Ulasan yang sudah ada -->
              <h5 class="mt-5">Ulasan Sebelumnya</h5>
              <div class="ulasan-list">
                <?php
                // Query untuk mengambil ulasan berdasarkan reservasi yang sudah ada
                $ulasan_query = "SELECT u.ReviewID, u.Rating, u.Comment, u.ReviewDate, r.ReservationID 
                                 FROM ulasan u
                                 JOIN reservasi r ON u.ReservationID = r.ReservationID
                                 WHERE r.Status = 'Confirmed'
                                 ORDER BY u.ReviewDate DESC";
                $ulasan_result = $conn->query($ulasan_query);

                if ($ulasan_result->num_rows > 0) {
                    while ($ulasan = $ulasan_result->fetch_assoc()) {
                        echo "<div class='ulasan-item'>
                                <p><strong>Reservasi ID: {$ulasan['ReservationID']}</strong></p>
                                <p>Rating: {$ulasan['Rating']}</p>
                                <p>Ulasan: {$ulasan['Comment']}</p>
                                <p><small>Ditulis pada: {$ulasan['ReviewDate']}</small></p>
                              </div><hr>";
                    }
                } else {
                    echo "<p>Belum ada ulasan untuk reservasi ini.</p>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>

</body>
</html>
