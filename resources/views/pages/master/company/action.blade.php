@if (mapping_akses('company','read'))
    <button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->company_code)}}"><i class="fe fe-info"></i></button>
@endif

@if (mapping_akses('company','update'))
    <a  class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->company_code)}}"><i class="fe fe-edit"></i></a>
@endif

@if (mapping_akses('company','delete'))
    <a  class="btn bg-danger-transparent" onclick="delete_company('{{$model->company_code}}')" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>
@endif

<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->company_code)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Company</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                            <div class="form-group">
                                <label>Company Code</label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Company Code" value="{{$model->company_code}}" name="company_code" id="detail_company_code" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Company Name </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Company Name" value="{{$model->company_name}}" name="company_name" id="detail_company_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Link SSO </label>
                                <input disabled type="text" class="form-control form-control-sm" placeholder="Link SSO" value="{{$model->link_sso}}" name="link_sso" id="detail_link_sso" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select disabled name="detail_is_active" company_code="detail_is_active" class="form-control form-control-sm custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>{{ $value}}</option>
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
<div class="modal fade" id="{{__('modal_edit'.$model->company_code)}}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal_detail" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Master Company</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12" style="text-align: start;">
                        <div class="form-group">
                                <label>Company Code</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Company Code" value="{{$model->company_code}}" name="edit_company_code" id="edit_company_code{{$model->company_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Company Name </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Company Name" value="{{$model->company_name}}" name="edit_company_name" id="edit_company_name{{$model->company_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Link SSO </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Link SSO" value="{{$model->link_sso}}" name="edit_link_sso" id="edit_link_sso{{$model->company_code}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="edit_is_active" id="edit_is_active{{$model->company_code}}">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $model->is_active ? "selected" : "" }}>{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit_edit" onclick="update_company('{{$model->company_code}}')" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<script>
    $(document).ready(function () {
        $('#edit_company_code'+'{{$model->company_code}}').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#edit_is_active'+"{{$model->company_code}}").select2({
            dropdownParent: $('#modal_edit'+"{{$model->company_code}}"),
            placeholder: 'Pilih Status',
            width: '100%'
        })
    })
</script>
