<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; }
        .section-title { background-color: #eee; padding: 5px; font-weight: bold; margin-top: 20px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; color: white; }
        .bg-red { background-color: #e74c3c; }
        .bg-green { background-color: #2ecc71; }
        .summary-box { margin-bottom: 20px; padding: 10px; border: 1px dashed #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FAKULTAS TEKNIK - UNIVERSITAS LAMBUNG MANGKURAT</h2>
        <p>LAPORAN REKAPITULASI</p>
        <p>Periode Cetak: <?= $bulan ?> <?= $tahun ?></p>
    </div>

    <div class="summary-box">
        <strong>Ringkasan KPI Saat Ini:</strong><br>
        - Pengajuan Baru (Bulan Ini): <?= $kpi['pengajuan'] ?><br>
        - Sedang Dipinjam (Aktif): <?= $kpi['dipinjam'] ?><br>
        - Aset Rusak: <?= $kpi['rusak'] ?><br>
        - Total Aset Terdata: <?= $kpi['aset'] ?>
    </div>

    <div class="section-title">1. Data Pengajuan (Perlu Verifikasi - Bulan Ini)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Peminjam</th>
                <th width="30%">Kegiatan</th>
                <th width="25%">Tanggal Pengajuan</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($dataPengajuan)): ?>
                <tr><td colspan="5" align="center">Tidak ada data pengajuan.</td></tr>
            <?php else: ?>
                <?php $i=1; foreach($dataPengajuan as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($row['nama_lengkap']) ?><br><small><?= esc($row['organisasi']) ?></small></td>
                    <td><?= esc($row['kegiatan']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl_pengajuan'])) ?></td>
                    <td><?= esc($row['status_peminjaman_global']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">2. Barang Sedang Dipinjam (Aktif)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Peminjam</th>
                <th width="30%">Kegiatan</th>
                <th width="25%">Tenggat Waktu</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($dataDipinjam)): ?>
                <tr><td colspan="5" align="center">Tidak ada barang sedang dipinjam.</td></tr>
            <?php else: ?>
                <?php $i=1; foreach($dataDipinjam as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($row['nama_lengkap']) ?></td>
                    <td><?= esc($row['kegiatan']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl_pinjam_selesai'])) ?></td>
                    <td>Dipinjam</td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">3. Daftar Aset Rusak (Perlu Perbaikan)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Kode</th>
                <th width="30%">Nama Aset</th>
                <th width="25%">Lokasi</th>
                <th width="20%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($dataRusak)): ?>
                <tr><td colspan="5" align="center">Semua aset dalam kondisi baik.</td></tr>
            <?php else: ?>
                <?php $i=1; foreach($dataRusak as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($row['kode_sarana']) ?></td>
                    <td><?= esc($row['nama_sarana']) ?></td>
                    <td><?= esc($row['nama_lokasi']) ?></td>
                    <td><span class="badge bg-red"><?= esc($row['kondisi']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">4. Rekapitulasi Total Aset (<?= count($totalAset) ?> Item)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Jenis</th>
                <th width="20%">Kode</th>
                <th width="40%">Nama Item</th>
                <th width="20%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($totalAset)): ?>
                <tr><td colspan="5" align="center">Belum ada data aset.</td></tr>
            <?php else: ?>
                <?php $i=1; foreach(array_slice($totalAset, 0, 50) as $row): // Limit 50 agar tidak terlalu panjang ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($row['jenis']) ?></td>
                    <td><?= esc($row['kode']) ?></td>
                    <td><?= esc($row['nama']) ?></td>
                    <td><?= esc($row['kondisi']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($totalAset) > 50): ?>
                <tr>
                    <td colspan="5" align="center">... dan <?= count($totalAset) - 50 ?> item lainnya ...</td>
                </tr>
                <?php endif; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 30px; text-align: right;">
        <p>Banjarbaru, <?= date('d F Y') ?></p>
        <p>Dicetak Oleh,</p>
        <br><br>
        <p><b>( <?= auth()->user()->username ?> )</b></p>
        <p>Tata Usaha</p>
    </div>
</body>
</html>