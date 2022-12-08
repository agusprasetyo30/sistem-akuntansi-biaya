<!-- Modal -->
<div class="modal fade" id="modal_import" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Import Kuantiti Rencana Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form-input" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mt1">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <div class="form-group">
                                    <select name="version" id="version" class="form-control form-control-sm custom-select select2 select2-hidden-accessible">
                                        <option value="" disabled selected></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div> --}}
                                <div class="col-lg mb-3">
                                    <b>Ketentuan :</b>
                                    <ol>
                                        <li>Format harus sesuai template 
                                                <select name="version" id="version" class="form-control form-control-sm custom-select select2 select2-hidden-accessible">
                                                    <option value="" disabled selected></option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                            ( <a href="#" id="submit-export" >Download Template</a> )
                                        </li>
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
                <button type="button" id="submit-import" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->
