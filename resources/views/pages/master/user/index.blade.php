@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Management Users</h4>
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
                <div class="card-header">
                    <div class="card-title">Management Users</div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="table-wrapper">
                            <table id="dt_users" class="table table-bordered text-nowrap key-buttons" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th data-type='text' data-name='nama' class="border-bottom-0 text-center">NAMA</th>
                                    <th data-type='text' data-name='username' class="border-bottom-0 text-center">USERNAME</th>
                                    <th data-type='text' data-name='role' class="border-bottom-0 text-center">ROLE</th>
                                    <th data-type='text' data-name='action' class="border-bottom-0 text-center">ACTION</th>
                                </tr>
                                <tr>
                                    <th data-type='text' data-name='nama' class="text-center"></th>
                                    <th data-type='text' data-name='username' class="text-center"></th>
                                    <th data-type='select' data-name='role' class="text-center"></th>
                                    <th data-type='text' data-name='action' class="text-center"></th>
                                </tr>
                                </thead>
                            </table>
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
        $(document).ready(function () {
            get_data()

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

        })

        function get_data(){
            $('#dt_users').DataTable().clear().destroy();
            $("#dt_users").DataTable({
                scrollX: true,
                dom: 'Bfrtip',
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
                    'pageLength', 'csv', 'pdf', 'excel', 'print'
                ],
                ajax: {
                    url : '{{route("user")}}',
                    data: {data:'index'}
                },
                columns: [
                    { data: 'name', name: 'users.name', orderable:false},
                    { data: 'username', name: 'users.username', orderable:false},
                    { data: 'nama_role', name: 'role.nama_role', orderable:false},
                    { data: 'action', name: 'action', orderable:false, searchable: false},

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
                        url: '{{route('insert_user')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            nama: $('#nama').val(),
                            username: $('#username').val(),
                            role: $('#data_main_role').val(),
                            metode: $('#login_method').val(),
                            email: $('#email').val(),
                        },
                        success:function (response) {
                            if (response.Code === 200){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("")
                                $('#data_main_role').val('').trigger("change");
                                $('#login_method').val('').trigger("change");
                                $('#username').removeClass('is-invalid');
                                $('#username').removeClass('is-valid');
                                $('#email').removeClass('is-invalid');
                                $('#email').removeClass('is-valid');
                                toastr.success('Data Berhasil Disimpan', 'Success')
                                get_data()
                            }else if (response.Code === 0){
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("");
                                $('#data_main_role').val('').trigger("change");
                                $('#login_method').val('').trigger("change");
                                $('#username').removeClass('is-invalid');
                                $('#username').removeClass('is-valid');
                                $('#email').removeClass('is-invalid');
                                $('#email').removeClass('is-valid');
                                toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                            }else {
                                $('#modal_add').modal('hide');
                                $("#modal_add input").val("");
                                $('#data_main_role').val('').trigger("change");
                                $('#login_method').val('').trigger("change");
                                $('#username').removeClass('is-invalid');
                                $('#username').removeClass('is-valid');
                                $('#email').removeClass('is-invalid');
                                $('#email').removeClass('is-valid');
                                toastr.error('Terdapat Kesalahan System', 'System Error')
                            }
                        }
                    })

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
                        $('#submit').prop('disabled', 'true');
                    }else {
                        toastr.error('Terdapat Kesalahan System', 'System Error')
                    }
                }
            })
        })

        $('#email').on('keyup', function () {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('helper_email')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    search: $('#email').val(),
                },
                success:function (response) {
                    if (response.Code === 200){
                        $('#email').removeClass('is-invalid');
                        $('#email').addClass('is-valid');
                    }else if (response.Code === 201){
                        $('#email').removeClass('is-valid');
                        $('#email').addClass('is-invalid');
                        $('#submit').prop('disabled', 'true');
                    }else {
                        toastr.error('Terdapat Kesalahan System', 'System Error')
                    }
                }
            })
        })

        function update_user(id) {
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
                        url: '{{route('update_user')}}',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            nama: $('#edit_name'+id).val(),
                            username: $('#edit_username'+id).val(),
                            role: $('#edit_data_main_role'+id).val(),
                            metode: $('#edit_login_method'+id).val(),
                            email: $('#edit_email'+id).val(),
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