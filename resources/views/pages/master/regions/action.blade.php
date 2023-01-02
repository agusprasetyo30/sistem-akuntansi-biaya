<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_region({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Region</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Nama Region </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Nama Region" value="{{$model->region_name}}" name="detail_nama_region" id="detail_nama_region" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Region Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Deskripsi Region" value="{{$model->region_desc}}" name="detail_deskripsi_region" id="detail_deskripsi_region" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Latitude </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Latitude" value="{{$model->latitude}}" name="detail_latitude"  id="detail_latitude" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Longtitude </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Longtitude" value="{{$model->longtitude}}" name="detail_longtitude" id="detail_longtitude" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" id="detail_is_active" class="form-control custom-select select2">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Region</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
{{--                            <input type="text" class="form-control form-control-sm d-none" placeholder="Nama Region" value="{{$model->id}}" name="edit_id" id="edit_id" autocomplete="off">--}}
                            <div class="form-group">
                                <label>Nama Region </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Region" value="{{$model->region_name}}" name="edit_nama_region" id="edit_nama_region{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Region Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Region" value="{{$model->region_desc}}" name="edit_deskripsi_region" id="edit_deskripsi_region{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Latitude </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Latitude" value="{{$model->latitude}}" name="edit_latitude"  id="edit_latitude{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Longtitude </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Longtitude" value="{{$model->longtitude}}" name="edit_longtitude" id="edit_longtitude{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->id}}">
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
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_region({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_is_active'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Status',
        width: '100%'
    })
</script>
