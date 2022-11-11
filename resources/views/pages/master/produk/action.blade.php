<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_produk({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Produk</h5>
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
                                    placeholder="Nama" value="{{$model->produk_name}}" name="detail_produk_name"
                                    id="detail_produk_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Deskripsi produk" value="{{$model->produk_desc}}"
                                    name="detail_produk_desc" id="detail_produk_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Kategori </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Kategori" value="{{$model->kategori_produk_name}}"
                                    name="detail_kategori_produk_id" id="detail_kategori_produk_id" autocomplete="off">
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
<div class="modal fade" id="{{__('modal_edit'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Edit Produk</h5>
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
                                <input type="text" class="form-control form-control-sm" placeholder="Nama"
                                    value="{{$model->produk_name}}" name="edit_produk_name"
                                    id="edit_produk_name{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Kategori"
                                    value="{{$model->produk_desc}}" name="edit_produk_desc"
                                    id="edit_produk_desc{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="edit_kategori_produk_id" id="edit_kategori_produk_id{{$model->id}}" class="form-control custom-select select2">
                                    <option value="{{$model->kategori_produk_id}}" selected>{{$model->kategori_produk_name}}</option>
                                </select>
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
                            <div class="form-group">
                                <label class="form-label">Dummy</label>
                                <select name="edit_is_dummy" id="edit_is_dummy{{$model->id}}">
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
                <button type="button" id="submit_edit" onclick="update_produk({{$model->id}})"
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

        $('#edit_is_dummy' + {{$model->id}}).select2({
            dropdownParent: $('#modal_edit' + {{$model->id}}),
            placeholder: 'Pilih Status',
            width: '100%'
        })

        $('#edit_kategori_produk_id'+{{$model->id}}).select2({
            dropdownParent: $('#modal_edit'+{{$model->id}}),
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: false,
            ajax: {
                url: "{{ route('kategori_produk_select') }}",
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
