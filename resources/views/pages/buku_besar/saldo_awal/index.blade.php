@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Saldo Awal</h4>
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
            {{-- <div class="card-header">
                <div class="card-title">Basic DataTable</div>
            </div> --}}
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_saldo_awal" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='month_year' class="border-bottom-0 text-center">PERIODE</th>
                                <th data-type='text' data-name='gl_account' class="border-bottom-0 text-center">G/L ACCOUNT</th>
                                <th data-type='text' data-name='valuation_class' class="border-bottom-0 text-center">VALUATION CLASS</th>
                                <th data-type='text' data-name='price_control' class="border-bottom-0 text-center">PRICE CONTROL</th>
                                <th data-type='text' data-name='material_name' class="border-bottom-0 text-center">MATERIAL</th>
                                <th data-type='text' data-name='plant' class="border-bottom-0 text-center">PLANT</th>
                                <th data-type='text' data-name='total_stock' class="border-bottom-0 text-center">TOTAL STOCK</th>
                                <th data-type='text' data-name='total_value' class="border-bottom-0 text-center">TOTAL VALUE</th>
                                <th data-type='text' data-name='nilai_satuan' class="border-bottom-0 text-center">NILAI SATUAN</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='month_year' class="text-center"></th>
                                <th data-type='text' data-name='gl_account' class="text-center"></th>
                                <th data-type='text' data-name='valuation_class' class="text-center"></th>
                                <th data-type='text' data-name='price_control' class="text-center"></th>
                                <th data-type='text' data-name='material_name' class="text-center"></th>
                                <th data-type='text' data-name='plant' class="text-center"></th>
                                <th data-type='text' data-name='total_stock' class="text-center"></th>
                                <th data-type='text' data-name='total_value' class="text-center"></th>
                                <th data-type='text' data-name='nilai_satuan' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.saldo_awal.add')
            @include('pages.buku_besar.saldo_awal.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            get_data()

            $('#data_main_plant').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Plant',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('plant_select') }}",
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
            })

            $('#total_value').on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });

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
                $("#submit-export").css("display", "block");
            })

            nilai_satuan()

        })

        function nilai_satuan(){
            let total = $('#total_value').val()
            let stok = $('#total_stock').val()
            let res = total.substring(3)
            let result = res.replaceAll(".", "")
            let nilsa = parseInt(result) / parseInt(stok) || 0
            $("#nilai_satuan").val(nilsa)
        }

        function get_data(){
            $('#dt_saldo_awal').DataTable().clear().destroy();
            $("#dt_saldo_awal").DataTable({
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
                    url : '{{route("saldo_awal")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'month_year', name: 'month_year', orderable:false},
                    { data: 'gl_account', name: 'gl_account', orderable:false},
                    { data: 'valuation_class', name: 'valuation_class', orderable:false},
                    { data: 'price_control', name: 'price_control', orderable:false},
                    { data: 'material_name', name: 'material.material_name', orderable:false},
                    { data: 'plant_code', name: 'plant.plant_code', orderable:false},
                    { data: 'total_stock', name: 'total_stock', orderable:false},
                    { data: 'total_value', name: 'total_value', orderable:false},
                    { data: 'nilai_satuan', name: 'nilai_satuan', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,9]}
                ],

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
                        url: '{{route('insert_saldo_awal')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            version_id: $('#data_main_version').val(),
                            gl_account: $('#gl_account').val(),
                            valuation_class: $('#valuation_class').val(),
                            price_control: $('#price_control').val(),
                            material_code: $('#data_main_material').val(),
                            plant_code: $('#data_main_plant').val(),
                            total_stock: $('#total_stock').val(),
                            total_value: $('#total_value').val(),
                            nilai_satuan: $('#nilai_satuan').val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#is_active').val('').trigger("change");
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 400){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                toastr.warning(response.msg, 'Warning')
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

        // $('#submit-import').on('click', function () {
        //     Swal.fire({
        //         title: 'Apakah anda yakin?',
        //         text: "Data akan segera import",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#019267',
        //         cancelButtonColor: '#EF4B4B',
        //         confirmButtonText: 'Konfirmasi',
        //         cancelButtonText: 'Kembali'
        //     }).then((result) =>{
        //         importStore()
        //         // if (result.value){
        //         //     let file = new FormData($("#form-input")[0]);
        //         //     $.ajax({
        //         //         type: "POST",
        //         //         headers: {
        //         //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         //         },
        //         //         processData: false,
        //         //         contentType: false,
        //         //         url: '{{route('import_saldo_awal')}}',
        //         //         data: file,
        //         //         success:function (response) {
        //         //             if (response.Code === 200){
        //         //                 $('#modal_import').modal('hide');
        //         //                 $("#modal_import input").val("")
        //         //                 $('#is_active').val('').trigger("change");
        //         //                 toastr.success('Data Berhasil Disimpan', 'Success')
        //         //                 get_data()
        //         //             }else if (response.Code === 0){
        //         //                 $('#modal_import').modal('hide');
        //         //                 $("#modal_import input").val("")
        //         //                 toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
        //         //             }else if (response.Code === 400){
        //         //                 $('#modal_import').modal('hide');
        //         //                 $("#modal_import input").val("")
        //         //                 toastr.warning(response.msg, 'Warning')
        //         //             }else if (response.Code === 500){
        //         //                 $('#modal_import').modal('hide');
        //         //                 $("#modal_import input").val("")
        //         //                 response.msg.forEach(element => {
        //         //                     toastr.warning(element, 'Warning')
        //         //                 });
        //         //             }else {
        //         //                 $('#modal_import').modal('hide');
        //         //                 $("#modal_import input").val("")
        //         //                 toastr.error('Terdapat Kesalahan System', 'System Error')
        //         //             }
        //         //         }
        //         //     })
        //         // }
        //     })
        // })

        $('#submit-import').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_saldo_awal')}}',
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
        })

        function importStore() {
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_saldo_awal')}}',
                data: file,
                success:function (response) {
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
                    }else if (response.Code === 400){
                        $('#modal_import').modal('hide');
                        $("#modal_import input").val("")
                        toastr.warning(response.msg, 'Warning')
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
                },
                error: function(){
                    $('#modal_import').modal('hide');
                    $("#modal_import input").val("")
                    toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                }
            })
        }

        $('#submit-export').on('click', function () {
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('export_saldo_awal')}}',
                data: {
                    version: $('#version').val(),
                },
                success:function (result, status, xhr) {
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'saldo_awal.xlsx');

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
                },
                error: function(){
                    $('#modal_import').modal('hide');
                    $("#modal_import input").val("")
                    toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                }
            })
        })

        function update_saldo_awal(id) {
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
                        url: '{{route('update_saldo_awal')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            version_id: $('#edit_data_main_version'+id).val(),
                            gl_account: $('#edit_gl_account'+id).val(),
                            valuation_class: $('#edit_valuation_class'+id).val(),
                            price_control: $('#edit_price_control'+id).val(),
                            material_code: $('#edit_data_main_material'+id).val(),
                            plant_code: $('#edit_data_main_plant'+id).val(),
                            total_stock: $('#edit_total_stock'+id).val(),
                            total_value: $('#edit_total_value'+id).val(),
                            nilai_satuan: $('#edit_nilai_satuan'+id).val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_edit'+id).modal('hide');
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 400){
                                $('#modal_edit'+id).modal('hide');
                                toastr.warning(response.msg, 'Warning')
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

        function delete_saldo_awal(id) {
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
                        url: '{{route('delete_saldo_awal')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                toastr.success('Data Berhasil Dihapus', 'Success')
                                get_data()
                            }else if (response.Code === 502){
                                toastr.warning(response.msg, 'Warning')
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
