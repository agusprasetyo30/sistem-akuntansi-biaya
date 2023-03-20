@if (mapping_akses('qty_renprod','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('qty_renprod','update'))
    <a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('qty_renprod','delete'))
    <a class="btn bg-danger-transparent" onclick="delete_qty_renprod({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

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
                                <label>Versi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Versi" value="{{$model->version}}" name="detail_version"
                                    id="detail_version" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Periode </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="bulan" value="{{format_month($model->month_year,'bi')}}" name="detail_bulan" id="detail_bulan" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Cost Center </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->cost_center}} {{$model->cost_center_desc}}" name="detail_cost_center"
                                    id="detail_cost_center" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nilai Satuan" value="{{$model->qty_renprod_value}}" name="detail_qty_renprod_value"
                                    id="detail_qty_renprod_value" autocomplete="off">
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
                <h5 class="modal-title" id="largemodal1">Edit Kuantiti Rencana Produksi</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Versi <span class="text-red">*</span></label>
                                <select name="edit_data_main_version{{$model->id}}" id="edit_data_main_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->version_id}}" selected>{{$model->version}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bulan <span class="text-red">*</span></label>
                                <select name="edit_data_detail_version{{$model->id}}" id="edit_data_detail_version{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->asumsi_umum_id}}" selected>{{format_month($model->month_year, 'se')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center</label>
                                <select name="main_cost_center" id="edit_data_main_cost_center{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->cost_center}}" selected>{{$model->cost_center}} {{$model->cost_center_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Value </label>
                                <input type="number" class="form-control form-control-sm" placeholder="Value"
                                    value="{{$model->qty_renprod_value}}" name="edit_qty_renprod_value"
                                    id="edit_qty_renprod_value{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_qty_renprod({{$model->id}})"
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
        $('#edit_data_detail_version'+{{$model->id}}).append('<option selected disabled value="">Pilih Bulan</option>').select2({
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

    $('#edit_data_detail_version'+{{$model->id}}).select2({
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

</script>
