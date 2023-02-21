<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_tarif('{{$model->id}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Tarif</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Produk </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="product" value="{{$model->product_code}} - {{$model->material_name}}" name="detail_product" id="detail_product" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Plant </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Plant" value="{{$model->plant_code}} - {{$model->plant_desc}}" name="detail_plant_name"
                                    id="detail_plant_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Group Account FC</label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Group Account" value="{{$model->group_account_fc}} {{$model->group_account_fc_desc}}"
                                    name="detail_group_account_fc" id="detail_group_account_fc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->tarif_value}}" name="detail_tarif_value"
                                    id="detail_tarif_value" autocomplete="off">
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
                <h5 class="modal-title" id="largemodal1">Edit Tarif</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Produk <span class="text-red">*</span></label>
                                <select name="edit_data_main_produk{{$model->id}}" id="edit_data_main_produk{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->product_code}}" selected>{{$model->product_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_code}}" selected>{{$model->plant_code}} - {{$model->plant_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Group Account FC</label>
                                <select name="edit_group_account_fc" id="edit_group_account_fc{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->group_account_fc}}" selected>{{$model->group_account_fc}} {{$model->group_account_fc_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->tarif_value}}" name="edit_qty_renprod_value"
                                    id="edit_tarif_value{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_tarif('{{$model->id}}')"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_data_main_produk'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Produk',
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

        $('#edit_group_account_fc'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Group Account',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('group_account_fc_select') }}",
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
