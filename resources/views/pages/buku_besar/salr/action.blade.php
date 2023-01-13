<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_price_rendaan({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modal_detail"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Price Rencana Pengadaan</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Group Account FC <span class="text-red">*</span></label>
                                <input value="{{$model->group_account_fc}} - {{$model->group_account_fc_desc}}" disabled type="text" class="form-control" id="detail_data_main_ga_account">
                            </div>
                            <div class="form-group">
                                <label class="form-label">General Ledger Account FC <span class="text-red">*</span></label>
                                <input disabled value="{{$model->gl_account_fc}} - {{$model->gl_account_fc_desc}}" type="text" class="form-control" id="detail_data_main_gl_account">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center <span class="text-red">*</span></label>
                                <input disabled value="{{$model->cost_center}} - {{$model->cost_center_desc}}" type="text" class="form-control" id="detail_data_main_cost_center">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_awal">Bulan <span class="text-red">*</span></label>
                                <input disabled value="{{$model->periode}}" type="text" class="form-control" id="detail_tanggal" placeholder="Bulan-Tahun" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Value <span class="text-red">*</span></label>
                                <input disabled value="{{$model->value}}" class="form-control" type="text" placeholder="0" required name="value" id="detail_value" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama </label>
                                <input disabled value="{{$model->name}}" class="form-control" type="text" placeholder="Masukkan Nama" name="nama" id="detail_nama" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Partner Cost Center </label>
                                <input disabled value="{{$model->partner_cost_center}}" type="text" class="form-control" id="detail_data_main_partner_cost_center">
                            </div>
                            <div class="form-group">
                                <label class="form-label">UserName </label>
                                <input disabled value="{{$model->username}}" class="form-control" type="text" placeholder="Masukkan Username" name="username" id="detail_username" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <input disabled value="{{$model->material_code}}" type="text" class="form-control" id="detail_data_main_material">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number </label>
                                <input disabled value="{{$model->document_number}}" class="form-control" type="text" placeholder="Masukkan Dokumen Number" name="document_num" id="detail_document_num" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number Deskripsi </label>
                                <input disabled value="{{$model->document_number_text}}" class="form-control" type="text" placeholder="Masukkan Dokumen Number Deskripsi" name="document_num_desc" id="detail_document_num_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Purchase Order </label>
                                <input disabled value="{{$model->purchase_order}}" class="form-control" type="text" placeholder="Masukkan Purchase Order" name="purchase_order" id="detail_purchase_order" autocomplete="off">
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
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Salr</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label class="form-label">Group Account FC <span class="text-red">*</span></label>
                                <select name="edit_data_main_ga_account{{$model->id}}" id="edit_data_main_ga_account{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->group_account_fc}}" selected>{{$model->group_account_fc}} - {{$model->group_account_fc_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">General Ledger Account FC <span class="text-red">*</span></label>
                                <select name="main_gl_account" id="edit_data_main_gl_account{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->gl_account_fc}}" selected>{{$model->gl_account_fc}} - {{$model->gl_account_fc_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cost Center <span class="text-red">*</span></label>
                                <select name="main_cost_center" id="edit_data_main_cost_center{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->cost_center}}" selected>{{$model->cost_center}} - {{$model->cost_center_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_awal">Bulan <span class="text-red">*</span></label>
                                <input value="{{format_month($model->periode, 'se')}}" type="text" class="form-control" id="edit_tanggal{{$model->id}}" placeholder="Bulan-Tahun" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Value <span class="text-red">*</span></label>
                                <input value="{{$model->value}}" class="form-control" type="text" placeholder="0" required name="value" id="edit_value{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama </label>
                                <input value="{{$model->name}}" class="form-control" type="text" placeholder="Masukkan Nama" name="nama" id="edit_nama{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Partner Cost Center </label>
                                <select name="main_partner_cost_center" id="edit_data_main_partner_cost_center{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->partner_cost_center}}" disabled selected>Pilih Partner Cost Center</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">UserName </label>
                                <input value="{{$model->username}}" class="form-control" type="text" placeholder="Masukkan Username" name="username" id="edit_username{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Material</label>
                                <select name="main_material" id="edit_data_main_material{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->material_code}}"  selected>Pilih Material</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number </label>
                                <input value="{{$model->document_number}}" class="form-control" type="text" placeholder="Masukkan Dokumen Number" name="document_num" id="edit_document_num{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dokumen Number Deskripsi </label>
                                <input value="{{$model->document_number_text}}" class="form-control" type="text" placeholder="Masukkan Dokumen Number Deskripsi" name="document_num_desc" id="edit_document_num_desc{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Purchase Order </label>
                                <input value="{{$model->purchase_order}}" class="form-control" type="text" placeholder="Masukkan Purchase Order" name="purchase_order" id="edit_purchase_order{{$model->id}}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-list btn-animation">
                    <button type="button" id="submit_edit{{$model->id}}" onclick="update_salr({{$model->id}})" class="btn btn-primary">Simpan</button>
                    <button type="button" id="back_edit{{$model->id}}" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_tanggal'+{{$model->id}}).bootstrapdatepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose:true
    });

    $('#edit_value'+{{$model->id}}).on('keyup', function(){
        let rupiah = formatRupiah($(this).val(), "Rp ")
        $(this).val(rupiah)
    });

    $('#edit_data_main_ga_account'+{{$model->id}}).select2({
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
    }).on('change', function () {
        var group_account = $('#edit_data_main_ga_account'+{{$model->id}}).val();
        $('#edit_data_main_gl_account'+{{$model->id}}).append('<option selected disabled value="">Pilih General Ledger Account</option>').select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih General Ledger Account',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('general_ledger_fc_select') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        group_account:group_account

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

    $('#edit_data_main_gl_account'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih General Ledger Account',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('general_ledger_fc_detail_select') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    group_account: $('#edit_data_main_ga_account'+{{$model->id}}).val()

                };
            },
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    });


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

    $('#edit_data_main_partner_cost_center'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        tags : true,
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
</script>
