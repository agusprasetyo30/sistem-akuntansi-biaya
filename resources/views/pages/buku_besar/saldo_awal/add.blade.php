
<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi</label>
                                <select name="main_version" id="data_main_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>  
                            <div class="form-group">
                                <label>G/L Account </label>
                                <input type="text" class="form-control form-control-sm" placeholder="GL Account" name="gl_account"
                                    id="gl_account" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Valuation Class" name="valuation_class"
                                    id="valuation_class" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Price Control" name="price_control"
                                    id="price_control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>                          
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="data_main_plant" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total value" name="total_value"
                                    id="total_value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total Stock" name="total_stock"
                                    id="total_stock" autocomplete="off">
                            </div>
                            {{-- <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nilai Satuan" name="nilai_satuan"
                                    id="nilai_satuan" autocomplete="off">
                            </div> --}}
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
