<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_kurs({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Kurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="tanggal_awal">Tanggal <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->month}}-{{$model->year}}" id="detail_tanggal{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kurs  <span class="text-red">*</span></label>
                                <input disabled class="form-control" type="text" name="currency" id="detail_currency{{$model->id}}" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="{{rupiah($model->usd_rate)}}" data-type="currency" placeholder="1.000.000.00">
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
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

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="tanggal_awal">Tanggal <span class="text-red">*</span></label>
                                <input value="{{$model->month}}-{{$model->year}}" type="text" class="form-control" id="edit_tanggal{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kurs  <span class="text-red">*</span></label>
                                <div class="input-icon">
                                    <span class="input-icon-addon text-primary">
                                        <p>Rp</p>
                                    </span>
                                    <input class="form-control" type="text" name="currency" id="edit_currency{{$model->id}}" autocomplete="off" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="{{rupiah($model->usd_rate)}}" data-type="currency" placeholder="1.000.000.00">
                                </div>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_kurs({{$model->id}})" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_tanggal'+{{$model->id}}).bootstrapdatepicker({
        dropdownParent:$('#modal_edit'+{{$model->id}}),
        format: "MM-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose:true
    });

    $('#edit_currency'+{{$model->id}}).on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });
</script>
