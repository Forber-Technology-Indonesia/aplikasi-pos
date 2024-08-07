<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<input type="hidden" name="ttglawal" id="ttglawal" value="<?= $tglawal; ?>">
<input type="hidden" name="ttglakhir" id="ttglakhir" value="<?= $tglakhir; ?>">
<div class="col-lg-12">
    <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Produk</th>
                <th>Stok<br>Sekarang</th>
                <th>Harga<br>Modal</th>
                <th>Saldo (Rp)</th>
                <th>Stok<br>Masuk</th>
                <th>Stok<br>Keluar</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
function tampildataproduk() {
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('laporan/ambildata_persediaanproduk') ?>",
            "type": "POST",
            "data": {
                tglawal: $('#ttglawal').val(),
                tglakhir: $('#ttglakhir').val()
            }
        },


        "columnDefs": [
            //     {
            //     "targets": [0],
            //     "orderable": false,
            //     "width": 10
            // }
        ],
    });
}
$(document).ready(function() {
    tampildataproduk();
});
</script>