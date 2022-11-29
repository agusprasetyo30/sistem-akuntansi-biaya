<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Masukkan Nama" name="nama" id="nama" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Masukkan Username" name="username" id="username" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Username sudah ada.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input type="email" class="form-control form-control-sm" placeholder="Masukkan Email" name="email" id="email" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Email sudah ada.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data_main_role" class="form-label">Role</label>
                                <select name="main_role" id="data_main_role" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Role</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="login_method" class="form-label">Metode</label>
                                <select name="login_method" id="login_method" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Metode</option>
                                    @foreach (login_method() as $key => $value)
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
