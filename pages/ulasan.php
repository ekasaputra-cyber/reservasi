<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $reservation_id = $_POST['reservation_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Memasukkan ulasan ke dalam tabel ulasan
    $sql = "INSERT INTO ulasan (ReservationID, Rating, Comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $reservation_id, $rating, $comment);

    if ($stmt->execute()) {
        echo "Ulasan berhasil dikirim!";
    } else {
        echo "Terjadi kesalahan saat mengirim ulasan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Ulasan Reservasi</h1>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tulis Ulasan Anda</h5>

              <form method="POST">
                <div class="form-group">
                  <label for="reservation_id">ID Reservasi</label>
                  <input type="number" class="form-control" id="reservation_id" name="reservation_id" required>
                </div>
                <div class="form-group">
                  <label for="rating">Rating (1-5)</label>
                  <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                </div>
                <div class="form-group">
                  <label for="comment">Komentar</label>
                  <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Kirim Ulasan</button>
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
