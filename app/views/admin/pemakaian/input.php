<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<div class="row mt-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header text-white" style="background-color: #8313a8;">

                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Input Pemakaian Barang</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="document.location='<?= site_url('pemakaian/index') ?>'">
                            <i class="fa fa-backward"></i> Kembali
                        </button>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">Faktur</label>
                        <input type="text" name="faktur" id="faktur" class="form-control form-control-sm"
                            value="<?= $faktur; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Tgl.Pemakaian</label>
                        <input type="date" name="tgl" id="tgl" class="form-control-sm form-control"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-sm-3">
                        <label for="">Pilih Akun Biaya</label>
                        <select name="akun" id="akun" class="form-control form-control-sm" required
                            style="width: 100%;">
                            <option value="">-Pilih Akun-</option>
                            <?php foreach ($dataakunbiaya->result_array() as $b) : ?>
                            <option value="<?= $b['noakun']; ?>"><?= $b['noakun'] . '(' . $b['namaakun'] . ')'; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Aksi</label><br>
                        <button type="button" class="btn btn-sm btn-danger btnbataltransaksi">
                            <i class="fa fa-ban"></i> Batal
                        </button>
                        <button type="button" class="btn btn-sm btn-success btnsimpantransaksi">
                            <i class="fa fa-save"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row viewforminputproduk mt-4" style="display: none;">
                </div>
                <div class="row viewtampildata mt-2" style="display: none;">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodalcariproduk" style="display: none;"></div>
<script>
function buatnomor() {
    let tgl = $('#tgl').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('pemakaian/buatnomor') ?>",
        data: {
            tgl: tgl
        },
        dataType: "json",
        success: function(response) {
            if (response.faktur) {
                $('#faktur').val(response.faktur);
                $('#nofaktur').val(response.faktur);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function tampilforminputproduk() {

    $.ajax({
        url: "<?= site_url('admin/pemakaian/forminput') ?>",
        data: {
            nofaktur: $('#faktur').val()
        },
        type: 'post',
        dataType: "json",
        beforeSend: function(e) {
            $('.viewforminputproduk').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewforminputproduk').html(response.data).show();
                $('#kodebarcode').focus();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
$(document).ready(function() {
    $('#akun').select2();
    tampilforminputproduk();

    $('#tgl').change(function(e) {
        e.preventDefault();
        buatnomor();
    });

    $(this).keydown(function(e) {
        if (e.keyCode == 27) {
            e.preventDefault();
            $('#kodebarcode').focus();
        }

    });

    $('.btnbataltransaksi').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus',
            text: `Yakin Membatalkan Transaksi Pemakaian ini ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, <i class="fa fa-ban"></i> Batalkan !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('pemakaian/bataltransaksi') ?>",
                    data: {
                        faktur: $('#faktur').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.sukses,
                            }).then((result) => {
                                if (result.value) {

                                    window.location.reload();
                                }
                            });
                        }
                        if (response.error) {
                            $.toast({
                                heading: 'Error',
                                text: response.error,
                                icon: 'error',
                                loader: true,
                                position: 'mid-center',
                                hideAfter: 2000,
                                stack: false
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })
    });

    $('.btnsimpantransaksi').click(function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Transaksi Selesai',
            html: `Yakin disimpan transaksi <strong>Pemakaian Barang</strong> ini ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#5ded40',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, <i class="fa fa-check"></i> Simpan !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('pemakaian/simpantransaki') ?>",
                    data: {
                        faktur: $('#faktur').val(),
                        noakun: $('#akun').val(),
                        tgl: $('#tgl').val(),
                        total: $('#totalsubtotal').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            $.toast({
                                heading: 'Error',
                                text: response.error,
                                icon: 'error',
                                loader: true,
                                position: 'mid-center',
                                hideAfter: 2000,
                                stack: false
                            });
                        }

                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                html: response.sukses,
                            }).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })


    });
});
</script>