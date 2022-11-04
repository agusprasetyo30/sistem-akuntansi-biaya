<button type="button" class="btn bg-info-transparent" title="detail" data-bs-toggle="modal" data-bs-target="{{__('#modal_detail'.$model->id)}}"><i class="fe fe-info"></i></button>
<a class="btn bg-warning-transparent" title="edit" data-bs-toggle="modal" data-bs-target="{{__('#modal_edit'.$model->id)}}"><i class="fe fe-edit"></i></a>
<a class="btn bg-danger-transparent" onclick="delete_kategori_produk({{$model->id}})" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>


<!-- Modal Detail-->
<div class="modal fade" id="{{__('modal_detail'.$model->id)}}" role="dialog" aria-labelledby="modal_detail"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Detail Kategori Produk</h5>
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
                                    placeholder="Nama Kategori" value="{{$model->kategori_produk_name}}" name="detail_kategori_produk_name"
                                    id="detail_kategori_produk_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input disabled type="text" class="form-control form-control-sm"
                                    placeholder="Deskripsi Kategori" value="{{$model->kategori_produk_desc}}"
                                    name="detail_kategori_produk_desc" id="detail_kategori_produk_desc" autocomplete="off">
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
                <h5 class="modal-title" id="largemodal1">Edit Kategori Produk</h5>
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
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Kategori"
                                    value="{{$model->kategori_produk_name}}" name="edit_kategori_produk_name"
                                    id="edit_kategori_produk_name{{$model->id}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Kategori"
                                    value="{{$model->kategori_produk_desc}}" name="edit_kategori_produk_desc"
                                    id="edit_kategori_produk_desc{{$model->id}}" autocomplete="off">
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
                <button type="button" id="submit_edit" onclick="update_kategori_produk({{$model->id}})"
                    class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<!--/div-->

<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="largemodal" aria-hidden="true" style="text-align: start;">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largemodal1">Tambah Kategori Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mt1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama Kategori" name="kategori_produk_name" id="kategori_produk_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control form-control-sm" placeholder="Deskripsi Kategori" name="kategori_produk_desc" id="kategori_produk_desc" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-control custom-select select2">
                                    <option value="" disabled selected>Pilih Status</option>
                                    @foreach (status_is_active() as $key => $value)
                                        <option value="{{ $key }}">{{ $value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit" class="btn btn-primary">Simpan</button>
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

        $('#is_active').select2({
            dropdownParent: $('#modal_add'),
            placeholder: 'Pilih Status',
            width: '100%'
        })
    })

    $('#submit').on('click', function () {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan segera dikirim",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#019267',
            cancelButtonColor: '#EF4B4B',
            confirmButtonText: 'Konfirmasi',
            cancelButtonText: 'Kembali'
        }).then((result) =>{
            if (result.value){
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('insert_kategori_produk')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama: $('#kategori_produk_name').val(),
                        deskripsi: $('#kategori_produk_desc').val(),
                        is_active: $('#is_active').val(),
                    },
                    success:function (response) {
                        if (response.Code === 200){
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            $('#is_active').val('').trigger("change");
                            toastr.success('Data Berhasil Disimpan', 'Success')
                            get_data()
                        }else if (response.Code === 0){
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                        }else {
                            $('#modal_add').modal('hide');
                            $("#modal_add input").val("")
                            toastr.error('Terdapat Kesalahan System', 'System Error')
                        }


                    }
                })

            }

        })
    })

    function update_kategori_produk(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan segera disimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#019267',
            cancelButtonColor: '#EF4B4B',
            confirmButtonText: 'Konfirmasi',
            cancelButtonText: 'Kembali'
        }).then((result) =>{
            if (result.value){

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('update_kategori_produk')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        nama: $('#edit_kategori_produk_name'+id).val(),
                        deskripsi: $('#edit_kategori_produk_desc'+id).val(),
                        is_active: $('#edit_is_active'+id).val(),
                    },
                    success:function (response) {
                        if (response.Code === 200){
                            $('#modal_edit'+id).modal('hide');
                            toastr.success('Data Berhasil Disimpan', 'Success')
                            get_data()
                        }else if (response.Code === 0){
                            $('#modal_edit'+id).modal('hide');
                            toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                        }else {
                            $('#modal_edit'+id).modal('hide');
                            toastr.error('Terdapat Kesalahan System', 'System Error')
                        }
                    }
                })

            }

        })
    }

    function delete_kategori_produk(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan segera dihapus",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#019267',
            cancelButtonColor: '#EF4B4B',
            confirmButtonText: 'Konfirmasi',
            cancelButtonText: 'Kembali'
        }).then((result) =>{
            if (result.value){

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('delete_kategori_produk')}}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                    },
                    success:function (response) {
                        if (response.Code === 200){
                            toastr.success('Data Berhasil Dihapus', 'Success')
                            get_data()
                        }else if (response.Code === 0){
                            toastr.warning('Periksa Kembali Data Input Anda', 'Warning')
                        }else {
                            toastr.error('Terdapat Kesalahan System', 'System Error')
                        }
                    }
                })

            }

        })
    }
</script>
