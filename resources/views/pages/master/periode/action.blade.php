<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_periode({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Nama </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Region" value="{{$model->periode_name}}"
                                    name="detail_periode_name" id="detail_periode_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Awal Periode</label>
                                <input type="text" class="form-control form-control-sm" disabled
                                    value="{{helpDate($model->awal_periode,'mi')}}">
                            </div>
                            <div class="form-group">
                                <label>Akhir Periode</label>
                                <input type="text" class="form-control form-control-sm" disabled
                                    value="{{helpDate($model->akhir_periode,'mi')}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" id="detail_is_active"
                                    class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>
                                        {{ $value}}</option>
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Region</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Region"
                                    value="{{$model->periode_name}}" name="edit_periode_name"
                                    id="edit_periode_name{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Awal Periode </label>
                                <input type="text" class="form-control form-control-sm fc-datepicker"
                                    placeholder="Ex: Asana" name="edit_awal_periode"
                                    id="edit_awal_periode{{$model->id}}" autocomplete="off"
                                    value="{{date('d-m-Y', strtotime($model->awal_periode))}}">
                            </div>
                            <div class="form-group">
                                <label>Akhir Periode </label>
                                <input type="text" class="form-control form-control-sm fc-datepicker"
                                    placeholder="Ex: Asana" name="edit_akhir_periode"
                                    id="edit_akhir_periode{{$model->id}}" autocomplete="off"
                                    value="{{date('d-m-Y', strtotime($model->akhir_periode))}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->id}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>
                                        {{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_periode({{$model->id}})"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_is_active' + {{$model->id}}).select2({
            dropdownParent: $('#modal_edit' + {{$model->id}}),
            placeholder: 'Pilih Status',
            width: '100%'
        })

        $('.fc-datepicker').datepicker({
            todayHighlight: true,
            orientation: 'bottom left',
            dateFormat: 'dd-mm-yy',
            autoclose: true,
        })
    })

</script>
