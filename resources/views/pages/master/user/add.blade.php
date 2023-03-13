<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Users</h5>
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
                                <label>Password </label>
                                <div class="input-group" id="Password-toggle">
                                    <button id="toggle_new_pass" class="input-group-text">
                                        <i id="icon_new_pass" class="fe fe-eye" aria-hidden="true"></i>
                                    </button>
                                    <input id="new_pass" class="form-control" type="password"
                                           name="new_pass" required autocomplete="off"
                                           placeholder="Masukkan Password Baru Anda">
                                    <button class="btn btn btn-primary br-tl-0 br-bl-0" id="generate_pass">Generate Password</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password </label>
                                <div class="input-group" id="Password-toggle">
                                    <button id="toggle_confirm_pass" class="input-group-text">
                                        <i id="icon_confirm_pass" class="fe fe-eye" aria-hidden="true"></i>
                                    </button>
                                    <input id="confirm_pass" class="form-control" type="password"
                                           name="confirm_pass" required autocomplete="off"
                                           placeholder="Konfirmasi Password Baru Anda">
                                </div>
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
