<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->gl_account_fc)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->gl_account_fc)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_gl_account_fc('{{$model->gl_account_fc}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->gl_account_fc)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail General Ledger Account Fixed Cost</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Code </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama Kategori" value="{{$model->gl_account_fc}}" name="detail_gl_account_fc"
                                    id="detail_gl_account_fc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Deskripsi Kategori Material" value="{{$model->gl_account_fc_desc}}"
                                    name="detail_gl_account_fc_desc" id="detail_gl_account_fc_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Group Account FC</label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Group Account" value="{{$model->group_account_fc}} {{$model->group_account_fc_desc}}"
                                    name="detail_group_account_fc" id="detail_group_account_fc" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->gl_account_fc)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit General Ledger Account Fixed Cost</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Code </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Kode Account"
                                    value="{{$model->gl_account_fc}}" name="edit_gl_account_fc"
                                    id="edit_gl_account_fc{{$model->gl_account_fc}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Account"
                                    value="{{$model->gl_account_fc_desc}}" name="edit_gl_account_fc_desc"
                                    id="edit_gl_account_fc_desc{{$model->gl_account_fc}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Group Account FC</label>
                                <select name="edit_group_account_fc" id="edit_group_account_fc{{$model->gl_account_fc}}" class="form-control custom-select select2">
                                    <option value="{{$model->group_account_fc}}" selected>{{$model->group_account_fc}} {{$model->group_account_fc_desc}}</option>
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->gl_account_fc}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>
                                        {{ $value}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_gl_account_fc('{{$model->gl_account_fc}}')"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_gl_account_fc'+'{{$model->gl_account_fc}}').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#edit_group_account_fc'+'{{$model->gl_account_fc}}').select2({
            dropdownParent: $('#modal_edit'+'{{$model->gl_account_fc}}'),
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
