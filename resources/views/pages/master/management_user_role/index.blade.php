@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Mapping User Role</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('mapping_role','create'))
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
            @include('pages.master.management_user_role.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        $(document).ready(function () {
            table()

            $('#login_method').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Metode',
                width: '100%'
            })

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

            $('#data_main_user').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih User',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{ route('user_select') }}",
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
            console.log( $('#create').val())
            $("#submit-data").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('insert_user_role')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    user: $('#data_main_user').val(),
                    role: $('#data_main_role').val(),
                    metode: $('#login_method').val(),
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
                            $('#data_main_user').val('').trigger("change");
                            $('#data_main_role').val('').trigger("change");
                            $('#login_method').val('').trigger("change");
                            $("#submit-data").attr('class', 'btn btn-primary').attr("disabled", false);
                            get_data()
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
            <table id="dt_management_user_role" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                <thead>
                <tr>
                    <th data-type='text' data-name='user_id' class="text-center">USER</th>
                    <th data-type='text' data-name='role_id' class="text-center">ROLE</th>
                    <th data-type='text' data-name='action' class="text-center">ACTION</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>`

            get_data()
        }

        function get_data(){
            $('#dt_management_user_role thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_management_user_role thead');

            $('#dt_management_user_role').DataTable().clear().destroy();
            $("#dt_management_user_role").DataTable({
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
                    url : '{{route("user_role")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'name', name: 'users.name', orderable:true},
                    { data: 'nama_role', name: 'role.nama_role', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2]}
                ]

            })
        }

        function update_management_user_role(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_user_role')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    user: $('#edit_data_main_user'+id).val(),
                    role: $('#edit_data_main_role'+id).val(),
                    metode: $('#edit_login_method'+id).val(),
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

        function delete_management_user_role(id) {
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
                        url: '{{route('delete_user_role')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function (response) {
                            Swal.fire({
                                title: response.title,
                                text: response.msg,
                                icon: response.type,
                                allowOutsideClick: false
                            })
                                .then((result) => {
                                    if (result.value) {
                                        get_data()
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