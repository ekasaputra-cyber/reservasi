<?php include('../includes/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>
<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Data Pelanggan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active">Pelanggan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Pelanggan</h5>
              <p>Berikut adalah daftar pelanggan yang terdaftar dalam sistem Anda.</p>
              
              <a href="pelanggan_create.php" class="btn btn-primary mb-3">Tambah Data Baru</a>
              <?php include('../includes/db_connect.php'); ?>
              
              <!-- Tabel Data Pelanggan -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Customer ID</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM pelanggan";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['CustomerID']}</td>
                            <td>{$row['FullName']}</td>
                            <td>{$row['PhoneNumber']}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['Address']}</td>
                            <td>
                              <a href='pelanggan_edit.php?id={$row['CustomerID']}'>Edit</a> |
                              <a href='pelanggan_delete.php?id={$row['CustomerID']}'>Delete</a>
                            </td>
                          </tr>";
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Tabel Data Pelanggan -->
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
