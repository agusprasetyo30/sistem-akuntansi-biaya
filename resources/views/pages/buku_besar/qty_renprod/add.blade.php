<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Kuantiti Rencana Produksi</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="form-group">
                                <label class="form-label">Versi Asumsi</label>
                                <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi</label>
                                <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bulan</label>
                                <select name="detail_version" id="data_detail_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center</label>
                                <select name="main_cost_center" id="data_main_cost_center" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Cost Center</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value" name="qty_renprod_value"
                                    id="qty_renprod_value" autocomplete="off">
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