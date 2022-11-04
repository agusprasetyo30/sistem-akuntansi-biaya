<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Asumsi Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Periode</label>
                                <select name="main_periode" id="data_main_periode" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Periode</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kurs </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Kurs" name="kursr" id="kurs" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Handling BB ( % )</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Handling BB" name="handling_bb" id="handling_bb" autocomplete="off">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label>Data Saldo Awal </label>--}}
{{--                                <input type="text" class="form-control form-control-sm" placeholder="Data Saldo Awal" name="data_saldo_awal" id="data_saldo_awal" autocomplete="off">--}}
{{--                            </div>--}}
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
