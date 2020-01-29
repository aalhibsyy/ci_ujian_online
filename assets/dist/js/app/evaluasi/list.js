var table;

$(document).ready(function () {

    ajaxcsrf();

    table = $("#evaluasi").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#evaluasi_filter input')
                .off('.DT')
                .on('keyup.DT', function (e) {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_url+"evaluasi/list_json",
            "type": "POST",
        },
        columns: [
            {
                "data": "id_materi",
                "orderable": false,
                "searchable": false
            },
            { "data": 'nama_materi' },
            { "data": 'nama_pengajar' },
            {
                "searchable": false,
                "orderable": false
            }
        ],
        columnDefs: [
            {
                "targets": 3,
                "data": {
                    "id_materi": "id_materi",
                    "ada": "ada"
                },
                "render": function (data, type, row, meta) {
                    var btn;
                    if (data.ada > 0) {
                        btn = `
								<a class="btn btn-xs btn-success" href="#">
									Sudah Pernah Isi Evaluasi
								</a>`;
                    } else {
                        btn = `<a class="btn btn-xs btn-primary" href="${base_url}evaluasi/isi/${data.id_materi}">
								<i class="fa fa-pencil"></i> Isi Evaluasi
							</a>`;
                    }
                    return `<div class="text-center">
									${btn}
								</div>`;
                }
            },
        ],
        order: [
            [1, 'asc']
        ],
        rowId: function (a) {
            return a;
        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        }
    });
});