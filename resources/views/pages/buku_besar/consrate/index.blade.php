@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Consumption Ratio</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_import" class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
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
                    <div class="card-title">COST CENTER</div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table-wrapper">
                            <table id="dt_consrate" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                    <th data-type='text' data-name='code' class="border-bottom-0 text-center">CODE PLANT</th>
                                    <th data-type='text' data-name='version' class="border-bottom-0 text-center">VERSION - PERIODE</th>
                                    <th data-type='text' data-name='produk' class="border-bottom-0 text-center">PRODUK</th>
                                    <th data-type='text' data-name='material' class="border-bottom-0 text-center">MATERIAL</th>
                                    <th data-type='text' data-name='uom' class="border-bottom-0 text-center">UOM</th>
                                    <th data-type='text' data-name='consrate' class="border-bottom-0 text-center">CONSRATE</th>
                                    <th data-type='select' data-name='status' class="border-bottom-0 text-center">STATUS</th>
                                    <th data-type='text' data-name='action' class="border-bottom-0 text-center">ACTION</th>
                                </tr>
                                <tr>
                                    <th data-type='text' data-name='nomor' class="text-center"></th>
                                    <th data-type='text' data-name='code' class="text-center"></th>
                                    <th data-type='text' data-name='version' class="text-center"></th>
                                    <th data-type='text' data-name='produk' class="text-center"></th>
                                    <th data-type='text' data-name='material' class="text-center"></th>
                                    <th data-type='text' data-name='uom' class="text-center"></th>
                                    <th data-type='text' data-name='consrate' class="text-center"></th>
                                    <th data-type='select' data-name='status' class="text-center"></th>
                                    <th data-type='text' data-name='action' class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.consrate.add')
            @include('pages.buku_besar.consrate.import')
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
                placeholder: 'Pilih Status',
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

            $('#submit_import').on('click', function () {
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
                        let file = new FormData($("#form-input-consrate")[0]);
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            processData: false,
                            contentType: false,
                            url: '{{route('import_consrate')}}',
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
                })
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

            $('#data_main_produk').select2({
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
            }).on('change', function () {
                var data_produk = $('#data_main_produk').val();
                $('#data_main_material').append('<option selected disabled value="">Pilih Material</option>').select2({
                    dropdownParent: $('#modal_add'),
                    placeholder: 'Pilih Material',
                    width: '100%',
                    allowClear: false,
                    ajax: {
                        url: "{{ route('material_keyword_select') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                produk:data_produk

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

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })
        })

        function get_data(){
            $('#dt_consrate').DataTable().clear().destroy();
            $("#dt_consrate").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                // searching: false,
                sortable: false,
                processing: true,
                serverSide: true,
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

                    this.api().columns().every(function (index) {
                        var column = this;
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control";
                                input.styleName = "width: 100%;";
                                $(input).
                                appendTo($(column.header()).empty()).
                                on('change clear', function () {
                                    column.search($(this).val(), false, false, true).draw();
                                });
                            }else if (data_type == 'select'){
                                var input = document.createElement("select");
                                input.className = "form-control custom-select select2";
                                var options = "";
                                if (iName == 'status'){
                                    options += '<option value="">Semua</option>';
                                    @foreach (status_is_active() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                }
                                input.innerHTML = options
                                $(input).appendTo($(column.header()).empty())
                                    .on('change clear', function () {
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
                    url : '{{route("consrate")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'plant_code', name: 'plant_code', orderable:false},
                    { data: 'version_periode', name: 'filter_version_periode', orderable:false},
                    { data: 'product', name: 'filter_product', orderable:false},
                    { data: 'material', name: 'filter_material', orderable:false},
                    { data: 'uom', name: 'filter_uom', orderable:false},
                    { data: 'cons_rate', name: 'cons_rate', orderable:false},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [4,5]}
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
                        url: '{{route('insert_consrate')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_plant: $('#data_main_plant').val(),
                            version: $('#data_main_version').val(),
                            id_asumsi: $('#data_detal_version').val(),
                            produk: $('#data_main_produk').val(),
                            material: $('#data_main_material').val(),
                            consrate: $('#consrate').val(),
                            is_active: $('#is_active').val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#data_main_plant').val('').trigger("change");
                                $('#data_main_version').val('').trigger("change");
                                $('#data_detal_version').val('').trigger("change");
                                $('#data_main_produk').val('').trigger("change");
                                $('#data_main_material').val('').trigger("change");
                                $('#is_active').val('').trigger("change");
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#data_main_plant').val('').trigger("change");
                                $('#data_main_version').val('').trigger("change");
                                $('#data_detal_version').val('').trigger("change");
                                $('#data_main_produk').val('').trigger("change");
                                $('#data_main_material').val('').trigger("change");
                                $('#is_active').val('').trigger("change");
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#data_main_plant').val('').trigger("change");
                                $('#data_main_version').val('').trigger("change");
                                $('#data_detal_version').val('').trigger("change");
                                $('#data_main_produk').val('').trigger("change");
                                $('#data_main_material').val('').trigger("change");
                                $('#is_active').val('').trigger("change");
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }


                        }
                    })

                }

            })
        })

        function update_consrate(id) {
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
                        url: '{{route('update_consrate')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            id_plant: $('#edit_data_main_plant'+id).val(),
                            version: $('#edit_data_main_version'+id).val(),
                            id_asumsi: $('#edit_data_detal_version'+id).val(),
                            produk: $('#edit_data_main_produk'+id).val(),
                            material: $('#edit_data_main_material'+id).val(),
                            consrate: $('#edit_consrate'+id).val(),
                            is_active: $('#edit_is_active'+id).val(),
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

        function delete_consrate(id) {
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
                        url: '{{route('delete_consrate')}}',
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
    {{--    <script src="{{asset('assets/js/pages/regions.js')}}"></script>--}}
@endsection
