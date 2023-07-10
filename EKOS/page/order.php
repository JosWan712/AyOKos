<?php
include 'koneksi.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $idRoom = $_POST['id_room'];
    $idUser = $_POST['id_user'];
    $tglMasuk = $_POST['tgl_masuk'];
    $satuan = $_POST['satuan'];
    $hargaSatuan = $_POST['harga_satuan'];
    $jumlah = $_POST['jumlah'];
    
    // Calculate tgl_keluar based on the selected satuan and jumlah
    $tglKeluar = date('Y-m-d', strtotime("$tglMasuk +$jumlah $satuan"));
    
    // Calculate harga_total
    $hargaTotal = $hargaSatuan * $jumlah;
    
    // Insert the data into the pemesanan table
    $query = "INSERT INTO pemesanan (id_room, id_user, tgl_masuk, tgl_keluar, harga_total)
              VALUES ('$idRoom', '$idUser', '$tglMasuk', '$tglKeluar', '$hargaTotal')";
    
    if ($connection->query($query) === TRUE) {
        echo "<script>alert('Order placed successfully!'); window.location.href='?page=home';</script>";
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $connection->error;
    }
}

// Retrieve the room details from the database
if (isset($_GET['id_room'])) {
    $idRoom = $_GET['id_room'];
    $queryRoom = $connection->query("SELECT * FROM room WHERE id_room = '$idRoom'");
    $room = $queryRoom->fetch_assoc();
    
    // Retrieve the room prices
    $queryPrices = $connection->query("SELECT * FROM harga WHERE id_room = '$idRoom'");
    $prices = $queryPrices->fetch_assoc();
}
?>


<!-- Display the order form -->
<div class="container">
    <h2>Order Room</h2>
    <form id="order-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
        <div class="form-group">
            <label for="id_room">Room ID</label>
            <input type="text" id="id_room" name="id_room" value="<?php echo $room['id_room']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="id_user">User ID</label>
            <input type="text" id="id_user" name="id_user" value="<?php echo $_SESSION['id']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="tgl_masuk">Tgl Masuk</label>
            <input type="date" id="tgl_masuk" name="tgl_masuk" required>
        </div>
        <div class="form-group">
            <label for="satuan">Satuan</label>
            <select id="satuan" name="satuan">
                <?php if (!empty($prices['harga_seminggu'])): ?>
                    <option value="minggu">Minggu</option>
                <?php endif; ?>
                <?php if (!empty($prices['harga_sebulan'])): ?>
                    <option value="sebulan">Sebulan</option>
                <?php endif; ?>
                <?php if (!empty($prices['harga_3bulan'])): ?>
                    <option value="3bulan">3 Bulan</option>
                <?php endif; ?>
                <?php if (!empty($prices['harga_6bulan'])): ?>
                    <option value="6bulan">6 Bulan</option>
                <?php endif; ?>
                <?php if (!empty($prices['harga_pertahun'])): ?>
                    <option value="pertahun">Tahunan</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="harga_satuan">Harga Satuan</label>
            <input type="text" id="harga_satuan" name="harga_satuan" value="" readonly>
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" id="jumlah" name="jumlah" min="1" required>
        </div>
        <div class="form-group">
            <label for="tgl_keluar">Tgl Keluar</label>
            <input type="date" id="tgl_keluar" name="tgl_keluar" value="" readonly>
        </div>
        <div class="form-group">
            <label for="harga_total">Harga Total</label>
            <input type="text" id="harga_total" name="harga_total" value="" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Order</button>
    </form>
</div>

<script>
// Function to calculate tgl_keluar and harga_total based on user input
function calculateFields() {
    var tglMasuk = document.getElementById('tgl_masuk').value;
    var satuan = document.getElementById('satuan').value;
    var hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);
    var jumlah = parseInt(document.getElementById('jumlah').value);

    // Calculate tgl_keluar based on satuan and jumlah
    var tglKeluar = new Date(tglMasuk);
    if (satuan === 'minggu') {
        tglKeluar.setDate(tglKeluar.getDate() + (7 * jumlah));
    } else if (satuan === 'sebulan') {
        tglKeluar.setMonth(tglKeluar.getMonth() + jumlah);
    } else if (satuan === '3bulan') {
        tglKeluar.setMonth(tglKeluar.getMonth() + (3 * jumlah));
    } else if (satuan === '6bulan') {
        tglKeluar.setMonth(tglKeluar.getMonth() + (6 * jumlah));
    } else if (satuan === 'pertahun') {
        tglKeluar.setFullYear(tglKeluar.getFullYear() + jumlah);
    }

    // Calculate harga_total
    var hargaTotal = hargaSatuan * jumlah;

    // Update the tgl_keluar and harga_total fields
    document.getElementById('tgl_keluar').value = tglKeluar.toISOString().split('T')[0];
    document.getElementById('harga_total').value = hargaTotal.toFixed(2);
}

// Attach the calculateFields function to the change event of relevant form fields
document.getElementById('tgl_masuk').addEventListener('change', calculateFields);
document.getElementById('satuan').addEventListener('change', calculateFields);
document.getElementById('jumlah').addEventListener('input', calculateFields);

// Initialize the fields on page load
calculateFields();
</script>
