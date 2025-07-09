@push('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
@endpush

@push('js')


    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function () {
            $('.dataTable').DataTable({
                dom: "<'row mb-3'" +
                 "<'col-md-8 d-flex align-items-center gap-2'B>" +
                 "<'col-md-4 d-flex justify-content-end align-items-center'lf>" +
                 ">" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",
                buttons: [
                    { extend: 'copy', className: 'btn btn-sm btn-info' },
                    { extend: 'excel', className: 'btn btn-sm btn-success' },
                    { extend: 'pdf', className: 'btn btn-sm btn-danger' },
                    { extend: 'print', className: 'btn btn-sm btn-secondary' }
                ],
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                pagingType: "simple_numbers",
                language: {
                    paginate: {
                        previous: '<i class="fa fa-angle-left"></i>',
                        next: '<i class="fa fa-angle-right"></i>',
                    }
                },

                initComplete: function() {
                    $('.dataTables_length select').addClass('form-select form-select-sm').css('width', '80px');
                    $('.dataTables_filter input').addClass('form-control form-control-sm').css('width', '200px');

                    $('.dataTables_length').addClass('d-flex align-items-center').css('margin-right', '30px');
                    $('.dataTables_filter').addClass('d-flex align-items-center');
                    $('.dataTables_length label').addClass('d-flex align-items-center gap-2');
                    $('.dataTables_filter label').addClass('d-flex align-items-center gap-2');
                },
                drawCallback: function() {
                    $('.dataTables_paginate .page-item').css({
                        'padding': '0',
                        'margin': '0',
                        'border': 'none'
                    });
                    $('.dataTables_paginate .pagination').css('gap', '0');
                }
            });
        });
    </script>
@endpush