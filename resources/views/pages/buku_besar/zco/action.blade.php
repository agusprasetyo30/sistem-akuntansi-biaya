<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_zco({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Kuantiti Rencana Produksi</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Plant </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Versi" value="{{$model->plant_code}}" name="detail_plant_code
                                    id="detail_version" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Periode </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="bulan" value="{{$model->periode}}" name="detail_periode" id="detail_bulan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Produk </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->product_code}} {{$model->product_name}}" name="detail_product_code"
                                    id="detail_product_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Produk Qty </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="bulan" value="{{$model->product_qty}}" name="detail_product_qty" id="detail_bulan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Cost Element </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->cost_element}} {{$model->gl_account_desc}}" name="detail_cost_element"
                                    id="detail_cost_element" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Material </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->material_code}} {{$model->material_name}}" name="detail_material_code"
                                    id="detail_material_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Qty </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->total_qty}}" name="detail_total_qty"
                                    id="detail_total_qty" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Currency </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->currency}}" name="detail_currency"
                                    id="detail_currency" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Amount </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->total_amount}}" name="detail_total_amount"
                                    id="detail_total_amount" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Unit Price Produk </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->unit_price_product}}" name="detail_unit_price_product"
                                    id="detail_unit_price_product" autocomplete="off">
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
                <h5 class="modal-title" id="largemodal1">Edit ZCO</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Plant</label>
                                <select name="main_plant" id="edit_data_main_plant{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->plant_code}}" selected>{{$model->plant_code}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Periode </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->periode}}" name="edit_periode"
                                    id="edit_periode{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Produk</label>
                                <select name="main_produk" id="edit_data_main_produk{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->product_code}}" selected>{{$model->product_code}} - {{$model->product_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Produk Qty </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->product_qty}}" name="edit_product_qty"
                                    id="edit_product_qty{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Element</label>
                                <select name="main_produk" id="edit_data_main_cost_element{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->cost_element}}" selected>{{$model->cost_element}} {{$model->gl_account_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}" selected>{{$model->material_code}} - {{$model->material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Total Qty </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->total_qty}}" name="edit_total_qty"
                                    id="edit_total_qty{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Currency </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->currency}}" name="edit_currency"
                                    id="edit_currency{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Total Amount </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->total_amount}}" name="edit_total_amount"
                                    id="edit_total_amount{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Unit Price Produk </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->unit_price_product}}" name="edit_unit_price_product"
                                    id="edit_unit_price_product{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_zco({{$model->id}})"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    // $('#edit_qty_renprod_value'+{{$model->id}}).on('keyup', function(){
    //     let rupiah = formatRupiah($(this).val(), "Rp ")
    //     $(this).val(rupiah)
    // });

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

    $('#edit_data_main_cost_element'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Cost Element',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('cost_element_select') }}",
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
    
</script>