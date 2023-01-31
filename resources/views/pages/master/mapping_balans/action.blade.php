<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_kategori_balans({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Kategori Balans</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="tanggal_awal">Kategori Produk <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->material_code}} - {{$model->material_name}}" id="detail_kategori_produk{{$model->id}}" placeholder="Kategori Produk" autocomplete="off" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori Balans <span class="text-red">*</span></label>
                                <input disabled class="form-control" type="text" name="detail_kategori_balans{{$model->id}}" id="detail_kategori_balans{{$model->id}}" autocomplete="off" value="{{$model->kategori_balans}} - {{$model->kategori_balans_desc}}" placeholder="Kategori Produk">
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
                <h5 class="modal-title" id="largemodal1">Edit Kategori Balans</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label for="edit_tanggal{{$model->id}}">Kategori Balans <span class="text-red">*</span></label>
                                <input value="{{$model->kategori_balans}}" type="text" class="form-control" id="edit_kategori_balans{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi <span class="text-red">*</span></label>
                                <input class="form-control" type="text" placeholder="0" required name="edit_deskripsi{{$model->id}}" id="edit_deskripsi{{$model->id}}" value="{{$model->kategori_balans_desc}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_kategori_balans({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

