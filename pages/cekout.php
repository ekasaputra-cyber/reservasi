<?php
  include('../includes/db_connect.php');

  // Cek apakah ID reservasi diterima dalam parameter
  if (isset($_GET['id'])) {
      $reservation_id = $_GET['id'];
      
      // Query untuk mendapatkan status reservasi berdasarkan ID
      $sql = "SELECT Status FROM reservasi WHERE ReservationID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $reservation_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          // Pastikan hanya status "CheckIn" yang bisa diubah menjadi "CheckedOut"
          if ($row['Status'] == 'CheckIn') {
              // Update status menjadi "CheckedOut"
              $update_sql = "UPDATE reservasi SET Status = 'CheckOut' WHERE ReservationID = ?";
              $update_stmt = $conn->prepare($update_sql);
              $update_stmt->bind_param("i", $reservation_id);

              if ($update_stmt->execute()) {
                  // Setelah sukses, redirect ke halaman reservasi dan tampilkan pesan sukses
                  header("Location: reservasi_list.php?message=Reservasi berhasil check-out");
                  exit();
              } else {
                  echo "Terjadi kesalahan saat mengubah status reservasi.";
              }
          } else {
              echo "Status reservasi tidak sesuai untuk check-out.";
          }
      } else {
          echo "Reservasi tidak ditemukan.";
      }
  } else {
      echo "ID Reservasi tidak ditemukan.";
  }
?>
