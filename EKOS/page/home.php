<div class="container">
    <h2>Cari kost!</h2>
    <!-- search -->
    <div class="row">
        <form action="<?=$_SERVER["REQUEST_URI"]?>">
            <input type="hidden" name="searched" value="true">
            <div class="col-md-5">
                <label for="nama" class="control-label">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="control-label">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="">Harga</label>
                <div class="input-group">
                    <span class="input-group-addon" style="border-right: 0;">Min</span>
                    <input type="number" name="min" id="min" class="form-control" value="0">
                    <span class="input-group-addon" style="border-left: 0; border-right: 0;">Max</span>
                    <input type="number" name="max" id="max" class="form-control" value="0">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary" id="submit">Cari...</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <!-- /search -->
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Kamar Tersedia</th>
						<th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET["searched"])) {
                        if ($_GET["searched"] == "click") {
                            $query = $connection->query("SELECT DISTINCT k.id_kost, k.nama, r.kategori, h.harga_seminggu, h.harga_sebulan, h.harga_3bulan, h.harga_6bulan, h.harga_pertahun, k.status, k.tersedia FROM kost k 
                            JOIN room r ON k.id_kost = r.id_kost
                            JOIN harga h ON r.id_room = h.id_room
                            WHERE k.id_kost=$_GET[key] 
                            ORDER BY r.kategori, h.harga_seminggu, h.harga_sebulan, h.harga_3bulan, h.harga_6bulan, h.harga_pertahun");
                        } else {
                            $query = $connection->query("SELECT r.id_kost, k.nama, r.kategori, 
                            MIN(
                                CASE
                                    WHEN h.harga_seminggu IS NOT NULL THEN h.harga_seminggu
                                    WHEN h.harga_sebulan IS NOT NULL THEN h.harga_sebulan
                                    WHEN h.harga_3bulan IS NOT NULL THEN h.harga_3bulan
                                    WHEN h.harga_6bulan IS NOT NULL THEN h.harga_6bulan
                                    ELSE h.harga_pertahun
                                END
                            ) AS harga, k.status, k.tersedia
                            FROM kost k
                            JOIN room r ON k.id_kost = r.id_kost
                            JOIN harga h ON r.id_room = h.id_room
                            WHERE k.nama LIKE '%$_GET[nama]%' AND k.status='$_GET[status]' 
                            AND (
                                h.harga_seminggu BETWEEN $_GET[min] AND $_GET[max]
                                OR h.harga_sebulan BETWEEN $_GET[min] AND $_GET[max]
                                OR h.harga_3bulan BETWEEN $_GET[min] AND $_GET[max]
                                OR h.harga_6bulan BETWEEN $_GET[min] AND $_GET[max]
                                OR h.harga_pertahun BETWEEN $_GET[min] AND $_GET[max]
                            )
                            GROUP BY k.nama, r.kategori
                            ORDER BY r.kategori, harga");
                        }
                    } else {
                        $query = $connection->query("SELECT r.id_kost, k.nama, r.kategori, 
                        MIN(
                            CASE
                                WHEN h.harga_seminggu IS NOT NULL THEN h.harga_seminggu
                                WHEN h.harga_sebulan IS NOT NULL THEN h.harga_sebulan
                                WHEN h.harga_3bulan IS NOT NULL THEN h.harga_3bulan
                                WHEN h.harga_6bulan IS NOT NULL THEN h.harga_6bulan
                                ELSE h.harga_pertahun
                            END
                        ) AS harga, k.status, k.tersedia
                        FROM kost k
                        JOIN room r ON k.id_kost = r.id_kost
                        JOIN harga h ON r.id_room = h.id_room
                        GROUP BY k.nama, r.kategori
                        ORDER BY r.kategori, harga");
                    }
                    $no = 1;
                    ?>
                    <?php while ($row = $query->fetch_assoc()): ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$row["nama"]?></td>
                            <td><?=$row["kategori"]?></td>
                            <td>Rp.<?=$row["harga"]?>,-</td>
                            <td><?=$row["status"]?></td>
                            <td><?=$row["tersedia"]?></td>
							<td><a href="?page=detail&kategori=<?=$row['kategori']?>&key=<?=$row['id_kost']?>" class="btn btn-primary btn-s">Detail</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
