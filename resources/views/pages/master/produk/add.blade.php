<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama" name="produk_name" id="produk_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi" name="produk_desc" id="produk_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_produk_id" id="kategori_produk_id" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dummy</label>
                                <select name="is_dummy" id="is_dummy" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_dummy() as $key => $value)
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