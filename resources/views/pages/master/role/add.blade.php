<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Role</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Role </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Role" name="role" id="role" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <div class="form-label">Permissions</div>
                                <div class="custom-control-stacked">
                                    @foreach($permission as $value)
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input permissioncheck" name="permission[]" id="{{$value->id}}" value="{{$value->id}}">
                                        <span class="custom-control-label">{{$value->name}}</span>
                                    </label>
                                    @endforeach
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
