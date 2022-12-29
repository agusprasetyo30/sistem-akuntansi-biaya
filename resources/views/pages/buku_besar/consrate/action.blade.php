
<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_consrate({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Consumption Ratio</h5>
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
                                <label>Versi Asumsi </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="version" value="{{$model->version}}" name="detail_version" id="detail_version" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Periode </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="bulan" value="{{format_month($model->month_year,'bi')}}" name="detail_bulan" id="detail_bulan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Produk </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="product" value="{{$model->product_code}} - {{$model->product_name}}" name="detail_product" id="detail_product" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="material" value="{{$model->material_code}} - {{$model->material_name}}" name="detail_material" id="detail_material" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Uom </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="oum" value="{{$model->material_uom}}" name="detail_uom" id="detail_uom" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Consumption Ratio (%) </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="consrate" value="{{$model->cons_rate}}" name="detail_consrate" id="detail_consrate" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Consumption Ratio</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Versi Asumsi <span class="text-red">*</span></label>
                                <select name="edit_data_main_version{{$model->id}}" id="edit_data_main_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->version_id}}" selected>{{$model->version}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bulan <span class="text-red">*</span></label>
                                <select name="edit_data_detal_version{{$model->id}}" id="edit_data_detal_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->month_year}}" selected>{{format_month($model->month_year, 'se')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Plant <span class="text-red">*</span></label>
                                <select name="edit_data_main_plant{{$model->id}}" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_code}}" selected>{{$model->plant_code}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Produk <span class="text-red">*</span></label>
                                <select name="edit_data_main_produk{{$model->id}}" id="edit_data_main_produk{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->product_code}}" selected>{{$model->product_code}} - {{$model->product_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material <span class="text-red">*</span></label>
                                <select name="edit_data_main_material{{$model->id}}" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Consumption Ratio (%) <span class="text-red">*</span></label>
                                <input class="form-control" type="number" placeholder="0" required name="edit_consrate{{$model->id}}" id="edit_consrate{{$model->id}}" value="{{$model->cons_rate}}" min="0" step="0.01" title="consrate" pattern="^\d+(?:\.\d{1,2})?$">
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
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit" onclick="update_consrate({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
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

    $('#edit_data_main_version'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Versi',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('version_select') }}",
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
    }).on('change', function () {
        var data_version = $('#edit_data_main_version'+{{$model->id}}).val();
        $('#edit_data_detal_version'+{{$model->id}}).append('<option selected disabled value="">Pilih Bulan</option>').select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Bulan',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('version_detail_select') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        version:data_version

                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            }
        });
    })

    $('#edit_data_detal_version'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Bulan',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('version_detail_select') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    version: $('#edit_data_main_version'+{{$model->id}}).val()

                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    });

    $('#edit_data_main_produk'+{{$model->id}}).select2({
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
    }).on('change', function () {
        var data_produk = $('#edit_data_main_produk'+{{$model->id}}).val();
        $('#edit_data_main_material'+{{$model->id}}).append('<option selected disabled value="">Pilih Material</option>').select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Material',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('material_keyword_select') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        produk:data_produk

                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            }
        });
    })

    var produk = $('#edit_data_main_produk'+{{$model->id}}).val();
    $('#edit_data_main_material'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Material',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('material_keyword_select') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    produk:produk

                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    });

    $('#edit_is_active'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Status',
        width: '100%'
    })
</script>
