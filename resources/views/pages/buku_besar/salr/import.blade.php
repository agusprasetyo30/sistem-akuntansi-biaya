<!-- Modal -->
<div class="modal fade" id="modal_import" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Import SALR</h5>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_input_salr" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mt1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-lg mb-5">
                                    <b>Ketentuan :</b>
                                    <ol>
                                        <li>Format harus sesuai template</li>
                                        <li>Template akan tersedia setelah memilih versi</li>
                                        <li>Jika data value "kosong" maka data akan terisi '0'</li>
                                        <li>Sistem akan memproses sheet pertama saja</li>
                                    </ol>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                    <select name="main_version" id="data_main_version_import" class="form-control custom-select select2">
                                        <option value="" disabled selected>Pilih Versi</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_awal">Bulan <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="bulan_periode" id="bulan_periode" placeholder="Bulan" autocomplete="off" required>
                                    <button style="display: none;" type="button" class="btn btn-primary mt-2" id="template"><i class="fe fe-download me-2"></i>Download Template</button>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label">Bulan <span class="text-red">*</span></label>--}}
{{--                                    <select name="detail_version" id="data_detail_version_import" class="form-control custom-select select2">--}}
{{--                                        <option value="" disabled selected>Pilih Version Terlebih Dahulu</option>--}}
{{--                                    </select>--}}
{{--                                    <button style="display: none;" type="button" class="btn btn-primary mt-2" id="template"><i class="fe fe-download me-2"></i>Download Template</button>--}}
{{--                                </div>--}}
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
