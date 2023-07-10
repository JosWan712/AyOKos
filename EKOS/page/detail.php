<div class="container">
    <h2>Detail Kost</h2>
    <?php
    include 'koneksi.php';

    // Check if key and kategori parameters are provided in the URL
    if (!isset($_GET['key']) || !isset($_GET['kategori'])) {
        echo "<script>alert('Key or Kategori not found!'); window.location.href='?page=home';</script>";
        exit;
    }

    // Retrieve key and kategori parameters
    $key = $_GET['key'];
    $kategori = $_GET['kategori'];

    // Retrieve rooms based on key and kategori
    $queryRooms = $connection->query("SELECT * FROM room WHERE id_kost = '$key' AND kategori = '$kategori'");
    $rooms = $queryRooms->fetch_all(MYSQLI_ASSOC);

    // Retrieve room prices
    $queryPrices = $connection->query("SELECT * FROM harga WHERE id_room IN (SELECT id_room FROM room WHERE id_kost = '$key' AND kategori = '$kategori')");
    $prices = $queryPrices->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="room-container">
        <div class="room-slider">
            <?php foreach ($rooms as $room): ?>
                <div class="slide">
                    <div class="room-details">
                        <h3>Room <?= $room['no_kamar'] ?></h3>
                        <p><strong>Deskripsi:</strong> <?= $room['deskripsi'] ?></p>
                        <?php
            // Retrieve the image data from the database
            $imageData = $room['foto'];
            
            // Generate a data URI for the image
            $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);
            ?>
            <img src="<?php echo $imageSrc; ?>" alt="Room Image">
                        <?php
                        $roomID = $room['id_room'];
                        $roomPrices = array_filter($prices, function($price) use ($roomID) {
                            return $price['id_room'] == $roomID && !empty($price['harga_seminggu']) || !empty($price['harga_sebulan']) || !empty($price['harga_3bulan']) || !empty($price['harga_6bulan']) || !empty($price['harga_pertahun']);
                        });
                        ?>
                        <div class="room-prices">
                            <?php foreach ($roomPrices as $price): ?>
                                <?php if (!empty($price['harga_seminggu'])): ?>
                                    <h4>Harga Sewa Mingguan: <?= $price['harga_seminggu'] ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($price['harga_sebulan'])): ?>
                                    <h4>Harga Sewa Bulanan: <?= $price['harga_sebulan'] ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($price['harga_3bulan'])): ?>
                                    <h4>Harga Sewa 3 Bulan: <?= $price['harga_3bulan'] ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($price['harga_6bulan'])): ?>
                                    <h4>Harga Sewa 6 Bulan: <?= $price['harga_6bulan'] ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($price['harga_pertahun'])): ?>
                                    <h4>Harga Sewa Pertahun: <?= $price['harga_pertahun'] ?></h4>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
						<a href="?page=order&id_room=<?= $room['id_room'] ?>" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slider-navigation">
			<button class="prev-slide">Previous</button>
            <button class="next-slide">Next</button>
        </div>
    </div>

    <!-- Include CSS styles -->
    <link rel="stylesheet" type="text/css" href="path/to/your/css/slider.css">

    <!-- Include JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.room-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: $('.prev-slide'),
                nextArrow: $('.next-slide')
            });
        });
    </script>
</div>

