<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Kategori Balans</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal_awal">kategori Balans <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="kategori_balans" placeholder="Masukkan Kategori Produk" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">kategori Balans Deskripsi <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="Masukkan Kategori Produk Deskripsi" required name="kategori_balans_desc" id="kategori_balans_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Type Kategori Balans</label>
                                <select name="type_kategori_balans" id="type_kategori_balans" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Tipe kategori</option>
                                    <option value="produksi">Produksi</option>
                                    <option value="pemakaian">Pemakaian</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Urutan</label>
                                <select name="urutan" id="urutan" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
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
