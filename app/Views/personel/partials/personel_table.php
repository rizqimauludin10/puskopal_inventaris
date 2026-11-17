<?php if (!empty($personelList)): ?>
<div class="table-responsive shadow-sm rounded">
    <table class="table table-sm table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th>Nama</th>
                <th>Pangkat</th>
                <th>NRP/NIP</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($personelList as $p): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($p['nama']) ?></td>
                <td><?= esc($p['keterangan']) ?></td>
                <td><?= esc($p['nrp_nip'] ?? '-') ?></td>
                <td><?= esc($p['nama_jabatan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-end">
                    <span class="badge bg-primary me-1">Militer: <?= $totalKategori['Militer'] ?></span>
                    <span class="badge bg-success me-1">PNS: <?= $totalKategori['PNS'] ?></span>
                    <span class="badge bg-warning text-dark me-1">PPPK: <?= $totalKategori['PPPK'] ?></span>
                    <span class="badge bg-secondary">Karyawan: <?= $totalKategori['Karyawan'] ?></span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php else: ?>
<div class="text-center text-muted py-3">
    <em>Belum ada personel di penempatan ini.</em>
</div>
<?php endif; ?>