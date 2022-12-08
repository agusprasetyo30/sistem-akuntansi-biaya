
<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a  class="btn bg-danger-transparent" onclick="delete_asumsi_umum({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Asumsi Umum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Periode </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Periode" value="{{$model->periode_name}}" name="detail_periode" id="detail_periode" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kurs </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Kurs" value="{{$model->kurs}}" name="detail_kurs" id="detail_kurs" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Handling BB ( % ) </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Handling BB" value="{{$model->handling_bb}}" name="detail_handling_bb"  id="detail_handling_bb" autocomplete="off">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label>Data Saldo Awal </label>--}}
{{--                                <input disabled type="text" class="form-control form-control-sm" placeholder="Data Saldo Awal" value="{{$model->data_saldo_awal}}" name="detail_data_saldo_awal"  id="detail_data_saldo_awal" autocomplete="off">--}}
{{--                            </div>--}}
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
                                <label class="form-label">Periode</label>
                                <select name="main_periode" id="edit_data_main_periode{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->periode_id}}" selected>{{$model->periode_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kurs </label>
                                <input value="{{$model->kurs}}" type="text" class="form-control form-control-sm" placeholder="Kurs" name="kursr" id="edit_kurs{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Handling BB ( % ) </label>
                                <input value="{{$model->handling_bb}}" type="text" class="form-control form-control-sm" placeholder="Handling BB" name="handling_bb" id="edit_handling_bb{{$model->id}}" autocomplete="off">
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label>Data Saldo Awal </label>--}}
{{--                                <input value="{{$model->data_saldo_awal}}" type="text" class="form-control form-control-sm" placeholder="Data Saldo Awal" name="data_saldo_awal" id="edit_data_saldo_awal{{$model->id}}" autocomplete="off">--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_asumsi_umum({{$model->id}})" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $('#edit_data_main_periode'+{{$model->id}}).select2({
        dropdownParent: $('#modal_edit'+{{$model->id}}),
        placeholder: 'Pilih Periode',
        width: '100%',
        allowClear: false,
        ajax: {
            url: "{{ route('periode_select') }}",
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
