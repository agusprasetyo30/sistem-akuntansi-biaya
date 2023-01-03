@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">Material</h4>
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
                        
                    </div>
                </div>
            </div>
            @include('pages.master.material.add')
            @include('pages.master.material.import')
        </div> 
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#is_active').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#is_dummy').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#kategori_material_id').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Kategori',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('kategori_material_select') }}",
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

            $('#group_account_code').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Group Account',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('group_account_select') }}",
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

            $('#material_code').keyup(function(){
                this.value = this.value.toUpperCase();
            });
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_material" class="table table-bordered text-nowrap key-buttons" style="width: 150%;">
                <thead>
                <tr>
                    <th data-type='text' data-name='code' class="text-center">CODE</th>
                    <th data-type='text' data-name='nama' class="text-center">NAMA</th>
                    <th data-type='text' data-name='deskripsi' class="text-center">DESKRIPSI</th>
                    <th data-type='select' data-name='group_account' class="text-center">GROUP ACCOUNT</th>
                    <th data-type='select' data-name='kategori_material' class="text-center">KATEGORI</th>
                    <th data-type='text' data-name='uom' class="text-center">UOM</th>
                    <th data-type='select' data-name='status' class="text-center">STATUS</th>
                    <th data-type='select' data-name='dummy' class="text-center">DUMMY</th>
                    <th data-type='text' data-name='nomor' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_material thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_material thead');

            $('#dt_material').DataTable().clear().destroy();
            $("#dt_material").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
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

                    this.api().eq(0).columns().every(function (index) {
                        var column = this;
                        var cell = $('.filters th').eq($(column.column(index).header()).index());
                        var data_type = this.header().getAttribute('data-type');
                        var iName = this.header().getAttribute('data-name');
                        var isSearchable = column.settings()[0].aoColumns[index].bSearchable;
                        if (isSearchable){
                            if (data_type == 'text'){
                                var input = document.createElement("input");
                                input.className = "form-control";
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
                                    @foreach (status_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach

                                } else if (iName == 'dummy'){
                                    input.className = "dummy_search form-control custom-select select2";
                                    @foreach (dummy_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach

                                } else if(iName == 'group_account'){
                                    input.className = "group_account_search form-control custom-select select2";

                                } else if(iName == 'kategori_material'){
                                    input.className = "kategori_material_search form-control custom-select select2";

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

                        $('.dummy_search').select2({
                            placeholder: 'Pilih Dummy',
                            width: '100%',
                            allowClear: false,
                        })

                        $('.group_account_search').select2({
                            placeholder: 'Pilih Group Account',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('group_account_dt') }}",
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

                        $('.kategori_material_search').select2({
                            placeholder: 'Pilih Kategori Material',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('kategori_material_dt') }}",
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

                    });
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2,3,4,5,6,7]
                        }, title: 'Matrial'}
                ],
                ajax: {
                    url : '{{route("material")}}',
                    data: {data:'index'}
                },
                columns: [
                    // { data: 'DT_RowIndex', name: 'material_code', searchable: false, orderable:false},
                    { data: 'material_code', name: 'material_code', orderable:true},
                    { data: 'material_name', name: 'material_name', orderable:true},
                    { data: 'material_desc', name: 'material_desc', orderable:true},
                    { data: 'group_account_desc', name: 'filter_group_account', orderable:true},
                    { data: 'kategori_material_name', name: 'filter_kategori_material', orderable:true},
                    { data: 'material_uom', name: 'material_uom', orderable:true},
                    { data: 'status', name: 'filter_status', orderable:false},
                    { data: 'dummy', name: 'filter_dummy', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,6,7,8]}
                ],

            })
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_material')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    material_code: $('#material_code').val(),
                    material_name: $('#material_name').val(),
                    material_desc: $('#material_desc').val(),
                    group_account_code: $('#group_account_code').val(),
                    kategori_material_id: $('#kategori_material_id').val(),
                    material_uom: $('#material_uom').val(),
                    is_active: $('#is_active').val(),
                    is_dummy: $('#is_dummy').val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            table()
                            $('#modal_add').modal('hide')
                            $("#modal_add input").val("")
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        })

        $('#submit-import').on('click', function () {
            $("#submit-import").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back-import").attr("disabled", true);
            let file = new FormData($("#form-input")[0]);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                url: '{{route('import_material')}}',
                data: file,
                success:function (response) {
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            table()
                            $('#modal_import').modal('hide')
                            $("#modal_import input").val("")
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit-import").attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back-import").attr("disabled", false);
                }
            })
        })

        function update_material(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_material')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    material_code: $('#edit_material_code'+id).val(),
                    material_name: $('#edit_material_name'+id).val(),
                    material_desc: $('#edit_material_desc'+id).val(),
                    group_account_code: $('#edit_group_account_code'+id).val(),
                    kategori_material_id: $('#edit_kategori_material_id'+id).val(),
                    material_uom: $('#edit_material_uom'+id).val(),
                    is_active: $('#edit_is_active'+id).val(),
                    is_dummy: $('#edit_is_dummy'+id).val(),
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false,
                        confirmButtonColor: "#019267",
                        confirmButtonText: 'Konfirmasi',
                    })
                    .then((result) => {
                        if (result.value) {
                            table()
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }

        function delete_material(id) {
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
                        url: '{{route('delete_material')}}',
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
                                confirmButtonColor: "#019267",
                                confirmButtonText: 'Konfirmasi',
                            })
                            .then((result) => {
                                if (result.value) {
                                    table()
                                }
                            })
                        },
                        error: function (response) {
                            handleError(response)
                        }
                    })

                }

            })
        }
    </script>
@endsection
