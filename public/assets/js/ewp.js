var ewpHandleErrorResponse = function (response) {
    if (response.status == 500) {
        swal.fire('Oopss...', 'Internal Server Error', 'error')
        return false
    }

    if (response.status == 404) {
        swal.fire('Oopss...', 'URL Not Found', 'error')
        return false
    }

    if (response.status == 422) {
        swal.fire('Oopss...', response.responseJSON.message, 'error')
        return false
    }

    swal.fire(response.responseJSON.title, response.responseJSON.msg, response.responseJSON.type)
}

var ewpTable = function (data) {
	if (data === 'undefined') {
		console.log('missing param');
	} else {
		var el_class = (data['class'] !== 'undefined') ? data['class'] : '';
		var column = (data['column'] !== 'undefined') ? data['column'] : '';
		var setFooter = (data['footer'] !== 'undefined') ? data['footer'] : false;

        var thead = '';
        var tfoot = '';

        if(column !== 'undefined'){
            for(i=0;i<column.length;i++){
                var width = (column[i]['width'] !== 'undefined') ? column[i]['width'] : '';
                var icon = (column[i]['icon'] !== 'undefined') ? column[i]['icon'] : '';
                var name = (column[i]['name'] !== 'undefined') ? column[i]['name'] : '';

                thead += '<th width="' + width + '%"><i class="' + icon + '"></i>&nbsp; ' + name + '</th>';
                tfoot += '<th width="' + width + '%"><i class="' + icon + '"></i>&nbsp; ' + name + '</th>';
            }
		}

        var html = '<table class="' + el_class + '" data-ride="datatables" style="width: 100%;">'
        +'<thead><tr>' + thead + '</tr></thead>'
        +'<tbody><tr><td>&nbsp;</td></tr></tbody>'
        +((setFooter == true) ? '<tfoot><tr>' + tfoot + '</tr></tfoot>' : '')
        +'</table>';
        
        return html;
	}
}

var ewpDatatables = function (data) {
    var column = []
    var modColumn = []

    for (var i = 0; i < data.column.length; i++) {
        // Menggambar Kolom
        column.push({ "mData": data.column[i]["col"] })

        // Modifikasi kolom
        if(data.column[i]["mod"] != null){
            modColumn.push(data.column[i]["mod"])
        }
    }

    $(data.target).each(function() {
		$(this).dataTable( {
			"bDestroy": true,
			"processing": true,
	    	"serverSide": true,
			"ajax":{
				url: data.url,
				type: "POST",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: data.newParameter
			},
			"sPaginationType": "simple_numbers",
			"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
			"aoColumns": column,
			"aaSorting": [data.sorting],
			"lengthMenu": [ 10, 25, 50, 75, 100 ],
            language: { 
                search: "",
                searchPlaceholder: "Search...",
            },
			"pageLength": 10,
			"aoColumnDefs": modColumn,
			"drawCallback": function (oSettings) {
                var initTooltip = function(el) {
                    var theme = el.data('theme') ? 'tooltip-' + el.data('theme') : '';
                    var width = el.data('width') == 'auto' ? 'tooltop-auto-width' : '';
                    var trigger = el.data('trigger') ? el.data('trigger') : 'hover';
            
                    $(el).tooltip({
                        trigger: trigger,
                        template: '<div class="tooltip ' + theme + ' ' + width + '" role="tooltip">\
                            <div class="arrow"></div>\
                            <div class="tooltip-inner"></div>\
                        </div>'
                    });
                }
            
                var initTooltips = function() {
                    // init bootstrap tooltips
                    $('[data-toggle="tooltip"]').each(function() {
                        initTooltip($(this));
                    });
                }
            },
			"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
				$(nHead).children('th').addClass('text-center');
			},
			"fnFooterCallback": function( nFoot, aData, iStart, iEnd, aiDisplay ) {
				$(nFoot).children('th').addClass('text-center');
			},
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				for (var i = 0; i < data.column.length; i++) {
                    if (data.column[i]["mid"])
                        $(nRow).children('td:nth-child(' + (i + 1) + ')').addClass('text-center')
                }
			}

		})
	}).closest( '.dataTables_wrapper' ).find( 'select' ).select2( {minimumResultsForSearch: -1})
}

var handleError = function (response) {
    if(response.status == 500){
        Swal.fire('Oopss...', 'Internal Server Error', 'error')
        return false
    }

    if(response.status == 404){
        Swal.fire('Oopss...', 'URL Not Found', 'error')
        return false
    }

    if(response.status == 405){
        Swal.fire('Oopss...', 'Method Not Allowed Http Exception', 'error')
        return false
    }

    if(response.status == 422){
        Swal.fire('Oopss...', 'Unprocessable Entity', 'error')
        return false
    }
    
    // Swal.fire(response.responseJSON.title, response.responseJSON.msg, response.responseJSON.type)

    Swal.fire({
        title: response.responseJSON.title,
        html: response.responseJSON.msg,
        icon: response.responseJSON.type,
        allowOutsideClick: false
    })

}