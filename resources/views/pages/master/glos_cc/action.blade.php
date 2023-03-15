@if (mapping_akses('glos_cc','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('glos_cc','update'))
    <a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('glos_cc','delete'))
    <a class="btn bg-danger-transparent" onclick="delete_glos_cc('{{$model->id}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Glos CC</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Plant </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Plant" value="{{$model->plant_code}} - {{$model->plant_desc}}" name="detail_plant_name"
                                    id="detail_plant_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->cost_center}} - {{$model->cost_center_desc}}" name="detail_cost_center"
                                    id="detail_cost_center" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->material_code}} - {{$model->material_name}}" name="detail_material_code"
                                    id="detail_material_code" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Glos CC</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_code}}" selected>{{$model->plant_code}} - {{$model->plant_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center</label>
                                <select name="main_cost_center" id="edit_data_main_cost_center{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->cost_center}}" selected>{{$model->cost_center}} - {{$model->cost_center_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_glos_cc('{{$model->id}}')"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_data_main_plant'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Plant',
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

        $('#edit_data_main_cost_center'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Cost Center',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('cost_center_select') }}",
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
        
        $('#edit_data_main_material'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Material',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('material_select') }}",
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
    })
</script>
