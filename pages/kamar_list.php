<?php include('../includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Data Kamar</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Kamar</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Kamar</h5>
              <p>Berikut adalah daftar kamar yang terdaftar dalam sistem Anda.</p>
              
              <a href="kamar_create.php" class="btn btn-primary mb-3">Tambah Kamar Baru</a>

              <!-- Tabel Data Kamar -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Room ID</th>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Query untuk mendapatkan data kamar
                  $sql = "SELECT k.RoomID, k.RoomNumber, k.RoomTypeID, k.Price, k.Status, t.RoomType
                          FROM kamar k
                          JOIN roomtype t ON k.RoomTypeID = t.RoomTypeID";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>
                              <td>{$row['RoomID']}</td>
                              <td>{$row['RoomNumber']}</td>
                              <td>{$row['RoomType']}</td>
                              <td>{$row['Price']}</td>
                              <td>{$row['Status']}</td>
                              <td>
                                <a href='kamar_edit.php?id={$row['RoomID']}'>Edit</a> |
                                <a href='kamar_delete.php?id={$row['RoomID']}'>Delete</a>
                              </td>
                            </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data kamar ditemukan</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Tabel Data Kamar -->
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
