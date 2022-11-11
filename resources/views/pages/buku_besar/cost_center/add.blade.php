<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Cost Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="data_main_plant" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Kode Cost Center" name="code_cost_center" id="code_cost_center" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Cost Center" name="cost_center_desc" id="cost_center_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->