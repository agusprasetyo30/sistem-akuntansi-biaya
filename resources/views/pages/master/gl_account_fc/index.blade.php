@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0 text-primary">General Ledger Account Fixed Cost</h4>
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
            @include('pages.master.gl_account_fc.add')
            @include('pages.master.gl_account_fc.import')
        </div>
    </div>
</div>
<!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#gl_account_fc').keyup(function(){
                this.value = this.value.toUpperCase();
            });

            $('#group_account_fc').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Group Account',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('group_account_fc_select') }}",
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

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_gl_account_fc" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                <tr>
                    <th data-type='text' data-name='code' class="text-center">CODE</th>
                    <th data-type='text' data-name='deskripsi' class="text-center">DESKRIPSI</th>
                    <th data-type='select' data-name='group_account_fc' class="text-center">GROUP ACCOUNT</th>
                    <th data-type='text' data-name='nomor' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>`

            get_data()
        }


        function get_data(){
            $('#dt_gl_account_fc thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_gl_account_fc thead');

            // $('#dt_gl_account_fc').DataTable().clear().destroy();
            $("#dt_gl_account_fc").DataTable({
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
                deferRender:true,
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
                                    @foreach (status_dt() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                } else if(iName == 'group_account_fc'){
                                    input.className = "group_account_fc_search form-control custom-select select2";

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

                        $('.group_account_fc_search').select2({
                            placeholder: 'Pilih Group Account',
                            allowClear: false,
                            ajax: {
                                url: "{{ route('group_account_fc_dt') }}",
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
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2]
                        }, title: '',
                        filename: 'General Ledger Account Fixed Cost'  
                    }
                ],
                ajax: {
                    url : '{{route("gl_account_fc")}}',
                    data: {data:'index'}
                },
                columns: [
                    // { data: 'DT_RowIndex', name: 'general_ledger_account', searchable: false, orderable:false},
                    { data: 'gl_account_fc', name: 'gl_account_fc', orderable:true},
                    { data: 'gl_account_fc_desc', name: 'gl_account_fc_desc', orderable:true},
                    { data: 'group_account_fc', name: 'filter_group_account_fc', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,2,3]}
                ],

            })
        }

        $('#submit').on('click', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_gl_account_fc')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    gl_account_fc: $('#gl_account_fc').val(),
                    gl_account_fc_desc: $('#gl_account_fc_desc').val(),
                    group_account_fc: $('#group_account_fc').val(),
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
                            $('#modal_add').modal('hide')
                            $("#modal_add input").val("")
                            // table()
                            $('#dt_gl_account_fc').DataTable().ajax.reload();
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
                url: '{{route('import_gl_account_fc')}}',
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
                            $('#modal_import').modal('hide')
                            $("#modal_import input").val("")
                            // table()
                            $('#dt_gl_account_fc').DataTable().ajax.reload();
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

        function update_gl_account_fc(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_gl_account_fc')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id : id,
                    gl_account_fc: $('#edit_gl_account_fc'+id).val(),
                    gl_account_fc_desc: $('#edit_gl_account_fc_desc'+id).val(),
                    group_account_fc: $('#edit_group_account_fc'+id).val(),
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
                            $('#modal_edit'+id).modal('hide')
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            // table()
                            $('#dt_gl_account_fc').DataTable().ajax.reload();
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }

        function delete_gl_account_fc(id) {
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
                        url: '{{route('delete_gl_account_fc')}}',
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
                                    // table()
                                    $('#dt_gl_account_fc').DataTable().ajax.reload();
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
