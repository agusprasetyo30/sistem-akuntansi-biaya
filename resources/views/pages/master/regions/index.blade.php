@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Regions</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import"  class="btn btn-outline-primary" id="btn-import"><i class="fe fe-download me-2"></i> Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Region</div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table_main">
                    </div>
                </div>
            </div>
        </div>
        @include('pages.master.regions.add')
        @include('pages.master.regions.import')
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_region" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="nama" class="text-center">KODE</th>' +
            '<th data-type="text" data-name="deskripsi" class="text-center">DESKRIPSI</th>' +
            '<th data-type="select" data-name="status" class="text-center">STATUS</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {

            get_data()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })
        })

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_region thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_region thead');

            // $('#dt_region').DataTable().clear().destroy();
            $("#dt_region").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                deferRender:true,
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                initComplete: function () {

                    $('.dataTables_scrollHead').css('overflow', 'auto');
                    $('.dataTables_scrollHead').on('scroll', function () {
                        // console.log('data')
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });

                    $(document).on('scroll', function () {
                        $('.dtfh-floatingparenthead').on('scroll', function () {
                            $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                        });
                    })

                    this.api().eq(0).columns().every(function (index) {
                        var column = this;
                        var cell = $('.filters th').eq($(column.column(index).header()).index());
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo(cell.empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                var options = "";
                                if (iName == 'status'){
                                    input.className = "status_search form-control custom-select select2";
                                    options += '<option value="">Semua</option>';
                                    @foreach (status_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                }
                                input.innerHTML = options
                                $(input).appendTo(cell.empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });

                            }
                        }else {
                            cell.empty()
                        }

                        $('.status_search').select2({
                            placeholder: 'Pilih Status',
                            width: '100%',
                            allowClear: false,
                        })

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3]
                        }, title: 'Regions' }
                ],
                ajax: {
                    url : '{{route("regions")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'region_name', name: 'region_name', orderable:true},
                    { data: 'region_desc', name: 'region_desc', orderable:true},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3]}
                ]
            })
        }

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_regions')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    nama: $('#nama_region').val(),
                    deskripsi: $('#deskripsi_region').val(),
                    latitude: $('#latitude').val(),
                    longtitude: $('#longtitude').val(),
                    is_active: $('#is_active').val(),
                },
                success:function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    }).then((result)=>{
                        if (result.value) {
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            $('#is_active').val('').trigger("change");
                            $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            // $("#table_main").empty();
                            // get_data()
                            $('#dt_region').DataTable().ajax.reload();

                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_region').DataTable().ajax.reload();
                }
            })
        })

        $('#submit_import').on('click', function () {
            $("#submit_import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_import").attr("disabled", true);
            if ($('#file').val() !== ''){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan segera import",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#019267',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonText: 'Konfirmasi',
                    cancelButtonText: 'Kembali'
                }).then((result) =>{
                    if (result.value){
                        importStore()
                    }
                })

            }else {
                Swal.fire({
                    title: 'PERINGATAN',
                    text: "Silakan Isi Data Tersebut",
                    icon: 'warning',
                    cancelButtonColor: '#EF4B4B',
                    confirmButtonColor: '#019267',
                    confirmButtonText: 'Konfirmasi',
                }).then((result)=>{
                    if (result.value){
                        $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                        $("#back_import").attr("disabled", false);
                    }
                })
            }

        })

        function importStore(){
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_regions')}}',
                data: file,
                success:function (response) {
                    $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    })
                        .then((result) => {
                            if (result.value) {
                                $('#modal_import').modal('hide');
                                $("#modal_import input").val("")
                                $('#is_active').val('').trigger("change");
                                // $("#table_main").empty();
                                // get_data()
                                $('#dt_region').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_import").attr("disabled", false);
                    // $('#dt_region').DataTable().ajax.reload();
                }
            })
        }

        function update_region(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_regions')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    nama: $('#edit_nama_region'+id).val(),
                    deskripsi: $('#edit_deskripsi_region'+id).val(),
                    latitude: $('#edit_latitude'+id).val(),
                    longtitude: $('#edit_longtitude'+id).val(),
                    is_active: $('#edit_is_active'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: '#019267',
                        confirmButtonText: 'Konfirmasi',
                    })
                        .then((result) => {
                            if (result.value) {
                                $('#modal_edit'+id).modal('hide')
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit"+id).attr("disabled", false);
                                // $("#table_main").empty();
                                // get_data()
                                $('#dt_region').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_region').DataTable().ajax.reload();
                }
            })
        }

        function delete_region(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#019267',
                cancelButtonColor: '#EF4B4B',
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Kembali'
            }).then((result) =>{
                if (result.value){

                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('delete_regions')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false,
                                confirmButtonColor: '#019267',
                                confirmButtonText: 'Konfirmasi',
                            })
                                .then((result) => {
                                    if (result.value) {
                                        // $("#table_main").empty();
                                        // get_data()
                                        $('#dt_region').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_region').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }
    </script>
@endsection
