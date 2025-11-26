<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($judul) ?></title>
    <style>
        /* CSS Khusus untuk Dompdf */
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header h3 { margin: 2px 0; font-size: 12pt; font-weight: normal; }
        .header p { margin: 0; font-size: 9pt; font-style: italic; }
        
        .meta { margin-bottom: 15px; font-size: 11pt; }
        
        .section-title { 
            font-size: 11pt; font-weight: bold; margin-top: 25px; margin-bottom: 8px; 
            background-color: #e5e7eb; padding: 5px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 0.5px solid #000; }
        th { background-color: #f3f4f6; padding: 6px; text-align: center; font-size: 9pt; font-weight: bold; }
        td { padding: 5px; font-size: 9pt; vertical-align: top; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .footer { margin-top: 40px; text-align: right; page-break-inside: avoid; }
        .ttd-area { height: 70px; }
        
        /* Page Break Helper */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Universitas Lambung Mangkurat</h2>
        <h3>Fakultas Teknik</h3>
        <p>Jl. Jenderal Achmad Yani Km. 36, Banjarbaru, Kalimantan Selatan</p>
    </div>

    <div class="meta">
        <strong>Laporan Bulanan Sarana Prasarana</strong><br>
        Periode: <?= esc($periode) ?>
    </div>

    <div class="section-title">I. DATA PEMINJAMAN</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <?php foreach ($peminjaman['columns'] as $col) : ?>
                    <th><?= esc($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjaman['rows'])): ?>
                <tr><td colspan="<?= count($peminjaman['columns']) + 1 ?>" class="text-center">Tidak ada data peminjaman bulan ini.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($peminjaman['rows'] as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <?php foreach ($row as $key => $val) : ?>
                            <?php if (strpos($key, 'id_') !== false) continue; ?>
                            <td>
                                <?php 
                                if (strtotime($val) && strlen($val) > 10 && !is_numeric($val)) {
                                    echo date('d/m/y H:i', strtotime($val));
                                } else {
                                    echo esc($val);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
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
                <tr><td colspan="<?= count($kerusakan['columns']) + 1 ?>" class="text-center">Tidak ada laporan kerusakan bulan ini.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($kerusakan['rows'] as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <?php foreach ($row as $key => $val) : ?>
                            <?php if (strpos($key, 'id_') !== false) continue; ?>
                            <td>
                                <?php 
                                if (strtotime($val) && strlen($val) > 10 && !is_numeric($val)) {
                                    echo date('d/m/y', strtotime($val));
                                } else {
                                    echo esc($val);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">III. REKAPITULASI INVENTARIS</div>
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