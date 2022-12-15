<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Consumption Ratio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Kode Plant <span class="text-red">*</span></label>
                                <select name="main_plant" id="data_main_plant" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bulan <span class="text-red">*</span></label>
                                <select name="detail_version" id="data_detal_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Produk <span class="text-red">*</span></label>
                                <select name="main_produk" id="data_main_produk" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material <span class="text-red">*</span></label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Produk Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Consumption Ratio (%) <span class="text-red">*</span></label>
                                <input class="form-control" type="number" placeholder="0" required name="consrate" id="consrate" min="0" step="0.01" title="consrate" pattern="^\d+(?:\.\d{1,2})?$">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status <span class="text-red">*</span></label>
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
