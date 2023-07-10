<?php
include 'koneksi.php';

if (!isset($_SESSION["is_logged"])) {
    echo "<script>alert('Harus login dulu!'); window.location.href='?page=home';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_pemilik = $_SESSION["id"];
    $id_kost = $_POST['id_kost'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tersedia = $_POST['tersedia'];
    $status = $_POST['status'];


    $sql = "UPDATE kost SET nama='$nama', alamat='$alamat', tersedia='$tersedia', status='$status' WHERE id_kost='$id_kost' AND id_pemilik='$id_pemilik'";

    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Berhasil!'); window.location.href='?page=kost';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal!'); window.location.href='?page=kost';</script>";
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $key = $_GET['key'];
    $sql = "DELETE FROM kost WHERE id_kost = '$key'";
    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Berhasil!'); window.location.href='?page=kost';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal!'); window.location.href='?page=kost';</script>";
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $key = $_GET['key'];
    $query = $connection->query("SELECT * FROM kost WHERE id_kost = '$key' AND id_pemilik = $_SESSION[id]");
    $row = $query->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=kost';</script>";
        exit;
    }
}
?>
<div class="container">
    <div class="page-header">
        <h2>Edit <small>kost</small></h2>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">Form Edit Kost</h3>
                </div>
                <div class="panel-body">
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <input type="hidden" name="id_kost" value="<?=$row['id_kost']?>">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?=$row['nama']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?=$row['alamat']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tersedia">Kamar Tersedia</label>
                            <input type="text" name="tersedia" class="form-control" value="<?=$row['tersedia']?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="Laki-laki" <?=($row['status'] == 'Laki-laki') ? 'selected' : ''?>>Laki-laki</option>
                                <option value="Perempuan" <?=($row['status'] == 'Perempuan') ? 'selected' : ''?>>Perempuan</option>
                                <option value="Campur" <?=($row['status'] == 'Campur') ? 'selected' : ''?>>Campur</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
