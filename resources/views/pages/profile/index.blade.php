@extends('layouts.app')

@section('styles')

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0 text-primary">Hallo {{$data_id->name}}</h4>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="box-widget widget-user text-center">
                        <div class="widget-user-image mx-auto">
                            <img alt="User Avatar" class="rounded-circle" src="{{asset('assets/images/users/2.jpg')}}">
                        </div>
                        <div class="mt-4 ms-sm-5 ms-0">
                            <h4 class="pro-user-username mb-2 font-weight-bold">{{$data_id->name}}</h4>
                            <p>{{$data_id->company_code}} - {{$data_id->company_name}}</p>
                            <div>
                                @foreach($data_role as $items)
                                    @if($items->role_id == 1)
                                        <span class="badge fs-13 bg-success-transparent text-success border-success me-2">{{$items->nama_role}}</span>
                                    @elseif($items->role_id == 2)
                                        <span class="badge fs-13 bg-info-transparent text-info border-info me-2">{{$items->nama_role}}</span>
                                    @elseif($items->role_id == 3)
                                        <span class="badge fs-13 bg-primary-transparent text-primary border-primary me-2">{{$items->nama_role}}</span>
                                    @elseif($items->role_id == 4)
                                        <span class="badge fs-13 bg-warning-transparent text-warning border-warning me-2">{{$items->nama_role}}</span>
                                    @elseif($items->role_id == 5)
                                        <span class="badge fs-13 bg-danger-transparent text-danger border-danger me-2">{{$items->nama_role}}</span>
                                    @else
                                        <span class="badge fs-13 bg-secondary-transparent text-secondary border-secondary me-2">{{$items->nama_role}}</span>
                                    @endif
                                @endforeach
                            </div>
                            <button title="Ganti Password" data-bs-toggle="modal" data-bs-target="#modal_edit" class="btn btn-warning mt-3" id="btn_ganti_password"><i class="fa fa-key"></i> Ganti Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
    <!-- Modal Detail-->
    <div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_edit" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">Ganti Password</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 mt1">
                        <div class="row">
                            <div class="col-md-12" style="text-align: start;">
                                <div class="form-group">
                                    <label>Password Lama</label>
                                    <div class="input-group" id="Password-toggle">
                                        <button id="toggle_old_pass" class="input-group-text">
                                            <i id="icon_old_pass" class="fe fe-eye" aria-hidden="true"></i>
                                        </button>
                                        <input id="old_pass" class="form-control" type="password"
                                               name="old_pass" required autocomplete="off"
                                               placeholder="Masukkan Password Lama Anda">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Password Baru </label>
                                    <div class="input-group" id="Password-toggle">
                                        <button id="toggle_new_pass" class="input-group-text">
                                            <i id="icon_new_pass" class="fe fe-eye" aria-hidden="true"></i>
                                        </button>
                                        <input id="new_pass" class="form-control" type="password"
                                               name="new_pass" required autocomplete="off"
                                               placeholder="Masukkan Password Baru Anda">
                                        <button class="btn btn btn-primary br-tl-0 br-bl-0" id="generate_pass">Generate Password</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Password </label>
                                    <div class="input-group" id="Password-toggle">
                                        <button id="toggle_confirm_pass" class="input-group-text">
                                            <i id="icon_confirm_pass" class="fe fe-eye" aria-hidden="true"></i>
                                        </button>
                                        <input id="confirm_pass" class="form-control" type="password"
                                               name="confirm_pass" required autocomplete="off"
                                               placeholder="Konfirmasi Password Baru Anda">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-list btn-animation">
                        <button type="button" id="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->



@endsection()

@section('scripts')
    <script>

        $(document).ready(function () {
            $('#toggle_old_pass').on('click', function () {
                const type = $('#old_pass').attr('type') === 'password' ? 'text':'password'
                if (type === 'password'){
                    $('#icon_old_pass').attr('class', 'fe fe-eye')
                }
                else {
                    $('#icon_old_pass').attr('class', 'fe fe-eye-off')
                }

                $('#old_pass').attr('type', type)
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

        $('#submit').on('click', function () {
            $("#submit").attr('class', 'btn btn-primary btn-loaders btn-icon').attr("disabled", true);
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('check_pass')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: '{{$data_id->id}}',
                    old_pass:$('#old_pass').val(),
                    new_pass:$('#new_pass').val(),
                    confirm_pass:$('#confirm_pass').val(),
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
                                $('#modal_edit').modal('hide');
                                $("#modal_edit input").val("")
                                $('#icon_old_pass').attr('class', 'fe fe-eye')
                                $('#icon_new_pass').attr('class', 'fe fe-eye')
                                $('#icon_confirm_pass').attr('class', 'fe fe-eye')
                                $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            }
                        })
                    }else if (response.code === 201){
                        Swal.fire({
                            title: 'Password Salah',
                            text: "Cek kembali password lama anda!",
                            icon: 'warning',
                            confirmButtonColor: '#019267',
                            cancelButtonColor: '#EF4B4B',
                            confirmButtonText: 'Konfirmasi',
                        }).then((result)=>{
                            if (result.value) {
                                $("#submit").attr('class', 'btn btn-primary').attr("disabled", false);
                            }
                        })
                    }else if (response.code === 202){
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
                }
            })
        })

    </script>
@endsection
