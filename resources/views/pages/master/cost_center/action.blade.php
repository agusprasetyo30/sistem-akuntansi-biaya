
<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->cost_center)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->cost_center)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_cost_center('{{$model->cost_center}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->cost_center)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Cost Center</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Kode Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Kode Cost Center" value="{{$model->cost_center}}" name="detail_cost_center" id="detail_cost_center" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Deskrisi Cost Center" value="{{$model->cost_center_desc}}" name="detail_cost_center_desc"  id="detail_cost_center_desc" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
{{--                <button type="button" id="submit" class="btn btn-primary">Simpan</button>--}}
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Edit-->
<div class="modal fade" id="{{__('modal_edit'.$model->cost_center)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Cost Center</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Kode Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Kode Cost Center" value="{{$model->cost_center}}" name="edit_code_cost_center" id="edit_code_cost_center{{$model->cost_center}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Cost Center Deskripsi" value="{{$model->cost_center_desc}}" name="edit_cost_center_desc"  id="edit_cost_center_desc{{$model->cost_center}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->cost_center}}" onclick="update_cost_center('{{$model->cost_center}}')" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->cost_center}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>

            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_code_cost_center'+'{{$model->cost_center}}').keyup(function(){
            this.value = this.value.toUpperCase();
        });
    })
</script>