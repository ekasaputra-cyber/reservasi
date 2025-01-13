<?php
include('../includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $FullName = $_POST['FullName'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];
    $RoomID = $_POST['RoomID'];
    $CheckInDate = $_POST['CheckInDate'];
    $CheckOutDate = $_POST['CheckOutDate'];

    // Validasi tanggal (pastikan check-out lebih dari check-in)
    if (new DateTime($CheckInDate) > new DateTime($CheckOutDate)) {
        echo "<script>alert('Tanggal check-out tidak bisa lebih awal dari tanggal check-in.');</script>";
    } else {
        // Insert data pelanggan ke tabel pelanggan menggunakan prepared statement
        $stmt_pelanggan = $conn->prepare("INSERT INTO pelanggan (FullName, PhoneNumber, Email, Address) VALUES (?, ?, ?, ?)");
        $stmt_pelanggan->bind_param("ssss", $FullName, $PhoneNumber, $Email, $Address);

        if ($stmt_pelanggan->execute()) {
            $CustomerID = $conn->insert_id; // Ambil CustomerID yang baru saja ditambahkan

            // Insert data reservasi menggunakan prepared statement
            $stmt_reservasi = $conn->prepare("INSERT INTO reservasi (CustomerID, RoomID, CheckInDate, CheckOutDate, Status) VALUES (?, ?, ?, ?, 'Pending')");
            $stmt_reservasi->bind_param("iiss", $CustomerID, $RoomID, $CheckInDate, $CheckOutDate);

            if ($stmt_reservasi->execute()) {
                // Update status kamar menjadi "Occupied"
                $stmt_update_kamar = $conn->prepare("UPDATE kamar SET Status = 'Occupied' WHERE RoomID = ?");
                $stmt_update_kamar->bind_param("i", $RoomID);
                $stmt_update_kamar->execute();

                // Redirect ke halaman daftar reservasi
                header("Location: reservasi_list.php");
                exit();
            } else {
                echo "<script>alert('Error: " . $stmt_reservasi->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error: " . $stmt_pelanggan->error . "');</script>";
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
      <h1>Reservasi Hotel</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Forms</li>
          <li class="breadcrumb-item active">Reservasi</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form Reservasi</h5>

              <!-- Form Reservasi -->
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
                  <label for="RoomID" class="col-sm-2 col-form-label">Select Room</label>
                  <div class="col-sm-10">
                    <select class="form-select" name="RoomID" id="RoomID" required>
                      <?php
                      // Menampilkan daftar kamar yang tersedia
                      $sql_kamar = "
                      SELECT k.RoomID, k.RoomNumber, rt.RoomType
                      FROM kamar k
                      JOIN roomtype rt ON k.RoomTypeID = rt.RoomTypeID
                      WHERE k.Status = 'Available'
                  ";
                  $result = $conn->query($sql_kamar);
                  while ($row = $result->fetch_assoc()) {
                      echo "<option value='{$row['RoomID']}'>
                              {$row['RoomNumber']} - {$row['RoomType']}
                            </option>";
                  }
                  
                      ?>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="CheckInDate" class="col-sm-2 col-form-label">Check-In Date</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="CheckInDate" id="CheckInDate" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="CheckOutDate" class="col-sm-2 col-form-label">Check-Out Date</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="CheckOutDate" id="CheckOutDate" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Submit Reservation</button>
                  </div>
                </div>
              </form>
              <!-- End Form Reservasi -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.querySelector("form");
      form.addEventListener("submit", function(event) {
        const checkinDate = document.getElementById("CheckInDate").value;
        const checkoutDate = document.getElementById("CheckOutDate").value;

        // Validasi Tanggal Check-in dan Check-out
        if (new Date(checkinDate) > new Date(checkoutDate)) {
          alert("Tanggal check-out tidak bisa lebih awal dari tanggal check-in.");
          event.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
