$(document).ready(function () {
    "use strict";
    // select 2 dropdown 
    $("select.form-control:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true
    });

    //form validate
    $("#validate").validate();
    $("#add_category").validate();
    $("#add_supplier").validate();

    (function($) {
        $('.product-list').slimScroll({
            size: '10px',
            height: '500px',
            allowPageScroll: true,
            railVisible: false
        });
    
        $('.product-grid').slimScroll({
            size: '10px',
            height: '350px',
            allowPageScroll: true,
            railVisible: false
        });
    })(jQuery);



    //summernote
    $('.summernote').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true     // set focus to editable area after initializing summernote
    });

    // views/invoice/invoice.php
    // alert('Invoice');
    //datatables
    var table = $('#dataTableExampleMngInv').DataTable({
        "bDestroy": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        // "<?php echo site_url('Cinvoice/get_invoice_list')?>",
        "ajax": {
            "url": "/Cinvoice/get_invoice_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],

    });

    $('#filter_payment_method').on('change', function() {
        var filter_payment_method = $('#filter_payment_method').val();
        $('#payment_method').val(filter_payment_method);
        // if (filter_payment_method == "All") {
        //     window.location.href = '/Admin_dashboard/todays_sales_report';
        //     return false;
        // }
    });

});