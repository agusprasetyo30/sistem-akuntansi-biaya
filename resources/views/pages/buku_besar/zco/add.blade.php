<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah ZCO</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="main_version" id="data_main_version"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label class="form-label">Bulan <span class="text-red">*</span></label>
                                <select name="detail_version" id="data_detal_version" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label>Periode </label>
                                <input type="text" class="form-control" id="data_detal_version" placeholder="Periode"
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Plant</label>
                                <select name="main_plant" id="data_main_plant"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Plant</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Produk</label>
                                <select name="main_produk" id="data_main_produk"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Produk</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Produk Qty </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    name="product_qty" id="product_qty" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Element</label>
                                <select name="main_cost_element" id="data_main_cost_element"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Cost Element</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="data_main_material"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Material</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Qty </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    name="total_qty" id="total_qty" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Currency </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    name="currency" id="currency" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Amount </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    name="total_amount" id="total_amount" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Unit Price Produk </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    name="unit_price_product" id="unit_price_product" autocomplete="off">
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
