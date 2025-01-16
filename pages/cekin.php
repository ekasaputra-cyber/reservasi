<?php
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Mulai transaksi untuk menjaga konsistensi data
    $conn->begin_transaction();

    try {
        // Update status reservasi menjadi "CheckIn"
        $update_reservasi_sql = "UPDATE reservasi SET Status = 'CheckIn' WHERE ReservationID = ?";
        $stmt = $conn->prepare($update_reservasi_sql);
        $stmt->bind_param("i", $reservation_id);

        if ($stmt->execute()) {
            // Update status kamar menjadi "Occupied"
            $update_kamar_sql = "UPDATE kamar k
                                 JOIN reservasi r ON k.RoomID = r.RoomID
                                 SET k.Status = 'Occupied'
                                 WHERE r.ReservationID = ?";
            $update_stmt = $conn->prepare($update_kamar_sql);
            $update_stmt->bind_param("i", $reservation_id);
            $update_stmt->execute();

            // Commit transaksi setelah semua query berhasil
            $conn->commit();

            // Redirect setelah sukses
            header("Location: reservasi_list.php?message=Check-In berhasil");
            exit;
        } else {
            throw new Exception("Gagal memperbarui status reservasi.");
        }
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();
        echo "Terjadi kesalahan saat proses check-in: " . $e->getMessage();
    }
} else {
    echo "ID reservasi tidak ditemukan!";
    exit;
}
?>
