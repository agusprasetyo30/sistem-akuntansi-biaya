@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Mapping Role dan Menu</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('management_role','create'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add"  class="btn btn-primary btn-pill" id="btn-tambah"><i class="fa fa-plus me-2 fs-14"></i> Add</button>
                @endif
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- <div class="card-title">Management Role</div> --}}
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table-wrapper">

                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.management_user_akses.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#data_main_role').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Role',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('role_select') }}",
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

            $('#data_main_menu').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Menu',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('menu_select') }}",
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
            
            $('#data_main_company_code').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('company_filter_select') }}",
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

        $('#submit-data').on('click', function () {
            $("#submit-data").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_user_akses')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    role: $('#data_main_role').val(),
                    menu: $('#data_main_menu').val(),
                    create: $('#add_akses_create').prop('checked') == true ? 1 : 0,
                    read: $('#add_akses_read').prop('checked') == true ? 1 : 0,
                    update: $('#add_akses_update').prop('checked') == true ? 1 : 0,
                    delete: $('#add_akses_delete').prop('checked') == true ? 1 : 0,
                    approve: $('#add_akses_approve').prop('checked') == true ? 1 : 0,
                    submit: $('#add_akses_submit').prop('checked') == true ? 1 : 0,
                    company_code: $('#data_main_company_code').val(),
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
                            $('#data_main_role').val('').trigger("change");
                            $('#data_main_menu').val('').trigger("change");
                            $("#submit-data").attr('class', 'btn btn-primary').attr("disabled", false);
                            get_data()
                            location.reload();
                        }
                    })
                },
                error:function (response) {
                    handleError(response)
                    $("#submit-data").attr('class', 'btn btn-primary').attr("disabled", false);
                }
            })
        })

        function table (){
            document.getElementById('table-wrapper').innerHTML = `
            <table id="dt_management_role" class="table table-bordered text-nowrap key-buttons" style="width: 200%;">
                <thead>
                <tr>
                    <th data-type='text' data-name='role_id' class="text-center">ROLE</th>
                    <th data-type='text' data-name='feature_name' class="text-center">MENU</th>
                    <th data-type='text' data-name='company_code' class="text-center">ACCESS COMPANY</th>
                    <th data-type='text' data-name='create' class="text-center">ACCESS CREATE</th>
                    <th data-type='text' data-name='read' class="text-center">ACCESS READ</th>
                    <th data-type='text' data-name='update' class="text-center">ACCESS UPDATE</th>
                    <th data-type='text' data-name='delete' class="text-center">ACCESS DELETE</th>
                    <th data-type='text' data-name='approve' class="text-center">ACCESS APPROVE</th>
                    <th data-type='text' data-name='submit' class="text-center">ACCESS SUBMIT</th>
                    <th data-type='text' data-name='action' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_management_role thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_management_role thead');

            $('#dt_management_role').DataTable().clear().destroy();
            $("#dt_management_role").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                orderCellsTop: true,
                autoWidth:true,
                scrollCollapse: true,
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

                                input.innerHTML = options
                                $(input).appendTo(cell.empty())
                                    .on('change clear', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });

                            }
                        }else {
                            cell.empty()
                        }
                    });
                },
                buttons: [
                    'pageLength', 'excel'
                ],
                ajax: {
                    url : '{{route("user_akses")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'nama_role', name: 'role.nama_role', orderable:true},
                    { data: 'feature_name', name: 'feature.feature_name', orderable:true},
                    { data: 'company_code', name: 'company_code', orderable:false, searchable: false},
                    { data: 'create', name: 'filter_create', orderable:false, searchable: false},
                    { data: 'read', name: 'filter_read', orderable:false, searchable: false},
                    { data: 'update', name: 'filter_update', orderable:false, searchable: false},
                    { data: 'delete', name: 'filter_delete', orderable:false, searchable: false},
                    { data: 'approve', name: 'filter_approve', orderable:false, searchable: false},
                    { data: 'submit', name: 'filter_submit', orderable:false, searchable: false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2,3,4,5,6,7,8]}
                ]

            })
        }

        function update_management_user_akses(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_user_akses')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    role: $('#edit_data_main_role'+id).val(),
                    menu: $('#edit_data_main_menu'+id).val(),
                    create: $('#edit_create'+id).prop('checked') == true ? 1 : 0,
                    read: $('#edit_read'+id).prop('checked') == true ? 1 : 0,
                    update: $('#edit_update'+id).prop('checked') == true ? 1 : 0,
                    delete: $('#edit_delete'+id).prop('checked') == true ? 1 : 0,
                    approve: $('#edit_approve'+id).prop('checked') == true ? 1 : 0,
                    submit: $('#edit_submit'+id).prop('checked') == true ? 1 : 0,
                    company_code: $('#edit_data_main_company_code'+id).val(),
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
                                $("#submit_edit").attr('class', 'btn btn-primary').attr("disabled", false);
                                get_data()
                                location.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                }
            })
        }

        function delete_management_user_akses(id) {
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
                        url: '{{route('delete_user_akses')}}',
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
                                        get_data()
                                        location.reload();
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