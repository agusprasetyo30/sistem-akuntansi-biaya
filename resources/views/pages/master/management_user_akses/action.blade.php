@if (mapping_akses('management_role','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('management_role','update'))
    <a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('management_role','delete'))
    <a  class="btn bg-danger-transparent" onclick="delete_management_user_akses({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Mapping Role dan Menu</h5>
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
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Role" value="{{$model->nama_role}}" name="detail_nama_role"
                                    id="detail_nama_role" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Menu </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Menu" value="{{$model->feature_name}}" name="detail_feature_name"
                                    id="detail_feature_name" autocomplete="off">
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="data_main_menu" class="form-label">Akses Menu</label>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Create
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_create" id="akses_create" class="custom-switch-input" disabled {{ $model->create ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Read
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_read" id="akses_read" class="custom-switch-input" disabled {{ $model->read ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Update
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_update" id="akses_update" class="custom-switch-input" disabled {{ $model->update ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Delete
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_delete" id="akses_delete" class="custom-switch-input" disabled {{ $model->delete ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Approve
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_approve" id="akses_approve" class="custom-switch-input" disabled {{ $model->approve ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Submit
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="akses_submit" id="akses_submit" class="custom-switch-input" disabled {{ $model->submit ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
                <h5 class="modal-title" id="largemodal1">Edit Mapping Role dan Menu</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="data_main_role" class="form-label">Role</label>
                                <select name="main_role" id="edit_data_main_role{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->role_id}}" selected>{{$model->nama_role}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data_main_menu" class="form-label">Menu</label>
                                <select name="main_menu" id="edit_data_main_menu{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->kode_unik}}" selected>{{$model->feature_name}}</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="data_main_menu" class="form-label">Akses Menu</label>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Create
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_create" id="edit_create{{$model->id}}" class="custom-switch-input" {{ $model->create ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Read
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_read" id="edit_read{{$model->id}}" class="custom-switch-input" {{ $model->read ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Update
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_update" id="edit_update{{$model->id}}" class="custom-switch-input" {{ $model->update ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Delete
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_delete" id="edit_delete{{$model->id}}" class="custom-switch-input" {{ $model->delete ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Approve
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_approve" id="edit_approve{{$model->id}}" class="custom-switch-input" {{ $model->approve ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Submit
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="edit_submit" id="edit_submit{{$model->id}}" class="custom-switch-input" {{ $model->submit ? "checked" : "" }}>
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_management_user_akses({{$model->id}})" class="btn btn-primary">Simpan</button>
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

    $('#edit_data_main_menu'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
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

</script>