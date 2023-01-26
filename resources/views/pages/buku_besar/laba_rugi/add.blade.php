<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Price Pengadaan</h5>
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
                                <label class="form-label">Bulan <span class="text-red">*</span></label>
                                <select name="detail_version" id="data_detal_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Material</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Region</label>
                                <select name="main_region" id="data_main_region" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Region</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Format Mata Uang <span class="text-red">*</span></label>
                                <select name="data_main_mata_uang" id="data_main_mata_uang" class="form-control custom-select select2">
                                    <option selected disabled value="">Pilih Format Mata Uang</option>
                                    @foreach (mata_uang() as $key => $value)
                                        options += '<option value="{{ $key }}">{{ ucwords($value) }}</option>';
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="value_pick" style="display: none;">
                                <label class="form-label">Value <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="0" required name="price_rendaan_value" id="price_rendaan_value" autocomplete="off">
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
