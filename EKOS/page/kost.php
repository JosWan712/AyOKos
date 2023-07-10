<?php
include 'koneksi.php';

if (!isset($_SESSION["is_logged"])) {
    echo "<script>alert('Harus login dulu!'); window.location.href='?page=home';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_pemilik = $_SESSION["id"];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tersedia = $_POST['tersedia'];
    $status = $_POST['status'];
  
    
    $sql = "INSERT INTO kost (id_pemilik, nama, alamat, tersedia, status) VALUES ('$id_pemilik', '$nama', '$alamat', '$tersedia', '$status')";

    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Berhasil!'); window.location.href='?page=kost';</script>";
    } else {
        echo "<script>alert('Gagal!'); window.location.href='?page=kost';</script>";
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
?>
<div class="container">
	<div class="page-header">
		<h2>Daftar <small>kost!</small></h2>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="map" style="width:100%; height:300px"></div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4">
  <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="text-center">Form Tambah Kost</h3>
                </div>
                <div class="panel-body">
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tersedia">Kamar Tersedia</label>
                            <input type="text" name="tersedia" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                                <option value="Campur">Campur</option>
                            </select>
                        </div>
                
                        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                    </form>
                </div>
				</div>
		</div>
		<div class="col-md-8">
		    <div class="panel panel-info">
		        <div class="panel-heading"><h3 class="text-center">DAFTAR</h3></div>
		        <div class="panel-body">
		            <table class="table table-condensed">
		                <thead>
		                    <tr>
		                        <th>No</th>
								<th>Nama</th>
		                        <th>Tersedia</th>
		                        <th>Alamat</th>
		                        <th></th>
		                    </tr>
		                </thead>
		                <tbody>
		                    <?php $no = 1; ?>
		                    <?php if ($query = $connection->query("SELECT * FROM kost WHERE id_pemilik=$_SESSION[id]")): ?>
		                        <?php while($row = $query->fetch_assoc()): ?>
		                        <tr>
		                            <td><?=$no++?></td>
		                            <td><?=$row['nama']?></td>
		                            <td><?=$row['tersedia']?></td>
		                            <td><?=$row['alamat']?></td>
		                            <td>
		                                <div class="btn-group">
                                        <a href="?page=edit_kost&action=update&key=<?=$row['id_kost']?>" class="btn btn-warning btn-xs">Edit</a>
                                        <a href="?page=room&key=<?=$row['id_kost']?>" class="btn btn-primary btn-xs">Room</a>
		                                    <a href="?page=kost&action=delete&key=<?=$row['id_kost']?>" class="btn btn-danger btn-xs">Hapus</a>
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