<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Mapping User Role</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="data_main_user" class="form-label">User</label>
                                <select name="main_user" id="data_main_user" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data_main_role" class="form-label">Role</label>
                                <select name="main_role" id="data_main_role" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Role</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="login_method" class="form-label">Metode</label>
                                <select name="login_method" id="login_method" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Metode</option>
                                    @foreach (login_method() as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit-data" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
