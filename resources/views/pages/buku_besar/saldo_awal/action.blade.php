<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_saldo_awal({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Versi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Versi" value="{{$model->version}}" name="detail_version
                                    id="detail_version" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>G/L Account </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="GL Account " value="{{$model->gl_account}}" name="detail_gl_account"
                                    id="detail_gl_account" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Valuation Class" value="{{$model->valuation_class}}" name="detail_valuation_class"
                                    id="detail_valuation_class" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Material" value="{{$model->price_control}}" name="detail_price_control"
                                    id="detail_price_control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->material_code}} {{$model->material_name}}" name="detail_material_code"
                                    id="detail_material_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kode Plant </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Plant" value="{{$model->plant_code}}" name="detail_plant_name"
                                    id="detail_plant_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Total Value" value="{{helpRupiah($model->total_value)}}" name="detail_total_value"
                                    id="detail_total_value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Total Stock" value="{{$model->total_stock}}" name="detail_total_stock"
                                    id="detail_total_stock" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{helpRupiah($model->nilai_satuan)}}" name="detail_nilai_satuan"
                                    id="detail_nilai_satuan" autocomplete="off">
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
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Saldo Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Versi</label>
                                <select name="main_version" id="edit_data_main_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->version_id}}" selected>{{$model->version}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>GL Account </label>
                                <input type="text" class="form-control form-control-sm" placeholder="GL Account"
                                    value="{{$model->gl_account}}" name="edit_gl_account"
                                    id="edit_gl_account{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Valuation Class </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Valuation Class"
                                    value="{{$model->valuation_class}}" name="edit_valuation_class"
                                    id="edit_valuation_class{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Price Control </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Price Control"
                                    value="{{$model->price_control}}" name="edit_price_control"
                                    id="edit_price_control{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_code}}" selected>{{$model->plant_code}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Value </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total value"
                                    value="{{helpRupiah($model->total_value)}}" name="edit_total_value"
                                    id="edit_total_value{{$model->id}}" autocomplete="off" onkeyup="edit_nilai_satuan()">
                            </div>
                            <div class="form-group">
                                <label>Total Stock </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Total Stock"
                                    value="{{$model->total_stock}}" name="edit_total_stock"
                                    id="edit_total_stock{{$model->id}}" autocomplete="off" onkeyup="edit_nilai_satuan()">
                            </div>
                            <div class="form-group">
                                <label>Nilai Satuan </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nilai Satuan"
                                    value="" name="edit_nilai_satuan"
                                    id="edit_nilai_satuan{{$model->id}}" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_saldo_awal({{$model->id}})"
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

        $('#edit_total_value'+{{$model->id}}).on('keyup', function(){
                let rupiah = formatRupiah($(this).val(), "Rp ")
                $(this).val(rupiah)
            });
        
        edit_nilai_satuan()
    })

    function edit_nilai_satuan(){
            let total = $('#edit_total_value'+{{$model->id}}).val()
            let stok = $('#edit_total_stock'+{{$model->id}}).val()
            let res = total.substring(3)
            let result = res.replaceAll(".", "")
            let nilsa = parseInt(result) / parseInt(stok) || 0
            $("#edit_nilai_satuan"+{{$model->id}}).val(nilsa)
        }

</script>
