<!-- Modal -->
<div class="modal fade" id="modal_import" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Import Regions</h5>
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
                                        <li>Format harus sesuai template  ( <a href="{{ route('export_regions') }}">Download Template</a> )</li>
                                        <li>Sistem akan memproses sheet pertama saja</li>
                                    </ol>
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
                    <button type="button" id="submit_import" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_import" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
