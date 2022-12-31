<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->plant_code)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->plant_code)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_plant('{{$model->plant_code}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->plant_code)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail plant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Plant Code </label>

                                <input disabled type="text" class="form-control form-control-sm" placeholder="Code Plant" value="{{$model->plant_code}}" name="detail_code_plant" id="detail_code_plant" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Plant Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Deskripsi Plant" value="{{$model->plant_desc}}" name="detail_deskripsi_plant" id="detail_deskripsi_plant" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" id="detail_is_active" class="form-control form-control-sm custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>{{ $value}}</option>
                                    @endforeach
                                </select>
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
<div class="modal fade" id="{{__('modal_edit'.$model->plant_code)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Plant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Plant Code </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Code Plant" value="{{$model->plant_code}}" name="edit_code_plant" id="edit_code_plant{{$model->plant_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Plant Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Plant" value="{{$model->plant_desc}}" name="edit_deskripsi_plant" id="edit_deskripsi_plant{{$model->plant_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->plant_code}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_plant('{{$model->plant_code}}')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_is_active'+'{{$model->plant_code}}').select2({
        dropdownParent: $('#modal_edit'+'{{$model->plant_code}}'),
        placeholder: 'Pilih Status',
        width: '100%'
    })
</script>
