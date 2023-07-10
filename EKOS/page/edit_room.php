<?php
include 'koneksi.php';

if (!isset($_SESSION["is_logged"])) {
    echo "<script>alert('Harus login dulu!'); window.location.href='?page=home';</script>";
    exit;
}

$successMessage = $errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_room = $_POST['id_room'];
    $no_kamar = $_POST['no_kamar'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $note = $_POST['note'];
    $harga_seminggu = $_POST['harga_seminggu'];
    $harga_sebulan = $_POST['harga_sebulan'];
    $harga_3bulan = $_POST['harga_3bulan'];
    $harga_6bulan = $_POST['harga_6bulan'];
    $harga_pertahun = $_POST['harga_pertahun'];

    // Handle file upload
    $foto = $_FILES['foto'];
    $filename = $foto['name'];
    $filetmp = $foto['tmp_name'];
    $filetype = $foto['type'];

    $allowedExtensions = array('jpg', 'jpeg', 'png');
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if (!in_array($extension, $allowedExtensions)) {
        echo "<script>alert('Tipe file tidak didukung!'); window.location.href='?page=room';</script>";
        exit;
    }

    $fotoData = file_get_contents($filetmp);
    $fotoData = mysqli_real_escape_string($connection, $fotoData);

    $sql = "UPDATE room SET no_kamar='$no_kamar', kategori='$kategori', deskripsi='$deskripsi', note='$note', foto='$fotoData' WHERE id_room='$id_room'";

    if (mysqli_query($connection, $sql)) {
        $successMessage = "Data berhasil disimpan!";
        $hargaSql = "UPDATE harga SET harga_seminggu=NULLIF('$harga_seminggu', ''), harga_sebulan=NULLIF('$harga_sebulan', ''), harga_3bulan=NULLIF('$harga_3bulan', ''), harga_6bulan=NULLIF('$harga_6bulan', ''), harga_pertahun=NULLIF('$harga_pertahun', '') WHERE id_room='$id_room'";

        if (mysqli_query($connection, $hargaSql)) {
            $successMessage = "Data berhasil disimpan!";
        } else {
            $errorMessage = "Gagal mengupdate data di tabel harga: " . mysqli_error($connection);
        }
    } else {
        $errorMessage = "Gagal mengupdate data di tabel room: " . mysqli_error($connection);
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $key = $_GET['key'];
    $sql = "DELETE FROM room WHERE id_room = '$key'";

    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Berhasil!'); window.location.href='?page=room';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal!'); window.location.href='?page=room';</script>";
        exit;
    }
}

// Retrieve room details
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['key'])) {
    $key = $_GET['key'];
    $query = $connection->query("SELECT * FROM room WHERE id_room='$key'");
    $room = $query->fetch_assoc();
    $hargaQuery = $connection->query("SELECT * FROM harga WHERE id_room='$key'");
    $harga = $hargaQuery->fetch_assoc();
} else {
    // Redirect if no key is provided
    echo "<script>window.location.href='?page=room';</script>";
    exit;
}
?>

<div class="container">
    <div class="page-header">
        <h2>Edit Room</h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <?php if ($successMessage) echo $successMessage; ?>
                <?php if ($errorMessage) { ?>
                    <div class="alert alert-danger"><?= $errorMessage ?></div>
                <?php } ?>
                <div class="panel-heading">
                    <h3 class="text-center">Form Edit Room</h3>
                </div>
                <div class="panel-body">
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_room" value="<?=$room['id_room']?>">
                        <div class="form-group">
                            <label for="no_kamar">Nomor Kamar</label>
                            <input type="text" name="no_kamar" class="form-control" value="<?=$room['no_kamar']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="<?=$room['kategori']?>">
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control"><?=$room['deskripsi']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea name="note" rows="3" class="form-control"><?=$room['note']?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" class="form-control-file" accept="image/jpeg,image/png">
                        </div>
                        <div class="form-group">
                            <label for="harga_seminggu">Harga per Minggu</label>
                            <input type="text" name="harga_seminggu" class="form-control" value="<?= $harga['harga_seminggu'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="harga_sebulan">Harga per Bulan</label>
                            <input type="text" name="harga_sebulan" class="form-control" value="<?= $harga['harga_sebulan'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="harga_3bulan">Harga 3 Bulan</label>
                            <input type="text" name="harga_3bulan" class="form-control" value="<?= $harga['harga_3bulan'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="harga_6bulan">Harga 6 Bulan</label>
                            <input type="text" name="harga_6bulan" class="form-control" value="<?= $harga['harga_6bulan'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="harga_pertahun">Harga per Tahun</label>
                            <input type="text" name="harga_pertahun" class="form-control" value="<?= $harga['harga_pertahun'] ?>">
                        </div
