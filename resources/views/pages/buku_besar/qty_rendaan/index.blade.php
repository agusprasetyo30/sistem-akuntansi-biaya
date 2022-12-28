@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Kuantiti Rencana Pengadaan</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <div class="card-title">Basic Kuantiti Rencana Pengadaan</div>
            </div> --}}
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_qty_rendaan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='material_id' class="border-bottom-0 text-center">MATERIAL</th>
                                <th data-type='text' data-name='periode_id' class="border-bottom-0 text-center">VERSION</th>
                                <th data-type='text' data-name='region' class="border-bottom-0 text-center">REGION</th>
                                <th data-type='text' data-name='qty_rendaan_value' class="border-bottom-0 text-center">VALUE</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='material_id' class="text-center"></th>
                                <th data-type='text' data-name='periode_id' class="text-center"></th>
                                <th data-type='text' data-name='region' class="text-center"></th>
                                <th data-type='text' data-name='qty_rendaan_value' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.qty_rendaan.add')
            @include('pages.buku_besar.qty_rendaan.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            get_data()

            $('#data_main_version').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            }).on('change', function () {
                var data_version = $('#data_main_version').val();
                $('#data_detal_version').append('<option selected disabled value="">Pilih Bulan</option>').select2({
                    dropdownParent: $('#modal_add'),
                    placeholder: 'Pilih Bulan',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('version_detail_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                version:data_version

                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        }
                    }
                });
            })

            $('#version').select2({
                dropdownParent: $('#modal_import'),
                placeholder: 'Pilih Versi',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('version_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            }).on('change', function () {
                $("#template").css("display", "block");
            })

            $("#template").on('click', function () {
                $.ajax({
                     type: "POST",
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     xhrFields:{
                         responseType: 'blob'
                     },
                     url: '{{route('export_qty_rendaan')}}',
                     data: {
                         temp:$('#version').val()
                     },
                     success: function(result, status, xhr) {

                         var disposition = xhr.getResponseHeader('content-disposition');
                         var matches = /"([^"]*)"/.exec(disposition);
                         var filename = (matches != null && matches[1] ? matches[1] : 'qty_rendaan.xlsx');

                         // The actual download
                         var blob = new Blob([result], {
                             type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                         });
                         var link = document.createElement('a');
                         link.href = window.URL.createObjectURL(blob);
                         link.download = filename;

                         document.body.appendChild(link);

                         link.click();
                         document.body.removeChild(link);
                     }
                 })
             })

            $('#data_main_material').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Material',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('material_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            })

            $('#data_main_region').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih region',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('region_select') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    }
                }
            })

            $('#submit_import').on('click', function () {
                var versi = $('#version').val();
                if (versi === null){
                    toastr.warning('Data Versi Asumsi Harus diisi', 'Warning')
                }else {
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{route('check_qty_rendaan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version:$('#version').val()
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                Swal.fire({
                                    title: 'Apakah anda yakin?',
                                    text: "Data akan segera dikirim",
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
                            }else if (response.Code === 201){
                                Swal.fire({
                                    title: 'Apakah anda yakin?',
                                    text: "Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?",
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
                            }
                        }
                    })
                }
            })

            function importStore() {
                let file = new FormData($("#form-input-consrate")[0]);
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    url: '{{route('import_qty_rendaan')}}',
                    data: file,
                    success:function (response) {
                        console.log(response);
                        if (response.Code === 200){
                            $('#modal_import').modal('hide');
                            $("#modal_import input").val("")
                            $('#is_active').val('').trigger("change");
                            toastr.success('Data Berhasil Disimpan', 'Success')
                            get_data()
                        }else if (response.Code === 0){
                            $('#modal_import').modal('hide');
                            $("#modal_import input").val("")
                            toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                        }else if (response.Code === 500){
                            $('#modal_import').modal('hide');
                            $("#modal_import input").val("")
                            response.msg.forEach(element => {
                                toastr.warning(element, 'Warning')
                            });
                        }else {
                            $('#modal_import').modal('hide');
                            $("#modal_import input").val("")
                            toastr.error('Terdapat Kesalahan System', 'System Error')
                        }
                    }
                })
            }

            $('#qty_rendaan_value').on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });
        })

        function get_data(){
            $('#dt_qty_rendaan').DataTable().clear().destroy();
            $("#dt_qty_rendaan").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                // sortable: false,
                // searching: false,
                processing: true,
                serverSide: true,
                order:[[0, 'desc']],
                fixedHeader: {
                    header: true,
                    headerOffset: $('#main_header').height()
                },
                initComplete: function () {
                        $('.dataTables_scrollHead').css('overflow', 'auto');
                        $('.dataTables_scrollHead').on('scroll', function () {
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });

                    $(document).on('scroll', function () {
                        $('.dtfh-floatingparenthead').on('scroll', function () {
                            $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                        });
                    })

                    this.api().columns().every(function (index) {
                        var column = this;
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control form-control-sm";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo($(column.header()).empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }
                        }

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5' }
                ],
                ajax: {
                    url : '{{route("qty_rendaan")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'material', name: 'filter_material', orderable:false},
                    { data: 'version_periode', name: 'filter_version_periode', orderable:false},
                    { data: 'region_name', name: 'filter_region', orderable:false},
                    { data: 'value', name: 'qty_rendaan_value', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5]}
                ]

            })
        }


        $('#submit').on('click', function () {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera dikirim",
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
                        url: '{{route('insert_qty_rendaan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version_asumsi:$('#data_main_version').val(),
                            bulan:$('#data_detal_version').val(),
                            material_id: $('#data_main_material').val(),
                            region_id: $('#data_main_region').val(),
                            qty_rendaan_value: $('#qty_rendaan_value').val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#is_active').val('').trigger("change");
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }


                        }
                    })

                }

            })
        })

        function update_qty_rendaan(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan segera disimpan",
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
                        url: '{{route('update_qty_rendaan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            version_asumsi:$('#edit_data_main_version'+id).val(),
                            bulan:$('#edit_data_detal_version'+id).val(),
                            material_id: $('#edit_data_main_material'+id).val(),
                            region_id: $('#edit_data_main_region'+id).val(),
                            qty_rendaan_value: $('#edit_qty_rendaan_value'+id).val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_edit'+id).modal('hide');
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_edit'+id).modal('hide');
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_edit'+id).modal('hide');
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

                }

            })
        }

        function delete_qty_rendaan(id) {
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
                        url: '{{route('delete_qty_rendaan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                toastr.success('Data Berhasil Dihapus', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

                }

            })
        }
    </script>
@endsection
