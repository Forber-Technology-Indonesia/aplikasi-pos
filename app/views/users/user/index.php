@extends(layouts/index)
@section(style)
<link rel="stylesheet" href="<?= assets() ?>plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= assets() ?>plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= assets() ?>plugins/datatables/responsive/rowReorder.dataTables.min.css">
<link rel="stylesheet" href="<?= assets() ?>plugins/select2/select2.min.css">
@endsection
@section(content)
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-primary create">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap data-table" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Grup</th>
                        <th>Status</th>
                        <th style="width: 10%;">#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="tampil-modal"></div>
@endsection
@section(script)
<script src="<?= assets() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= assets() ?>plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= assets() ?>plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<script src="<?= assets() ?>plugins/select2/select2.min.js"></script>
<script>
    $(document).ready(function() {
        table = $('.data-table').DataTable({
            'responsive': true,
            'aaSorting': [
                [1, "asc"]
            ],
            'processing': true,
            'serverSide': true,
            'displayLength': 10,
            'lengthMenu': [10, 25, 50, 100],
            'ajax': {
                'url': BASE_URL + 'users/data',
                'method': 'post'
            },
            'columns': [{
                    data: 'no',
                    class: 'text-center',
                    orderable: false
                },
                {
                    data: 'nama_user',
                    class: 'text-left'
                },
                {
                    data: 'username',
                    class: 'text-left',
                    orderable: false,
                },
                {
                    data: 'group',
                    class: 'text-left',
                    orderable: false,
                },
                {
                    data: 'status',
                    class: 'text-center',
                    orderable: false,
                },
                {
                    data: 'button',
                    class: 'text-center',
                    orderable: false,
                }
            ]
        });
    });

    $(document).on('click', '.create', function() {
        $.post(BASE_URL + 'users/create', function(resp) {
            $('#tampil-modal').html(resp);
            $('#modal-form').modal('show');
        });
    });

    $(document).on('click', '.edit', function(e) {
        $.post(BASE_URL + 'users/edit', {
            'id': $(this).attr('id')
        }, function(resp) {
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('click', '.destroy', function() {
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Hapus Data User',
            text: 'Yakin dihapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                $.post(BASE_URL + 'users/destroy', {
                    'id': id
                }, function(response) {
                    var resp = eval('(' + response + ')');
                    if (resp.status == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            text: resp.message,
                            icon: 'success',
                            loader: true,
                        });
                        var DataTabel = $('.data-table').DataTable();
                        DataTabel.ajax.reload();
                    } else {
                        Swal.fire('Oops...', resp.message, 'error');
                    }
                });
            }
        })
    });

    $(document).on('submit', '.form_data', function(e) {
        event.preventDefault();
        var formData = new FormData($('.form_data')[0]);
        $.ajax({
            url: $('.form_data').attr('action'),
            dataType: 'json',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('.store_data').attr('disabled', 'disabled');
                $('.store_data').html('<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
            },
            success: function(resp) {
                $('.form-group').removeClass('has-error').find('.help-block').remove();
                if (resp.status == 'success') {
                    $('#modal-form').modal('hide');
                    $.toast({
                        heading: 'Success!',
                        text: resp.message,
                        icon: 'success',
                        loader: true,
                    });
                    var DataTabel = $('.data-table').DataTable();
                    DataTabel.ajax.reload();
                } else {
                    $.each(resp.error, function(key, value) {
                        var element = $('#' + key);
                        element.closest('div.form-group')
                            .removeClass('has-error')
                            .addClass(value.length > 0 ? 'has-error' : 'has-success')
                            .find('.help-block')
                            .remove();
                        element.after('<div class="help-block">' + value + '</div>');
                    });
                }
            },
            complete: function() {
                $('.store_data').removeAttr('disabled');
                $('.store_data').html('Simpan');
            }
        })
    });
</script>
@endsection