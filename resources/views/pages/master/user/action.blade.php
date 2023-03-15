@if (mapping_akses('users','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('users','update'))
    @if($model->role_id != 1 and auth()->user()->id != $model->id)
        <button type="button" class="btn bg-success-transparent" title="Ganti Password" data-bs-toggle="modal" data-bs-target="{{__('#ganti_password'.$model->id)}}"><i class="fe fe-unlock"></i></button>
    @endif
    
    <a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('users','delete'))
    <a  class="btn bg-danger-transparent" onclick="delete_user({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Users</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Nama </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Nama" value="{{$model->name}}" name="detail_nama" id="detail_nama" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Username </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Username" value="{{$model->username}}" name="detail_username" id="detail_username" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Company </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Username" value="{{$model->company_code}} - {{$model->company_name}}" name="detail_username" id="detail_username" autocomplete="off">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label>Role </label>--}}
{{--                                <input disabled type="text" class="form-control form-control-sm" placeholder="Role" value="{{$model->nama_role}}" name="detail_role" id="detail_role" autocomplete="off">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Metode Login </label>--}}
{{--                                <input disabled type="text" class="form-control form-control-sm" placeholder="Metode Login" value="{{$model->login_method}}" name="detail_metode" id="detail_metode" autocomplete="off">--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
{{--                <button type="button" id="submit" class="btn btn-primary">Simpan</button>--}}
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Ganti Password-->
<div class="modal fade" id="{{__('ganti_password'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="ganti_password" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Ganti Password : {{$model->username}}</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Password </label>
                                <div class="input-group" id="Password-toggle">
                                    <button id="toggle_new_pass{{$model->id}}" class="input-group-text">
                                        <i id="icon_new_pass{{$model->id}}" class="fe fe-eye" aria-hidden="true"></i>
                                    </button>
                                    <input id="new_pass{{$model->id}}" class="form-control" type="password"
                                           name="new_pass" required autocomplete="off"
                                           placeholder="Masukkan Password Baru Anda">
                                    <button class="btn btn btn-primary br-tl-0 br-bl-0" id="generate_pass{{$model->id}}">Generate Password</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password </label>
                                <div class="input-group" id="Password-toggle">
                                    <button id="toggle_confirm_pass{{$model->id}}" class="input-group-text">
                                        <i id="icon_confirm_pass{{$model->id}}" class="fe fe-eye" aria-hidden="true"></i>
                                    </button>
                                    <input id="confirm_pass{{$model->id}}" class="form-control" type="password"
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
                    <button type="button" id="submit_password{{$model->id}}" onclick="update_password({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit_password{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
{{--            <div class="modal-footer">--}}
{{--                --}}{{--                <button type="button" id="submit" class="btn btn-primary">Simpan</button>--}}
{{--                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit User</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Masukkan Nama" value="{{$model->name}}" name="edit_name" id="edit_name{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="edit_username{{$model->id}}">Username </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Masukkan Username" value="{{$model->username}}" name="edit_username" id="edit_username{{$model->id}}" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Username sudah ada.
                                </div>
                            </div>
                            <div class="form-group" id="cost_center_pick">
                                <label class="form-label">Perusahaan <span class="text-red">*</span></label>
                                <select id="filter_company_code{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->company_code}}">{{$model->company_code}} - {{$model->company_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_user({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#filter_company_code'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
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

    $('#toggle_new_pass'+{{$model->id}}).on('click', function () {
        const type = $('#new_pass'+{{$model->id}}).attr('type') === 'password' ? 'text':'password'
        if (type === 'password'){
            $('#icon_new_pass'+{{$model->id}}).attr('class', 'fe fe-eye')
        }
        else {
            $('#icon_new_pass'+{{$model->id}}).attr('class', 'fe fe-eye-off')
        }

        $('#new_pass'+{{$model->id}}).attr('type', type)
    })

    $('#toggle_confirm_pass'+{{$model->id}}).on('click', function () {
        const type = $('#confirm_pass'+{{$model->id}}).attr('type') === 'password' ? 'text':'password'
        if (type === 'password'){
            $('#icon_confirm_pass'+{{$model->id}}).attr('class', 'fe fe-eye')
        }
        else {
            $('#icon_confirm_pass'+{{$model->id}}).attr('class', 'fe fe-eye-off')
        }

        $('#confirm_pass'+{{$model->id}}).attr('type', type)
    })

    $('#generate_pass'+{{$model->id}}).on('click', function () {
        generate_pass('{{$model->id}}')
    })

    function generate_pass(id) {
        var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var passwordLength = 8;
        var password = "";

        for (var i = 0; i <= passwordLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            password += chars.substring(randomNumber, randomNumber +1);
        }

        $('#new_pass'+id).val(password)
    }
</script>

