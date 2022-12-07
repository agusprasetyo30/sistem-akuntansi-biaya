<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Version & Asumsi Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="data_asumsi">
                    <ul>
                        <li><a href="#step-10">VERSION</a></li>
                        <li><a href="#step-11">ASUMSI UMUM</a></li>
                    </ul>
                    <div>
                        <div id="step-10" class="">
                            <form id="form-1" novalidate>
                                <div class="form-group">
                                    <label for="nama_versi">Nama Versi <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="nama_versi" placeholder="Ext : 202001" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_bulan">Jumlah Bulan <span class="text-red">*</span></label>
                                    <input type="number" min="1" max="12" class="form-control" id="jumlah_bulan" placeholder="Jumlah Bulan" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_awal">Tanggal Awal <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_awal" placeholder="Bulan-Tahun" required>
                                    <div class="valid-feedback">
                                        Terlihat Bagus!
                                    </div>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Harus Diisi.
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="step-11" class="">
                            <form class="asumsi_form" id="form-2" novalidate>
                                <div class="row" id="section_asumsi">
                                    <input readonly style="display:none;" type="text" value="" id="periode">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button disabled type="button" id="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

