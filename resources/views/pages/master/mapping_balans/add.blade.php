<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Mapping Kategori Balans</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material <span class="text-red">*</span></label>
                                <select name="data_main_material_balans" id="data_main_material_balans" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Material</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Balans <span class="text-red">*</span></label>
                                <select name="data_main_kategori_balans" id="data_main_kategori_balans" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Kategori Balans</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Plant <span class="text-red">*</span></label>
                                <select multiple="multiple" id="data_main_plant" class="form-control custom-select select2">
                                </select>
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
