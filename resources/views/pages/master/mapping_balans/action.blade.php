<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_mapping_balans({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


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
                                <label for="versi">Versi <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->version}}" id="detail_versi{{$model->id}}" placeholder="Kategori Versi" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_awal">Kategori Produk <span class="text-red">*</span></label>
                                <input disabled type="text" class="form-control" value="{{$model->material_code}} - {{$model->material_name}}" id="detail_kategori_produk{{$model->id}}" placeholder="Kategori Produk" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Balans <span class="text-red">*</span></label>
                                <input disabled class="form-control" type="text" name="detail_kategori_balans{{$model->id}}" id="detail_kategori_balans{{$model->id}}" autocomplete="off" value="{{$model->kategori_balans}} - {{$model->kategori_balans_desc}}" placeholder="Kategori Produk">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Plant <span class="text-red">*</span></label>
                                <select disabled multiple="multiple" name="data_main_plant{{$model->id}}" id="data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    @php
                                        $data_plant = explode(';', $model->plant_code)
                                    @endphp
                                    @foreach($data_plant as $items)
                                        <option value="{{$items}}">{{$items}}</option>
                                    @endforeach
                                </select>
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
                <h5 class="modal-title" id="largemodal1">Edit Mapping Balans</h5>
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
                                <label class="form-label" for="edit_tanggal{{$model->id}}">Material <span class="text-red">*</span></label>
                                <select name="edit_material{{$model->id}}" id="edit_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori Balans <span class="text-red">*</span></label>
                                <select name="edit_kategori_balans{{$model->id}}" id="edit_kategori_balans{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->kategori_balans_id}}" selected>{{$model->kategori_balans}} - {{$model->kategori_balans_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Plant <span class="text-red">*</span></label>
                                <select multiple="multiple" name="edit_data_main_plant{{$model->id}}" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    @php
                                        $data_plant = explode(';', $model->plant_code)
                                    @endphp
                                    @foreach($data_plant as $items)
                                        <option selected value="{{$items}}">{{$items}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_map_balans({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>

    var get_data_object = {!! json_encode($data_plant) !!};
    var data_plant = get_data_object[0].split(';');

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
    })

    $('#edit_material'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Material',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('material_balans_select') }}",
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

    $('#edit_kategori_balans'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Kategori Balans',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('kategori_balans_select') }}",
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
        $('#edit_data_main_plant'+{{$model->id}}).val([]).change().select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Plant / Cost Center',
            width: '100%',
            multiple: true,
            allowClear: false,
            ajax: {
                url: "{{ route('plant_balans_select') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        kategori: $('#edit_kategori_balans'+{{$model->id}}).val(),
                        material: $('#edit_material'+{{$model->id}}).val(),
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

    $('#edit_data_main_plant'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Plant / Cost Center',
        width: '100%',
        multiple: true,
        allowClear: false,
        ajax: {
            url: "{{ route('plant_balans_select') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    kategori: $('#edit_kategori_balans'+{{$model->id}}).val(),
                    material: $('#edit_material'+{{$model->id}}).val(),
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

