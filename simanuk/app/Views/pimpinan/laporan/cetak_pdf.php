<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($judul) ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .section-title { font-size: 11pt; font-weight: bold; margin-top: 25px; margin-bottom: 8px; background-color: #e5e7eb; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 0.5px solid #000; }
        th { background-color: #f3f4f6; padding: 6px; text-align: center; font-size: 9pt; font-weight: bold; }
        td { padding: 5px; font-size: 9pt; vertical-align: middle; }
        .text-center { text-align: center; }
        
        /* Gambar agar seragam */
        .img-bukti { 
            width: 60px; 
            height: 60px; 
            object-fit: cover; 
            border: 1px solid #ddd; 
            padding: 2px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Universitas Lambung Mangkurat</h2>
        <p>Laporan Bulanan Sarana Prasarana - Periode: <?= esc($periode) ?></p>
    </div>

    <div class="section-title">I. DATA PEMINJAMAN & BUKTI FISIK</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Peminjam</th>
                <th style="width: 20%;">Barang</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">Foto Awal</th>
                <th style="width: 15%;">Foto Akhir</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjaman['rows'])): ?>
                <tr><td colspan="7" class="text-center">Tidak ada data peminjaman bulan ini.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($peminjaman['rows'] as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($row['nama_lengkap']) ?></td>
                        <td><?= esc($row['nama_item']) ?></td>
                        <td class="text-center">
                            <?= date('d/m', strtotime($row['tgl_pinjam_dimulai'])) ?><br>s/d<br>
                            <?= date('d/m', strtotime($row['tgl_pinjam_selesai'])) ?>
                        </td>
                        
                        <td class="text-center">
                            <?php 
                            // Pastikan path tidak double slash jika di database sudah ada '/'
                            $cleanPath = ltrim($row['foto_sebelum'], '/'); 
                            $pathSebelum = FCPATH . $cleanPath;
                            
                            if (!empty($row['foto_sebelum']) && file_exists($pathSebelum)): 
                                $type = pathinfo($pathSebelum, PATHINFO_EXTENSION);
                                $data = file_get_contents($pathSebelum);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                                <img src="<?= $base64 ?>" class="img-bukti">
                            <?php else: ?>
                                <span style="color:gray;">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php 
                            $cleanPath = ltrim($row['foto_sesudah'], '/');
                            $pathSesudah = FCPATH . $cleanPath;
                            
                            if (!empty($row['foto_sesudah']) && file_exists($pathSesudah)): 
                                $type = pathinfo($pathSesudah, PATHINFO_EXTENSION);
                                $data = file_get_contents($pathSesudah);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                                <img src="<?= $base64 ?>" class="img-bukti">
                            <?php else: ?>
                                <span style="color:gray;">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center"><?= esc($row['kondisi_akhir'] ?? 'Baik') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">II. DATA KERUSAKAN ASET</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <?php foreach ($kerusakan['columns'] as $col) : ?>
                    <th><?= esc($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($kerusakan['rows'])): ?>
                <tr><td colspan="<?= count($kerusakan['columns']) + 1 ?>" class="text-center">Tidak ada laporan kerusakan.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($kerusakan['rows'] as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <?php foreach ($row as $key => $val) : ?>
                            <?php if (strpos($key, 'id_') !== false) continue; ?>
                            <td><?= esc($val) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">III. REKAPITULASI INVENTARIS (POSISI STOK)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <?php foreach ($inventaris['columns'] as $col) : ?>
                    <th><?= esc($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($inventaris['rows'])): ?>
                <tr><td colspan="<?= count($inventaris['columns']) + 1 ?>" class="text-center">Data inventaris kosong.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($inventaris['rows'] as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <?php foreach ($row as $key => $val) : ?>
                            <?php if (strpos($key, 'id_') !== false) continue; ?>
                            <td><?= esc($val) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>