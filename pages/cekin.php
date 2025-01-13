<?php
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Update status reservasi menjadi "CheckedIn"
    $update_reservasi_sql = "UPDATE reservasi SET Status = 'CheckedIn' WHERE ReservationID = ?";
    $stmt = $conn->prepare($update_reservasi_sql);
    $stmt->bind_param("i", $reservation_id);
    if ($stmt->execute()) {
        // Setelah check-in, ubah status kamar menjadi "Occupied"
        $update_kamar_sql = "UPDATE kamar SET Status = 'Occupied' WHERE RoomID IN (SELECT RoomID FROM reservasi WHERE ReservationID = ?)";
        $update_stmt = $conn->prepare($update_kamar_sql);
        $update_stmt->bind_param("i", $reservation_id);
        $update_stmt->execute();

        header("Location: reservasi_list.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat check-in.";
    }
} else {
    echo "ID reservasi tidak ditemukan!";
    exit;
}
?>
