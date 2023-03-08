<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_role({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
<a  class="btn bg-success-transparent" title="permission" data-bs-toggle="modal" data-bs-target="{{__('#modal_give_permission'.$model->id)}}"><i class="fe fe-unlock"></i></a>

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Role </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Role" value="{{$model->name}}" name="detail_role" id="detail_role" autocomplete="off">
                            </div>
                            {{-- <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" id="detail_is_active" class="form-control form-control-sm custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
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
                <h5 class="modal-title" id="largemodal1">Edit Role</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Role </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Role"  value="{{$model->name}}" name="role" id="edit_role{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <div class="form-label">Permissions</div>
                                <div class="custom-control-stacked">
                                    {{-- @foreach($permission as $value)
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input permissioncheck" name="permission[]" id="{{$value->id}}" value="{{$value->id}}">
                                        <span class="custom-control-label">{{$value->name}}</span>
                                    </label>
                                    @endforeach --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_role({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->


<!-- Modal Permission-->
<div class="modal fade" id="{{__('modal_give_permission'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Give Permission Role</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Role </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Role"  value="{{$model->name}}" name="give_permission" id="give_permission{{$model->id}}" autocomplete="off" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Permission</label>
                                <select name="permission" id="permission{{$model->id}}" class="form-control custom-select select2">
                                    <option value="" selected>Pilih Permission</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_give_permission{{$model->id}}" onclick="give_permission({{$model->id}})" class="btn btn-primary">Give Permission</button>
                    <button type="button" id="submit_revoke_permission{{$model->id}}" onclick="revoke_permission({{$model->id}})" class="btn btn-warning">Revoke Permission</button>
                    <button type="button" id="back_give_permission{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#permission'+'{{$model->id}}').select2({
        dropdownParent: $('#modal_give_permission'+'{{$model->id}}'),
        placeholder: 'Pilih Permission',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('permission_select') }}",
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
</script>
