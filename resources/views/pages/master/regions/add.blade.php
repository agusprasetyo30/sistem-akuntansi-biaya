<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Region</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Region </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Region" name="nama_region" id="nama_region" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Region Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Region" name="deskripsi_region" id="deskripsi_region" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Latitude </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Latitude" name="latitude" id="latitude" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Longtitude </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Longtitude" name="longtitude" id="longtitude" autocomplete="off">
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
