<?php

$update = ((isset($_GET['action']) && $_GET['action'] == 'update') || isset($_SESSION["is_logged"])) ? true : false;
if ($update) {
    $sql = $connection->query("SELECT * FROM user WHERE id_user='$_SESSION[id]'");
    $row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($update) {
        $sql = "UPDATE user SET username='$_POST[username]', password='" . md5($_POST["password"]) . "' WHERE id_user='$_GET[key]'";
    } else {
        $sql = "INSERT INTO user VALUES (NULL, '$_POST[username]', '" . md5($_POST["password"]) . "')";
    }
    if ($connection->query($sql)) {
        echo alert("Berhasil! Silahkan login", "?page=home");
    } else {
        echo alert("Gagal!", "?page=user");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $connection->query("DELETE FROM user WHERE id_user='$_GET[key]'");
    echo alert("Berhasil!", "?page=user");
}
?>

<div class="container">
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <div class="page-header">
        <?php if ($update): ?>
            <h2>Update <small>data user!</small></h2>
        <?php else: ?>
            <h2>Daftar <small>sebagai user!</small></h2>
        <?php endif; ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" <?= (!$update) ?: 'value="' . $row["username"] . '"' ?>>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <?php if ($update): ?>
                <div class="row">
                    <div class="col-md-10">
                        <button type="submit" class="btn btn-warning btn-block">Update</button>
                    </div>
                    <div class="col-md-2">
                        <a href="?page=kriteria" class="btn btn-default btn-block">Batal</a>
                    </div>
                </div>
            <?php else: ?>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
                <div class="text-center">
                    <p>Masuk sebagai pemilik? <a href="index_pemilik.php">Click here</a></p>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-2"></div>
</div>
