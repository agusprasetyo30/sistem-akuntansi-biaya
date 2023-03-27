<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Laba Rugi</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="main_version" id="data_main_version_add" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Versi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Produk</label>
                                <select name="data_main_kategori_produk" id="data_main_kategori_produk" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Kategori Produk</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Penjualan <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="Masukkan Biaya Penjualan" required name="biaya_penjualan" id="biaya_penjualan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Administrasi Umum <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="Masukkan Biaya Administrasi Umum" required name="biaya_administrasi_umum" id="biaya_administrasi_umum" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Biaya Bunga <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="Masukkan Biaya Bunga" required name="biaya_bunga" id="biaya_bunga" autocomplete="off">
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
