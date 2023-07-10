<?php
$connection = mysqli_connect("localhost", "root", "", 'kos');

if (!$connection) {
// if (!$connection = new Mysqli("mysql.idhostinger.com", "u545578441_ekost", "ekost-jogja", "u545578441_ekost")) {
  echo "<h3>ERROR: Koneksi database gagal!</h3>";
}


mysqli_select_db($connection, 'kos');
?>