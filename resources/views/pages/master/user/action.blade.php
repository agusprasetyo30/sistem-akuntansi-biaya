<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>

@if($model->role_id != 1 and auth()->user()->id != $model->id)
    <button type="button" class="btn bg-success-transparent" title="Ganti Password" data-bs-toggle="modal" data-bs-target=""><i class="fe fe-unlock"></i></button>
@endif
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_user({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


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
{{--                            <div class="form-group">--}}
{{--                                <label for="data_main_role" class="form-label">Role</label>--}}
{{--                                <select name="main_role" id="edit_data_main_role{{$model->id}}" class="form-control custom-select select2">--}}
{{--                                    <option value="{{$model->role_id}}" selected>{{$model->nama_role}}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-label">Metode Login</label>--}}
{{--                                <select name="edit_login_method" id="edit_login_method{{$model->id}}">--}}
{{--                                    <option value="" disabled selected>Pilih Status</option>--}}
{{--                                    @foreach (login_method() as $key => $value)--}}
{{--                                        <option value="{{ $key }}" {{ $key == $model->login_method ? "selected" : "" }}>{{ $value}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
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
    $('#edit_data_main_role'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Status',
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

    $('#edit_login_method'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Metode',
        width: '100%'
    })
</script>

