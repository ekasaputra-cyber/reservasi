<?php
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    // Query untuk membatalkan (update status) reservasi menjadi 'Cancelled'
    $cancel_sql = "UPDATE reservasi SET Status = 'Cancelled' WHERE ReservationID = ?";
    $stmt = $conn->prepare($cancel_sql);
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        // Jika kamar yang dibatalkan statusnya 'Occupied', kembalikan status kamar menjadi 'Available'
        $update_kamar_sql = "UPDATE kamar SET Status = 'Available' WHERE RoomID IN (SELECT RoomID FROM reservasi WHERE ReservationID = ?)";
        $update_stmt = $conn->prepare($update_kamar_sql);
        $update_stmt->bind_param("i", $reservation_id);
        $update_stmt->execute();

        echo "Reservasi berhasil dibatalkan!";
        header("Location: reservasi_list.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat membatalkan reservasi.";
    }
} else {
    echo "ID reservasi tidak ditemukan!";
    exit;
}
?>
