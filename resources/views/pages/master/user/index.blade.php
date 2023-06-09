@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Users</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                @if (mapping_akses('users','create'))
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
                    {{-- <div class="card-title">Management Users</div> --}}
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table_main">
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.master.user.add')
        </div>
    </div>
    <!-- /Row -->

@endsection()

@section('scripts')
    <script>
        var table_main_dt = '<table id="dt_users" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th data-type="text" data-name="nama" class="text-center">NAMA</th>' +
            '<th data-type="text" data-name="username" class="text-center">USERNAME</th>' +
            '<th data-type="text" data-name="role" class="text-center">COMPANY</th>' +
            '<th data-type="text" data-name="action" class="text-center">ACTION</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '</tbody>' +
            '</table>'

        $(document).ready(function () {
            get_data()

            $('#filter_company_code').select2({
                dropdownParent: $('#modal_add'),
                placeholder: 'Pilih Perusahaan',
                width: '100%',
                allowClear: false,
                ajax: {
                    url: "{{route('company_select') }}",
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

            $('#toggle_new_pass').on('click', function () {
                const type = $('#new_pass').attr('type') === 'password' ? 'text':'password'
                if (type === 'password'){
                    $('#icon_new_pass').attr('class', 'fe fe-eye')
                }
                else {
                    $('#icon_new_pass').attr('class', 'fe fe-eye-off')
                }

                $('#new_pass').attr('type', type)
            })

            $('#toggle_confirm_pass').on('click', function () {
                const type = $('#confirm_pass').attr('type') === 'password' ? 'text':'password'
                if (type === 'password'){
                    $('#icon_confirm_pass').attr('class', 'fe fe-eye')
                }
                else {
                    $('#icon_confirm_pass').attr('class', 'fe fe-eye-off')
                }

                $('#confirm_pass').attr('type', type)
            })

            $('#generate_pass').on('click', function () {
                generate_pass()
            })

            function generate_pass() {
                var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                var passwordLength = 8;
                var password = "";

                for (var i = 0; i <= passwordLength; i++) {
                    var randomNumber = Math.floor(Math.random() * chars.length);
                    password += chars.substring(randomNumber, randomNumber +1);
                }

                $('#new_pass').val(password)
            }
        })

        function get_data(){
            $('#table_main').html(table_main_dt)

            $('#dt_users thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#dt_users thead');


            $('#dt_users').DataTable().clear().destroy();
            $("#dt_users").DataTable({
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
                    this.api().columns.adjust().draw()
                },
                buttons: [
                    { extend: 'pageLength', className: 'mb-5' },
                    { extend: 'excel', className: 'mb-5', exportOptions:{
                        columns:[0,1,2]
                        }, title: '',
                        filename: 'Management Users'
                    }
                ],
                ajax: {
                    url : '{{route("user")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'name', name: 'users.name', orderable:true},
                    { data: 'username', name: 'users.username', orderable:true},
                    { data: 'company', name: 'filter_company', orderable:true},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

                ],
                columnDefs:[
                    {className: 'text-center', targets: [0,1,2]}
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
                url: '{{route('insert_user')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    nama: $('#nama').val(),
                    company: $('#filter_company_code').val(),
                    username: $('#username').val(),
                    new_pass: $('#new_pass').val(),
                    confirm_pass: $('#confirm_pass').val(),
                },
                success:function (response) {
                    if (response.code === 200){
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
                                $('#username').removeClass('is-invalid');
                                $('#username').removeClass('is-valid');
                                $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                                $('#dt_users').DataTable().ajax.reload();
                            }
                        })
                    }else if (response.code === 201){
                        Swal.fire({
                            title: 'Password Tidak Sama',
                            text: "Cek kembali password baru dan password konfirmasi anda!",
                            icon: 'warning',
                            confirmButtonColor: '#019267',
                            cancelButtonColor: '#EF4B4B',
                            confirmButtonText: 'Konfirmasi',
                        }).then((result)=>{
                            if (result.value) {
                                $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            }
                        })
                    }
                },
                error:function (response) {
                    handleError(response)
                    $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                    // $('#dt_users').DataTable().ajax.reload();
                }
            })
        })

        $('#username').on('keyup', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('helper_username')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    search: $('#username').val(),
                },
                success:function (response) {
                    if (response.Code === 200){
                        $('#username').removeClass('is-invalid');
                        $('#username').addClass('is-valid');
                    }else if (response.Code === 201){
                        $('#username').removeClass('is-valid');
                        $('#username').addClass('is-invalid');
                        // $('#submit').prop('disabled', 'true');
                    }else {
                        toastr.error('Terdapat Kesalahan System', 'System Error')
                    }
                }
            })
        })


        function update_user(id) {
            $("#submit_edit"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_user')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    nama: $('#edit_name'+id).val(),
                    username: $('#edit_username'+id).val(),
                    company: $('#filter_company_code'+id).val(),
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
                                $('#dt_users').DataTable().ajax.reload();
                            }
                        })
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_edit"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit"+id).attr("disabled", false);
                    // $('#dt_users').DataTable().ajax.reload();
                }
            })
        }

        function delete_user(id) {
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
                        url: '{{route('delete_user')}}',
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
                                        $('#dt_users').DataTable().ajax.reload();
                                    }
                                })
                        },
                        error: function (response) {
                            handleError(response)
                            // $('#dt_users').DataTable().ajax.reload();
                        }
                    })

                }

            })
        }

        function update_password(id){
            $("#submit_password"+id).attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $("#back_edit_password"+id).attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('update_user_password')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    new_pass: $('#new_pass'+id).val(),
                    confirm_pass: $('#confirm_pass'+id).val(),
                },
                success: function (response) {
                    if (response.code === 200){
                        Swal.fire({
                            title: response.title,
                            text: response.msg,
                            icon: response.type,
                            allowOutsideClick: false,
                            confirmButtonColor: '#019267',
                            confirmButtonText: 'Konfirmasi',
                        }).then((result)=>{
                            if (result.value) {
                                $('#ganti_password'+id).modal('hide')
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
                                $("#submit_password"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit_password"+id).attr("disabled", false);
                                $('#dt_users').DataTable().ajax.reload();
                            }
                        })
                    }else if (response.code === 201){
                        Swal.fire({
                            title: 'Password Tidak Sama',
                            text: "Cek kembali password baru dan password konfirmasi anda!",
                            icon: 'warning',
                            confirmButtonColor: '#019267',
                            cancelButtonColor: '#EF4B4B',
                            confirmButtonText: 'Konfirmasi',
                        }).then((result)=>{
                            if (result.value) {
                                $("#submit_password"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                                $("#back_edit_password"+id).attr("disabled", false);
                            }
                        })
                    }
                },
                error: function (response) {
                    handleError(response)
                    $("#submit_password"+id).attr('class', 'btn btn-primary').attr("disabled", false);
                    $("#back_edit_password"+id).attr("disabled", false);
                    // $('#dt_users').DataTable().ajax.reload();
                }
            })
        }
    </script>
@endsection
