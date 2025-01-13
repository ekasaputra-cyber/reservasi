<?php
include('../includes/db_connect.php');

if (isset($_GET['reservation_id']) && is_numeric($_GET['reservation_id'])) {
    $reservation_id = (int) $_GET['reservation_id'];

    // Query untuk mendapatkan detail reservasi dan harga kamar
    $sql = "SELECT r.RoomID, k.Price, r.CheckInDate, r.CheckOutDate 
            FROM reservasi r
            JOIN kamar k ON r.RoomID = k.RoomID
            WHERE r.ReservationID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $room_price = $row['Price'];
        $check_in = new DateTime($row['CheckInDate']);
        $check_out = new DateTime($row['CheckOutDate']);
        
        // Menghitung jumlah malam
        $interval = $check_in->diff($check_out);
        $nights = $interval->days;
        
        // Jika check-in dan check-out adalah hari yang sama
        if ($nights == 0) {
            $nights = 1;  // Anggap durasi menginap 1 malam
        }

        // Menghitung total harga
        $total_price = $room_price * $nights;

        // Menampilkan detail reservasi dan harga
        echo "
        <p><strong>Harga Kamar:</strong> IDR " . number_format($room_price, 2, ',', '.') . "</p>
        <p><strong>Durasi Menginap:</strong> {$nights} malam</p>
        <p><strong>Total Pembayaran:</strong> IDR " . number_format($total_price, 2, ',', '.') . "</p>
        <input type='hidden' id='total_amount' value='" . $total_price . "' />
        <script>
            // Mengisi jumlah pembayaran otomatis dengan total harga
            document.getElementById('payment_amount').value = '" . $total_price . "';
        </script>
        ";
    } else {
        echo "<p>Detail reservasi tidak ditemukan.</p>";
    }
}
?>
