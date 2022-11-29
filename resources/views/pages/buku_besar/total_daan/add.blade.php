<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Total Pengadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Material</option>
                                </select>
                            </div>                   
                            <div class="form-group">
                                <label class="form-label">Periode</label>
                                <select name="main_periode" id="data_main_periode" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Periode</option>
                                </select>
                            </div>                   
                            <div class="form-group">
                                <label class="form-label">Region</label>
                                <select name="main_region" id="data_main_region" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Region</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi" name="total_daan_desc"
                                    id="total_daan_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value" name="total_daan_value"
                                    id="total_daan_value" autocomplete="off">
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