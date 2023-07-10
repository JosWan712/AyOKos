<?php
include 'koneksi.php';

if (!isset($_SESSION["is_logged"])) {
    echo <<<_HTML
    <script>alert('Harus login dulu!'); window.location.href='?page=home';</script>
_HTML;
    exit;
}
$successMessage = $errorMessage = "";
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_kost = $_POST['id_kost'];
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
        echo <<<_HTML
        <script>alert('Tipe file tidak didukung!'); window.location.href='?page=room';</script>
_HTML;
        exit;
    }

    $fotoData = file_get_contents($filetmp);
    $fotoData = mysqli_real_escape_string($connection, $fotoData);

    $sql = "INSERT INTO room (id_kost, no_kamar, kategori, deskripsi, note, foto) VALUES ('$id_kost', '$no_kamar', '$kategori', '$deskripsi', '$note', '$fotoData')";

    if (mysqli_query($connection, $sql)) {
        $roomID = mysqli_insert_id($connection);

        $hargaSql = "INSERT INTO harga (id_room, harga_seminggu, harga_sebulan, harga_3bulan, harga_6bulan, harga_pertahun) 
             VALUES ('$roomID', NULLIF('$harga_seminggu', ''), NULLIF('$harga_sebulan', ''), NULLIF('$harga_3bulan', ''), NULLIF('$harga_6bulan', ''), NULLIF('$harga_pertahun', ''))";

        if (mysqli_query($connection, $hargaSql)) {
            $successMessage = "Data berhasil disimpan!";
        } else {
            $errorMessage = "Gagal menginsert data ke tabel harga: " . mysqli_error($connection);
        }
    } else {
        $errorMessage = "Gagal menginsert data ke tabel room: " . mysqli_error($connection);
    }
    


}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $key = $_GET['key'];
    $sql = "DELETE FROM room WHERE id_room = '$key'";
    if (mysqli_query($connection, $sql)) {
        echo <<<_HTML
        <script>alert('Berhasil!'); window.location.href='?page=room';</script>
_HTML;
        exit;
    } else {
        echo <<<_HTML
        <script>alert('Gagal!'); window.location.href='?page=room';</script>
_HTML;
        exit;
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>Daftar Room</h2>
    </div>
    <div class="row">
		<div class="col-md-4">
  <div class="panel panel-info">
  <?php if ($successMessage) echo $successMessage; ?>
    <?php if ($errorMessage) { ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php } ?>
                <div class="panel-heading">
                    <h3 class="text-center">Form Tambah Room</h3>
                </div>
                <div class="panel-body">
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="id_kost">Kost</label>
                            <select class="form-control" name="id_kost" required>
                                <?php $key = $_GET['key']; 
                                if ($query = $connection->query("SELECT * FROM kost WHERE id_kost=$key")): ?>
                                    <?php while($row = $query->fetch_assoc()): ?>
                                        <option value="<?=$row['id_kost']?>"><?=$row['nama']?></option>
                                    <?php endwhile ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="no_kamar">Nomor Kamar</label>
                            <input type="text" name="no_kamar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="no_kamar">Kategori</label>
                            <input type="text" name="kategori" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="harga_seminggu">Harga/1minggu</label>
                            <input type="text" name="harga_seminggu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="harga_sebulan">Harga/1bln</label>
                            <input type="text" name="harga_sebulan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="harga_3bulan">Harga/3bln</label>
                            <input type="text" name="harga_3bulan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="harga_6bulan">Harga/6bln</label>
                            <input type="text" name="harga_6bulan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="harga_pertahun">Harga Pertahun</label>
                            <input type="text" name="harga_pertahun" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" class="form-control-file" accept="image/jpeg,image/png" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">DAFTAR</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Kamar</th>
                                <th>Deskripsi</th>
                                <th>Catatan</th>
                                <th>Hrg Seminggu</th>
                                <th>Hrg Sebulan</th>
                                <th>Hrg 3bulan</th>
                                <th>Hrg 6bulan</th>
                                <th>Hrg Pertahun</th>
                                <th>Foto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php $key = $_GET['key'];
                            if ($query = $connection->query("SELECT r.*, h.harga_seminggu, h.harga_sebulan, h.harga_3bulan, h.harga_6bulan, h.harga_pertahun FROM room r LEFT JOIN harga h ON r.id_room = h.id_room WHERE r.id_kost=$key")): ?>
                                <?php while($row = $query->fetch_assoc()): ?>
                                    <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$row['no_kamar']?></td>
                                        <td><?=$row['deskripsi']?></td>
                                        <td><?=$row['note']?></td>
                                        <td><?=$row['harga_seminggu']?></td>
                                        <td><?=$row['harga_sebulan']?></td>
                                        <td><?=$row['harga_3bulan']?></td>
                                        <td><?=$row['harga_6bulan']?></td>
                                        <td><?=$row['harga_pertahun']?></td>

                                        <td>
                                            <?php if ($row['foto']): ?>
                                                <img src="data:image/jpeg;base64,<?=base64_encode($row['foto'])?>" alt="Foto" width="100">
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="?page=edit_room&action=update&key=<?=$row['id_room']?>" class="btn btn-warning btn-xs">Edit</a>
                                                <a href="?page=room&action=delete&key=<?=$row['id_room']?>" class="btn btn-danger btn-xs">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
