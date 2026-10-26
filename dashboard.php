<?php require 'config/database.php';require_login();
$omset=$pdo->query("SELECT COALESCE(SUM(grand_total),0) FROM orders WHERE DATE(created_at)=CURDATE() AND status='Selesai'")->fetchColumn();
$count=$pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE() AND status='Selesai'")->fetchColumn();
$top=$pdo->query("SELECT p.nama_produk,COALESCE(SUM(od.kuantiti),0) qty FROM order_details od JOIN products p ON p.id=od.product_id JOIN orders o ON o.id=od.order_id WHERE DATE(o.created_at)=CURDATE() GROUP BY p.id ORDER BY qty DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$low=$pdo->query("SELECT COUNT(*) FROM products WHERE stok<=stok_minimum")->fetchColumn();
$stockAlerts=$pdo->query("SELECT id,nama_produk,stok,stok_minimum FROM products WHERE stok<=stok_minimum ORDER BY (stok=0) DESC,stok ASC,nama_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
$recent=$pdo->query("SELECT * FROM orders ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
require 'config/header.php';?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="page-title mb-1">Dashboard Kafe</h2><span class="text-muted">Ringkasan aktivitas penjualan hari ini</span></div><a class="btn btn-primary" href="cashier/transaction.php">+ Transaksi Baru</a></div>
<div class="row g-3 my-2">
<div class="col-md-3"><div class="card kpi-card p-3"><small>OMSET HARI INI</small><h4><?=rupiah($omset)?></h4></div></div>
<div class="col-md-3"><div class="card kpi-card p-3"><small>JUMLAH PESANAN</small><h4><?=$count?></h4></div></div>
<div class="col-md-3"><div class="card kpi-card p-3"><small>MENU TERLARIS</small><h4><?=htmlspecialchars(isset($top['nama_produk'])?$top['nama_produk']:'-')?></h4></div></div>
<div class="col-md-3"><div class="card kpi-card p-3"><small>PERLU CEK STOK</small><h4 class="<?=$low>0?'text-danger':''?>"><?=$low?></h4></div></div>
</div>
<div class="card p-4 mt-4 mb-4"><div class="d-flex justify-content-between align-items-center flex-wrap mb-3"><h5 class="page-title mb-0">Peringatan Stok</h5><?php if($_SESSION['user']['role']==='admin'): ?><a class="btn btn-sm btn-primary" href="admin/products.php">Kelola / Tambah Stok</a><?php endif; ?></div>
<?php if(count($stockAlerts)>0): ?><div class="alert alert-warning">Ada <?=count($stockAlerts)?> menu yang stoknya habis atau sudah mencapai batas minimum.</div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Menu</th><th>Stok tersisa</th><th>Batas minimum</th><th>Status</th><?php if($_SESSION['user']['role']==='admin'): ?><th>Aksi</th><?php endif; ?></tr></thead><tbody>
<?php foreach($stockAlerts as $s): ?><tr><td><?=htmlspecialchars($s['nama_produk'])?></td><td class="<?=$s['stok']<=0?'text-danger fw-bold':'text-warning-emphasis fw-bold'?>"><?=$s['stok']?></td><td><?=$s['stok_minimum']?></td><td><?php if((int)$s['stok']<=0): ?><span class="badge bg-danger">Stok Habis</span><?php else: ?><span class="badge bg-warning text-dark">Stok Menipis</span><?php endif; ?></td><?php if($_SESSION['user']['role']==='admin'): ?><td><a class="btn btn-sm btn-outline-primary" href="admin/products.php">Tambah stok</a></td><?php endif; ?></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="alert alert-success mb-0">Semua stok masih aman. Tidak ada menu yang perlu segera diisi ulang.</div><?php endif; ?></div>
<div class="card p-4 mt-4"><h5 class="page-title">Transaksi Terbaru</h5><div class="table-responsive"><table class="table align-middle"><tr><th>Invoice</th><th>Tanggal</th><th>Tipe</th><th>Total</th><th>Status</th></tr><?php foreach($recent as $r): ?><tr><td><strong><?=htmlspecialchars($r['invoice_number'])?></strong></td><td><?=$r['created_at']?></td><td><?=htmlspecialchars($r['tipe_pesanan'])?></td><td><?=rupiah($r['grand_total'])?></td><td><span class="soft-badge"><?=htmlspecialchars($r['status'])?></span></td></tr><?php endforeach; ?></table></div></div>
<?php require 'config/footer.php';?>
