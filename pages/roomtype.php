<?php include('../includes/db_connect.php'); ?>

<?php
// Menangani penghapusan Room Type
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Query untuk menghapus Room Type berdasarkan ID
    $delete_sql = "DELETE FROM roomtype WHERE RoomTypeID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Room Type berhasil dihapus!'); window.location.href='roomtype.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus Room Type.');</script>";
    }
}

// Menangani penambahan Room Type
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room_type'])) {
    $room_type_name = $_POST['RoomType'];
    $default_price = $_POST['DefaultPrice'];

    // Query untuk menambahkan Room Type baru
    $add_sql = "INSERT INTO roomtype (RoomType, DefaultPrice) VALUES (?, ?)";
    $stmt = $conn->prepare($add_sql);
    $stmt->bind_param("sd", $room_type_name, $default_price);
    
    if ($stmt->execute()) {
        echo "<script>alert('Room Type berhasil ditambahkan!'); window.location.href='roomtype.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan Room Type.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../includes/header.php'); ?>

<body>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Room Type Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Room Type</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Room Type</h5>

              <!-- Form untuk Menambah Room Type -->
              <form method="POST" class="mb-3">
                <div class="row mb-3">
                  <label for="RoomType" class="col-sm-2 col-form-label">Room Type Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="RoomType" id="RoomType" placeholder="Enter Room Type Name" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="DefaultPrice" class="col-sm-2 col-form-label">Default Price</label>
                  <div class="col-sm-10">
                    <input type="number" step="0.01" class="form-control" name="DefaultPrice" id="DefaultPrice" placeholder="Enter Default Price" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <button type="submit" name="add_room_type" class="btn btn-primary">Add Room Type</button>
                  </div>
                </div>
              </form>

              <!-- Tabel Data Room Type -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Room Type ID</th>
                    <th>Room Type Name</th>
                    <th>Default Price</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Query untuk mendapatkan daftar Room Type
                  $sql = "SELECT * FROM roomtype";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>
                              <td>{$row['RoomTypeID']}</td>
                              <td>{$row['RoomType']}</td>
                              <td>{$row['DefaultPrice']}</td>
                              <td>
                                <a href='room_type.php?delete_id={$row['RoomTypeID']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus Room Type ini?\")'>Delete</a>
                              </td>
                            </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='4' class='text-center'>Tidak ada Room Type ditemukan</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Tabel Data Room Type -->
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
