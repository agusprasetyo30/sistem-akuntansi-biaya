<!-- Modal -->
<div class="modal fade" id="modal_import" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Import Tarif</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <form method="POST" id="form-input" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mt1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-lg mb-3">
                                    <b>Ketentuan :</b>
                                    <ol>
                                        <li>Format harus sesuai template</li>
                                        <li>Sistem akan memproses sheet pertama saja</li>
                                    </ol> 
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Versi Asumsi</label>
                                    <select name="version" id="version" class="form-control custom-select select2">
                                        <option value="" disabled selected>Pilih Versi</option>
                                    </select>
                                    <button style="display: none;" type="button" class="btn btn-primary mt-2" id="submit-export"><i class="fe fe-download me-2"></i>Download Template</button>
                                </div>
                                <div class="input-group file-browser mb-5">
                                    <input type="file" name="file" id="file" class="form-control" aria-label="file example" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit-import" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back-import" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
