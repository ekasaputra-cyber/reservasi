<?php include('../includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Daftar Reservasi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Reservasi</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Reservasi</h5>
              <p>Berikut adalah daftar reservasi yang terdaftar dalam sistem Anda.</p>
              
              <a href="reservasi.php" class="btn btn-primary mb-3">Tambah Reservasi Baru</a>
              
              <!-- Tabel Data Reservasi -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Reservation ID</th>
                    <th>Customer Name</th>
                    <th>Room Number</th>
                    <th>Check-In Date</th>
                    <th>Check-Out Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Query untuk mendapatkan data reservasi dan pelanggan
                  $sql = "SELECT r.ReservationID, p.FullName, k.RoomNumber, r.CheckInDate, r.CheckOutDate, r.Status
                          FROM reservasi r
                          JOIN pelanggan p ON r.CustomerID = p.CustomerID
                          JOIN kamar k ON r.RoomID = k.RoomID";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>
                              <td>{$row['ReservationID']}</td>
                              <td>{$row['FullName']}</td>
                              <td>{$row['RoomNumber']}</td>
                              <td>{$row['CheckInDate']}</td>
                              <td>{$row['CheckOutDate']}</td>
                              <td>{$row['Status']}</td>
                              <td>
                                <a href='reservasi_edit.php?id={$row['ReservationID']}'>Edit</a> |
                                <a href='reservasi_delete.php?id={$row['ReservationID']}'>Delete</a>
                              </td>
                            </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data reservasi ditemukan</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Tabel Data Reservasi -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include('../includes/footer.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/simple-datatables.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      new simpleDatatables.DataTable(".datatable");
    });
  </script>
</body>
</html>
