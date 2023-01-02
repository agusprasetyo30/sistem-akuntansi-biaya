<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_kurs({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Kurs</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="tanggal_awal">Tanggal <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{format_month($model->month_year, 'se')}}" id="detail_tanggal{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off" required>
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
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit User</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="edit_tanggal{{$model->id}}">Tanggal <span class="text-red">*</span></label>
                                <input value="{{format_month($model->month_year, 'se')}}" type="text" class="form-control" id="edit_tanggal{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off" required>
                                <div class="valid-feedback">
                                    Terlihat Bagus!
                                </div>
                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                    Harus Diisi.
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kurs <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="0" required name="currency" id="edit_currency{{$model->id}}" value="{{rupiah($model->usd_rate)}}" autocomplete="off">
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
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_kurs({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_tanggal'+{{$model->id}}).bootstrapdatepicker({
        dropdownParent:$('#modal_edit'+{{$model->id}}),
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose:true
    });

    $('#edit_currency'+{{$model->id}}).on('keyup', function(){
        let rupiah = formatRupiah($(this).val(), "Rp ")
        $(this).val(rupiah)
    });
</script>
