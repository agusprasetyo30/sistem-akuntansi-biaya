
<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_cost_center({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Cost Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Kode Plant </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Kode Plant" value="{{$model->plant_code}}" name="detail_kode_plant" id="detail_kode_plant" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kode Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Kode Cost Center" value="{{$model->cost_center}}" name="detail_cost_center" id="detail_cost_center" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Deskrisi Cost Center" value="{{$model->cost_center_desc}}" name="detail_cost_center_desc"  id="detail_cost_center_desc" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Asumsi Umum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->id_plant}}" selected>{{$model->plant_code}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Kode Cost Center" value="{{$model->cost_center}}" name="edit_code_cost_center" id="edit_code_cost_center{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Cost Center </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Cost Center Deskripsi" value="{{$model->cost_center_desc}}" name="edit_cost_center_desc"  id="edit_cost_center_desc{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->id}}" class="form-control custom-select select2">
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
                <button type="button" id="submit_edit" onclick="update_cost_center({{$model->id}})" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_data_main_plant'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Status',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('plant_select') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    })


    $('#edit_is_active'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Status',
        width: '100%'
    })
</script>
