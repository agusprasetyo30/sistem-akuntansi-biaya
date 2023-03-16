@if (mapping_akses('mapping_role','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('mapping_role','update'))
    <a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('mapping_role','delete'))
    <a  class="btn bg-danger-transparent" onclick="delete_management_user_role({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Mapping User dan Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>User </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="User" value="{{$model->name}}" name="detail_name" id="detail_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Role </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Role" value="{{$model->nama_role}}" name="detail_role" id="detail_role" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Metode Login </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Metode Login" value="{{$model->login_method}}" name="detail_metode" id="detail_metode" autocomplete="off">
                            </div>
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
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Mapping User dan Role</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="data_main_user" class="form-label">User</label>
                                <select name="main_user" id="edit_data_main_user{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->user_id}}" selected>{{$model->name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data_main_role" class="form-label">Role</label>
                                <select name="main_role" id="edit_data_main_role{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->role_id}}" selected>{{$model->nama_role}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Metode Login</label>
                                <select name="edit_login_method" id="edit_login_method{{$model->id}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (login_method() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->login_method ? "selected" : "" }}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_management_user_role({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_data_main_user'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
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