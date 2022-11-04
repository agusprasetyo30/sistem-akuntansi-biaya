let table = function () {
	document.getElementById('table-wrapper').innerHTML = ewpTable({
        class : 'table datatable table-bordered text-nowrap',
        column : [
            {name : 'ID', width : '5'},
            {name : 'NAMA', width : '20'},
            {name : 'DESKRIPSI', width : '20'},
            {name : 'STATUS', width : '10'},
            {name : 'ACTION', width : '10'}
        ]
	})
	
	ewpDatatables({
        target : '.datatable',
        url : gridUrl,
		sorting : [0, 'desc'],
		column : [
			{col : 'id', mid : true, mod : {
                'aTargets': [0],
                'mRender': function (data, type, full, draw) {
                    var row = draw.row;
                    var start = draw.settings._iDisplayStart;
                    var length = draw.settings._iDisplayLength;

                    var counter = (start + 1 + row);

                    return counter;
                }
            }},
			{col : 'kategori_produk_name', mid : false, mod : false},
			{col : 'kategori_produk_desc', mid : false, mod : false},
			{col : 'is_active', mid : true, mod : {
                'aTargets': [3],
                'mRender': function (data, type, full) {
					let is_active = '';
                    if (full.is_active == true) {
                        is_active = `<span class="badge bg-success-light border-success fs-11">aktif</span>`
                    } else {
                        is_active = `<span class="badge bg-danger-light border-danger fs-11">non aktif</span>`
					}
					
                    return is_active;
                }
            }},
			{col : 'id', mid : true, mod : {
                'aTargets': [4],
                'mRender': function (data, type, full) {
					let btn_action = `
                    <a href="javascript:;" onclick="detail('` + full.id + `', this)" id="btn-detail-` + full.id + `" class="btn bg-info-transparent" title="detail" data-toggle="tooltip"><i class="fe fe-info"></i></a>
                    <a href="javascript:;" onclick="edit('` + full.id + `', this)" id="btn-ubah-` + full.id + `" class="btn bg-warning-transparent" title="edit" data-toggle="tooltip"><i class="fe fe-edit"></i></a>
                    <a href="javascript:;" onclick="trash('` + full.id + `', this)" id="btn-hapus-` + full.id + `" class="btn bg-danger-transparent" title="hapus" data-toggle="tooltip"><i class="fe fe fe-trash"></i></a>`;
					
                    return btn_action;
                }						
            }},
		]
	})
}

let detail = function (id, e) {
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: detailUrl,
        data: {
            code: id
        },
        success: function (response) {
            $('#modal-title').text('Data Detail')
            $('#modal-body').html(response)
            $('#modal-footer').html(`
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            `)
            $('#modal-dialog').addClass('modal-lg')
            $('#modal').modal('show')
        },
        error: function (response) {
            handleError(response)
        }
    })
}

let add = function () {
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: addUrl,
        success: function (response) {
            $('#modal-title').text('Tambah')
            $('#modal-body').html(response)
            $('#modal-footer').html(`
            <button type="button" class="btn btn-info" id="btn-simpan">Simpan</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            `)
            $('#modal-dialog').addClass('modal-lg')

            $('#is_active').select2({
                dropdownParent: $('#modal'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#modal').modal('show')

            $('#btn-simpan').on('click', function () {
                store(insertUrl, false, '#modal')
            })

            $('#form-input').on('submit', function(){
                store(insertUrl, false, '#modal')
            })
        },
        error: function (response) {
            handleError(response)
        }
    })
}

let edit = function (id, e) {
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: editUrl,
        data: {
            id: id
        },
        success: function (response) {
            $('#modal-title').text('Ubah Data')
            $('#modal-body').html(response)
            $('#modal-footer').html(`
                <button type="button" class="btn btn-info" id="btn-simpan">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            `)
            $('#modal-dialog').addClass('modal-lg')

            $('#is_active').select2({
                dropdownParent: $('#modal'),
                placeholder: 'Pilih Status',
                width: '100%'
            })

            $('#modal').modal('show')

            $('#btn-simpan').on('click', function () {
                store(updateUrl, true)
            })

            $('#form-input').on('submit', function(){
                store(updateUrl, true) 
            })
        },
        error: function (response) {
            handleError(response)
        }
    })
}

let store = function (url, edit = false, modal = '#modal') {
    let formData = new FormData();
    formData.append('id', $('[name="id"]').val())
    formData.append('kategori_produk_name', $('[name="kategori_produk_name"]').val())
    formData.append('kategori_produk_desc', $('[name="kategori_produk_desc"]').val())
    formData.append('is_active', $('[name="is_active"]').val())
    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        processData: false,
        contentType: false,
        url: url,
        data: formData,
        success: function (response) {
            Swal.fire({
                title: response.title,
                text: response.msg,
                icon: response.type,
                allowOutsideClick: false
            })
            .then((result) => {
                if (result.value) {
                    table()
                    $(modal).modal('hide')
                }
            })
        },
        error: function (response) {
            handleError(response)
        }
    })
}

let trash = function (id, e) {
    Swal.fire({
        title: 'Anda akan menghapus member ini !',
        text: 'Anda tidak dapat mengembalikan langkah ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus data!',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: deleteUrl,
                data: {
                    id: id
                },
                success: function (response) {
                    Swal.fire({
                        title: response.title,
                        text: response.msg,
                        icon: response.type,
                        allowOutsideClick: false
                    })
                    .then((result) => {
                        if (result.value) {
                            table()
                        }
                    })
                },
                error: function (response) {
                    handleError(response)
                }
            })
        }
    })
}

let getModal = function () {
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl + '/get-modal',
        success: function (response) {
            $('#modal-area').html(response)
        },
        error: function (response) {
            handleError(response)
        }
    })
}

$(document).ready(function () {
    table()

    getModal()
})