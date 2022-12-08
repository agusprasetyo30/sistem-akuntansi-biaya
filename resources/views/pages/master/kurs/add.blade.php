<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Kurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tanggal_awal">Tanggal <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="tanggal" placeholder="Bulan-Tahun" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kurs  <span class="text-red">*</span></label>
                                <div class="input-icon">
                                    <span class="input-icon-addon text-primary">
                                        <p>Rp</p>
                                    </span>
                                    <input class="form-control" type="text" name="currency" id="currency" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="1.000.000.00">
                                </div>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
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
