<!-- Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="largemodal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Mapping Role dan Menu</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="data_main_role" class="form-label">Role</label>
                                <select name="main_role" id="data_main_role" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Role</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data_main_menu" class="form-label">Menu</label>
                                <select name="main_menu" id="data_main_menu" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Menu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="data_main_company_code" class="form-label">Perusahaan</label>
                                <select name="main_company_code" id="data_main_company_code" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Perusahaan</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="data_main_menu" class="form-label">Akses Menu</label>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Create
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_create" id="add_akses_create" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Read
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_read" id="add_akses_read" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Update
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_update" id="add_akses_update" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Delete
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_delete" id="add_akses_delete" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Approve
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_approve" id="add_akses_approve" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                            <div class="row g-xs">
												<div class="col-4">
                                                    Submit
												</div>
												<div class="col-8">
													<div class="form-group">
                                                        <label class="custom-switch">
                                                            <span class="custom-switch-description me-2">Tidak</span>
                                                            <input type="checkbox" name="add_akses_submit" id="add_akses_submit" class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                            <span class="custom-switch-description">Iya</span>
                                                        </label>
                                                    </div>
												</div>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit-data" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
