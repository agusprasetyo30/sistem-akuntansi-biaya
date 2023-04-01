<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah SALR</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Group Account FC <span class="text-red">*</span></label>
                                <select name="main_ga_account" id="data_main_ga_account" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Group Account FC</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">General Ledger Account FC <span class="text-red">*</span></label>
                                <select name="main_gl_account" id="data_main_gl_account" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Group Account FC Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center <span class="text-red">*</span></label>
                                <select name="main_cost_center" id="data_main_cost_center" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Cost Center</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="main_version" id="data_main_version_add" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_awal">Bulan <span class="text-red">*</span></label>
                                <input type="text" class="form-control" name="data_detail_version_add" id="data_detail_version_add" placeholder="Bulan" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Value <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="0" required name="value" id="value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama </label>
                                <input class="form-control" type="text" placeholder="Masukkan Nama" name="nama" id="nama" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Partner Cost Center </label>
                                <select name="main_partner_cost_center" id="data_main_partner_cost_center" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Partner Cost Center</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">UserName </label>
                                <input class="form-control" type="text" placeholder="Masukkan Username" name="username" id="username" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Material</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number </label>
                                <input class="form-control" type="text" placeholder="Masukkan Dokumen Number" name="document_num" id="document_num" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number Deskripsi </label>
                                <input class="form-control" type="text" placeholder="Masukkan Dokumen Number Deskripsi" name="document_num_desc" id="document_num_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Purchase Order </label>
                                <input class="form-control" type="text" placeholder="Masukkan Purchase Order" name="purchase_order" id="purchase_order" autocomplete="off">
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
