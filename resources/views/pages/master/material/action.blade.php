<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->material_code)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->material_code)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_material('{{$model->material_code}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->material_code)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Code </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Code" value="{{$model->material_code}}" name="detail_material_code"
                                    id="detail_material_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nama </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Nama" value="{{$model->material_name}}" name="detail_material_name"
                                    id="detail_material_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Deskripsi Material" value="{{$model->material_desc}}"
                                    name="detail_material_desc" id="detail_material_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Group Account </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Group Account" value="{{$model->group_account_code}} {{$model->group_account_desc}}"
                                    name="detail_group_account_code" id="detail_group_account_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kategori </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Kategori" value="{{$model->kategori_material_name}}"
                                    name="detail_kategori_material_id" id="detail_kategori_material_id" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>UOM </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="UOM" value="{{$model->material_uom}}"
                                    name="detail_material_uom" id="detail_material_uom" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" id="detail_is_active"
                                    class="form-control form-control-sm custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>
                                        {{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dummy</label>
                                <select disabled name="detail_is_dummy" id="detail_is_dummy"
                                    class="form-control form-control-sm custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_dummy() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_dummy ? "selected" : "" }}>
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
<div class="modal fade" id="{{__('modal_edit'.$model->material_code)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Code </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama"
                                    value="{{$model->material_code}}" name="edit_material_code"
                                    id="edit_material_code{{$model->material_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama"
                                    value="{{$model->material_name}}" name="edit_material_name"
                                    id="edit_material_name{{$model->material_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Kategori"
                                    value="{{$model->material_desc}}" name="edit_material_desc"
                                    id="edit_material_desc{{$model->material_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Group Account</label>
                                <select name="edit_group_account_code" id="edit_group_account_code{{$model->material_code}}" class="form-control custom-select select2">
                                    <option value="{{$model->group_account_code}}" selected>{{$model->group_account_code}} {{$model->group_account_desc}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="edit_kategori_material_id" id="edit_kategori_material_id{{$model->material_code}}" class="form-control custom-select select2">
                                    <option value="{{$model->kategori_material_id}}" selected>{{$model->kategori_material_name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>UOM </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Kategori"
                                    value="{{$model->material_uom}}" name="edit_material_uom"
                                    id="edit_material_uom{{$model->material_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->material_code}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>
                                        {{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dummy</label>
                                <select name="edit_is_dummy" id="edit_is_dummy{{$model->material_code}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_dummy() as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $model->is_dummy ? "selected" : "" }}>
                                        {{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_material('{{$model->material_code}}')"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_material_code'+'{{$model->material_code}}').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#edit_is_active'+'{{$model->material_code}}').select2({
            dropdownParent: $('#modal_edit'+'{{$model->material_code}}'),
            placeholder: 'Pilih Kategori',
            width: '100%'
        })

        $('#edit_is_dummy'+'{{$model->material_code}}').select2({
            dropdownParent: $('#modal_edit'+'{{$model->material_code}}'),
            placeholder: 'Pilih Dummy',
            width: '100%'
        })

        $('#edit_kategori_material_id'+'{{$model->material_code}}').select2({
            dropdownParent: $('#modal_edit'+'{{$model->material_code}}'),
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('kategori_material_select') }}",
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

        $('#edit_group_account_code'+'{{$model->material_code}}').select2({
            dropdownParent: $('#modal_edit'+'{{$model->material_code}}'),
            placeholder: 'Pilih Group Account',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('group_account_select') }}",
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
