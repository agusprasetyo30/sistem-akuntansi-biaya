@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Total Pengadaan</h4>
    </div>
    <div class="page-rightheader">
        <div class="btn-list">
            <button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>Import</button>
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
                <div class="card-title">Total Pengadaan</div>
            </div> --}}
            <div class="card-body">
                <div class="">
                    <div class="table-responsive" id="table-wrapper">
                        <table id="dt_total_daan" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">NO</th>
                                <th data-type='text' data-name='material_id' class="border-bottom-0 text-center">MATERIAL</th>
                                <th data-type='text' data-name='periode_id' class="border-bottom-0 text-center">PERIODE</th>
                                <th data-type='text' data-name='region_id' class="border-bottom-0 text-center">REGION</th>
                                <th data-type='text' data-name='total_daan_desc' class="border-bottom-0 text-center">DESKRIPSI</th>
                                <th data-type='text' data-name='total_daan_value' class="border-bottom-0 text-center">VALUE</th>
                                <th data-type='text' data-name='nomor' class="border-bottom-0 text-center">ACTION</th>
                            </tr>
                            <tr>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                                <th data-type='text' data-name='material_id' class="text-center"></th>
                                <th data-type='text' data-name='periode_id' class="text-center"></th>
                                <th data-type='text' data-name='region_id' class="text-center"></th>
                                <th data-type='text' data-name='total_daan_desc' class="text-center"></th>
                                <th data-type='text' data-name='total_daan_value' class="text-center"></th>
                                <th data-type='text' data-name='nomor' class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('pages.buku_besar.total_daan.add')
        </div> 
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            get_data()

            $('#data_main_periode').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih periode',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('periode_select') }}",
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
        })

        function get_data(){
            $('#dt_total_daan').DataTable().clear().destroy();
            $("#dt_total_daan").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                // sortable: false,
                searching: false,
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
                    url : '{{route("total_daan")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', searchable: false, orderable:true},
                    { data: 'material_name', name: 'material.material_name', orderable:false},
                    { data: 'periode_name', name: 'periode.periode_name', orderable:false},
                    { data: 'region_name', name: 'regions.region_name', orderable:false},
                    { data: 'total_daan_desc', name: 'total_daan_desc', orderable:false},
                    { data: 'total_daan_value', name: 'total_daan_value', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},
                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,6]}
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
                        url: '{{route('insert_total_daan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            material_id: $('#data_main_material').val(),
                            periode_id: $('#data_main_periode').val(),
                            region_id: $('#data_main_region').val(),
                            total_daan_desc: $('#total_daan_desc').val(),
                            total_daan_value: $('#total_daan_value').val(),
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

        function update_total_daan(id) {
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
                        url: '{{route('update_total_daan')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            material_id: $('#edit_data_main_material'+id).val(),
                            periode_id: $('#edit_data_main_periode'+id).val(),
                            region_id: $('#edit_data_main_region'+id).val(),
                            total_daan_desc: $('#edit_total_daan_desc'+id).val(),
                            total_daan_value: $('#edit_total_daan_value'+id).val(),
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

        function delete_total_daan(id) {
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
                        url: '{{route('delete_total_daan')}}',
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
