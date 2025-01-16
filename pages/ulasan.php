<?php
include('../includes/db_connect.php');

// Proses pengiriman ulasan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $reservation_id = $_POST['reservation_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Periksa apakah reservasi sudah memiliki ulasan
    $check_review_query = "SELECT * FROM ulasan WHERE ReservationID = ?";
    $check_stmt = $conn->prepare($check_review_query);
    $check_stmt->bind_param("i", $reservation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "Anda sudah memberikan ulasan untuk reservasi ini.";
    } else {
        // Query untuk menyimpan ulasan ke tabel ulasan
        $sql = "INSERT INTO ulasan (ReservationID, Rating, Comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $reservation_id, $rating, $comment);
        if ($stmt->execute()) {
            // Setelah ulasan berhasil disimpan, ubah status reservasi menjadi 'Selesai'
            $update_status_query = "UPDATE reservasi SET Status = 'Selesai' WHERE ReservationID = ?";
            $update_stmt = $conn->prepare($update_status_query);
            $update_stmt->bind_param("i", $reservation_id);
            $update_stmt->execute();

            // Mengambil RoomID dari reservasi terkait
            $get_room_id_query = "SELECT RoomID FROM reservasi WHERE ReservationID = ?";
            $room_stmt = $conn->prepare($get_room_id_query);
            $room_stmt->bind_param("i", $reservation_id);
            $room_stmt->execute();
            $room_result = $room_stmt->get_result();
            $room_row = $room_result->fetch_assoc();
            $room_id = $room_row['RoomID'];

            // Mengubah status kamar menjadi 'Maintenance'
            $update_room_status_query = "UPDATE kamar SET Status = 'Maintenance' WHERE RoomID = ?";
            $update_room_stmt = $conn->prepare($update_room_status_query);
            $update_room_stmt->bind_param("i", $room_id);
            $update_room_stmt->execute();

            echo "Ulasan berhasil disimpan dan status reservasi telah diubah menjadi 'Selesai', status kamar diubah menjadi 'Maintenance'.";
        } else {
            echo "Terjadi kesalahan saat menyimpan ulasan.";
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
                    // Query untuk mengambil daftar reservasi yang sudah check-out dan belum memiliki ulasan
                    $query = "SELECT r.ReservationID 
                              FROM reservasi r
                              LEFT JOIN ulasan u ON r.ReservationID = u.ReservationID
                              WHERE r.Status = 'CheckOut' AND u.ReservationID IS NULL";
                    $result = $conn->query($query);

                    // Tampilkan semua reservasi yang sudah check-out dan belum memiliki ulasan
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ReservationID']}'>Reservasi ID: {$row['ReservationID']}</option>";
                        }
                    } else {
                        echo "<option value=''>Tidak ada reservasi yang dapat memberikan ulasan</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Input Rating (1-5) -->
                <div class="form-group">
                  <label for="rating">Rating (1-5)</label>
                  <div class="stars d-flex">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                  </div>
                  <input type="hidden" id="rating" name="rating" required>
                </div>
                <style>
                  .stars {
                    cursor: pointer;
                  }
                  .star {
                    font-size: 2rem; /* Ukuran bintang */
                    color: #ddd; /* Warna bintang saat tidak dipilih */
                    margin-right: 5px;
                  }
                  .star:hover,
                  .star.selected {
                    color: gold; /* Warna bintang yang dipilih */
                  }
                </style>

                <!-- Input Komentar -->
                <div class="form-group">
                  <label for="comment">Komentar</label>
                  <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Kirim Ulasan</button>
              </form>

              <h5 class="mt-5">Ulasan Sebelumnya</h5>
              <div class="ulasan-list">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Reservasi ID</th>
                      <th>Nama Pelanggan</th>
                      <th>Nomor Kamar</th>
                      <th>Jenis Kamar</th>
                      <th>Tanggal Cekin</th>
                      <th>Tanggal Cek Out</th>
                      <th>Rating</th>
                      <th>Ulasan</th>
                      <th>Tanggal Ulasan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Query untuk mengambil ulasan beserta informasi reservasi, pelanggan, dan kamar
                    $ulasan_query = "
                      SELECT u.ReviewID, u.Rating, u.Comment, u.ReviewDate, r.ReservationID, 
                            p.FullName AS CustomerName, k.RoomNumber, rt.RoomType, r.CheckInDate, r.CheckOutDate
                      FROM ulasan u
                      JOIN reservasi r ON u.ReservationID = r.ReservationID
                      JOIN kamar k ON r.RoomID = k.RoomID 
                      JOIN roomtype rt ON k.RoomTypeID = rt.RoomTypeID 
                      JOIN pelanggan p ON r.CustomerID = p.CustomerID
                      WHERE r.Status = 'Selesai'
                      ORDER BY u.ReviewDate DESC";  // Mengurutkan berdasarkan tanggal ulasan terbaru
                    $ulasan_result = $conn->query($ulasan_query);

                    if ($ulasan_result->num_rows > 0) {
                        while ($ulasan = $ulasan_result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$ulasan['ReservationID']}</td>
                                    <td>{$ulasan['CustomerName']}</td>
                                    <td>{$ulasan['RoomNumber']}</td>
                                    <td>{$ulasan['RoomType']}</td>
                                    <td>{$ulasan['CheckInDate']}</td>
                                    <td>{$ulasan['CheckOutDate']}</td>
                                    <td>{$ulasan['Rating']}</td>
                                    <td>{$ulasan['Comment']}</td>
                                    <td>{$ulasan['ReviewDate']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Belum ada ulasan untuk reservasi ini.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
<script>
  document.addEventListener('DOMContentLoaded', function () {
  const stars = document.querySelectorAll('.star');
  const ratingInput = document.getElementById('rating');

  stars.forEach(star => {
    star.addEventListener('click', function () {
      const value = this.getAttribute('data-value');
      ratingInput.value = value; // Menyimpan nilai rating di input tersembunyi

      // Mengatur warna bintang yang dipilih
      stars.forEach(star => {
        if (star.getAttribute('data-value') <= value) {
          star.classList.add('selected');
        } else {
          star.classList.remove('selected');
        }
      });
    });

    // Efek hover
    star.addEventListener('mouseenter', function () {
      const value = this.getAttribute('data-value');
      stars.forEach(star => {
        if (star.getAttribute('data-value') <= value) {
          star.classList.add('selected');
        } else {
          star.classList.remove('selected');
        }
      });
    });

    // Menghilangkan efek hover saat mouse keluar
    star.addEventListener('mouseleave', function () {
      stars.forEach(star => {
        star.classList.remove('selected');
      });

      const value = ratingInput.value;
      if (value) {
        // Jika sudah memilih rating sebelumnya, tampilkan bintang yang dipilih
        stars.forEach(star => {
          if (star.getAttribute('data-value') <= value) {
            star.classList.add('selected');
          }
        });
      }
    });
  });
});

</script>
  <?php include('../includes/footer.php'); ?>

</body>
</html>
