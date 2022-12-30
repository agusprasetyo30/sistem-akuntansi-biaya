<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_price_rendaan({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
     aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Price Rencana Pengadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Version </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                       placeholder="Nama version" value="{{$model->version}} - {{format_month($model->month_year, 'bi')}}" name="detail_version"
                                       id="detail_version" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                       placeholder="Nama Kategori" value="{{$model->material_code}} - {{$model->material_name}}" name="detail_material_name"
                                       id="detail_material_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Region </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                       placeholder="Total Stock" value="{{$model->region_name}}" name="detail_region_name"
                                       id="detail_region_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                       placeholder="Nilai Satuan" value="{{$model->price_rendaan_value}}" name="detail_price_rendaan_value"
                                       id="detail_price_rendaan_value" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail"
     aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Price Rencana Pengadaan</h5>
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
                                    <option value="{{$model->asumsi_umum_id}}" selected>{{format_month($model->month_year, 'se')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="edit_main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Region</label>
                                <select name="edit_data_main_region{{$model->id}}" id="edit_data_main_region{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->region_id}}" selected>{{$model->region_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Value </label>
                                <input class="form-control" type="number" value="{{$model->price_rendaan_value}}" placeholder="0" required name="edit_price_rendaan_value{{$model->id}}" id="edit_price_rendaan_value{{$model->id}}" min="0" step="0.01" title="consrate" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_price_rendaan({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
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
                    version:$('#edit_data_main_version'+{{$model->id}}).val()

                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    });

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

    $('#edit_data_main_region'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih region',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('region_select') }}",
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
</script>
