<!DOCTYPE html>
<html lang="en">
<?php
$CI =& get_instance();
$CI->load->model('Web_settings');
$CI->load->model('Soft_settings');
$CI->load->model('Companies');
$CI->load->model('Color_frontends');
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
$company_info = $CI->Companies->company_list();
$colors = $CI->Color_frontends->retrieve_color_editdata();
$Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();

?>
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if(!empty($image_large_details)){  ?>
        <meta property="og:type" content="product" />
        <meta property="og:title" content="<?php echo $product_name ?>">
        <meta property="og:image" content="<?php echo base_url().$image_large_details;  ?>"/>
        <meta property="og:description" content="<?php echo strip_tags($product_details);  ?>" />
        <!--        -------------------->
        <meta property="og:site_name" content="<?php if ($company_info['0']['company_name']) {
            echo $company_info['0']['company_name'];
        } ?>" />
        <meta property="og:image:width" content="100" />
        <meta property="og:image:height" content="100" />
        <meta property="og:locale" content="en_US" />

    <?php  } ?>
    <meta name="author" content="<?php if ($company_info['0']['company_name']) {
        echo $company_info['0']['company_name'];
    } ?>">
    <meta name="description" content="<?php if($Web_settings[0]['meta_description']){ echo $Web_settings[0]['meta_description']; } ?>">
    <meta name="keywords" content="<?php if($Web_settings[0]['meta_keyword']){ echo $Web_settings[0]['meta_keyword']; } ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo (isset($title)) ? $title : "Isshue" ?></title>

    <link rel="shortcut icon" href="<?php echo base_url().$Web_settings[0]['favicon']; ?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/css/bootstrap.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/animate/animate.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/flag-icon/css/flag-icon.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/linearicons/linearicons.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/jquery-nice-select-1.1.0/css/nice-select.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/metismenu/metisMenu.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/malihu-scrollbar/jquery.mCustomScrollbar.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/PhotoSwipe/photoswipe.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/PhotoSwipe/default-skin/default-skin.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/slick/slick.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/slick/slick-theme.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/magnific-Popup/magnific-popup.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/star-rating/star-rating.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/ion.rangeSlider/css/ion.rangeSlider.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinFlat.min.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/css/style.css?v=1" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/css/responsive.css" rel="stylesheet"')?>">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme .'/assets/css/custom.css" rel="stylesheet"')?>">
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/jquery-3.4.1.min.js');?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.32.4/sweetalert2.all.js"></script>

    <style>
        .color1 {
            background: <?php echo $colors->color1;?> !important;
        }

        .color11 {
            color: <?php echo $colors->color1;?> !important;
        }

        .color2 {
            background: <?php echo $colors->color2;?> !important;
        }
        .color22 {
            color: <?php echo $colors->color2;?> !important;
        }

        .color3 {
            background: <?php echo $colors->color3;?> !important;
        }
        .color33 {
            color: <?php echo $colors->color3;?> !important;
        }

        .color4 {
            background: <?php echo $colors->color4;?> !important;
        }
        .color44 {
            color: <?php echo $colors->color4;?> !important;
        }
        .style-1 .middleBar .form-control, .style-1 .input-group-addon {
            border-color: <?php echo $colors->color3;?> !important;
        }

        .style-1 .main-nav .nav > li > a:hover {
            background: <?php echo $colors->color2;?> !important;
        }

        .style-1 .slider .slick-prev,  .style-1 .slider .slick-next {
            background: <?php echo $colors->color3;?> !important;
        }

        .text-uppercase .active {
            background: <?php echo $colors->color1;?> !important;
        }
       .hover_content > .wishlist {
            background: <?php echo $colors->color4;?> !important;
        }

        .wishlist {
            color: <?php echo $colors->color4;?> !important;
        }
        .posted-in a {
            color: <?php echo $colors->color3;?> !important;
        }
        .hover_content .nav li a {
            background: <?php echo $colors->color4;?> !important;
        }

        .style-1 .pagination > .active > a {
            background: <?php echo $colors->color4;?> !important;
        }
        .star-rating {
            color:<?php echo $colors->color4;?> !important;
        }
        .rating-wrap {
            color:<?php echo $colors->color4;?> !important;
        }

        .hover-box .nav li a {
            background:<?php echo $colors->color4;?> !important;
            border:<?php echo $colors->color4;?> !important;
        }
        .hover-box .nav li a:hover {
            border-color:<?php echo $colors->color4;?> !important;
        }

        .hover-info .nav li:last-child a {
            background:<?php echo $colors->color4;?> !important;
            border:<?php echo $colors->color3;?> !important;
        }

        .product_slider .item .item_info .item_title2 {
            color:<?php echo $colors->color3;?> !important;
        }

        .sec_title{
            background:<?php echo $colors->color3;?> !important;
        }

        .fa-heart-o,.fa-heart,.glyphicon-star{
            color:#fff !important;
        }

        .glyphicon-star{
            color:<?php echo $colors->color4;?> !important;
        }
        .cart-btn{
            background:<?php echo $colors->color3;?> !important;
        }




</style>
        </head>
<?php $this->load->view('website/themes/' . $theme . '/include/admin_header'); ?>
{content}
<?php $this->load->view('website/themes/' . $theme . '/include/admin_footer'); ?>
    <!-- /.End of header -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/anime.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/metismenu/metisMenu.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/owl-carousel/owl.carousel.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/malihu-scrollbar/jquery.mCustomScrollbar.concat.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/slick/slick.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/elevatezoom/jquery.elevateZoom-3.0.8.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/photoswipe.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/photoswipe-ui-default.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/magnific-Popup/jquery.magnific-popup.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/star-rating/star-rating.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/dscountdown/dscountdown.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/theia-sticky-sidebar/ResizeSensor.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/ion.rangeSlider/js/ion.rangeSlider.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/jQuery-slimScroll/jquery.slimscroll.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/prognroll/prognroll.min.js');?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/script.js');?>"></script>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>





<script type="text/javascript">
    //Subscribe entry

    $('#sub_email').keypress(function (e) {
        if (e.which == 13) {

            $('#smt_btn').click();
        }
    });
    $('body').on('click', '#smt_btn', function (e) {
        e.preventDefault();
        var sub_email = $('#sub_email').val();
        if (sub_email == 0) {
            Swal({
                type: 'warning',
                title: '<?php echo display('please_enter_email')?>'
            });
            return false;
        }
        if( !validateEmail(sub_email)) {
            Swal({
                type: 'warning',
                title: '<?php echo display('please_enter_email')?>'
            });
            return false;
        }else {
            $.ajax({
                type: "post",
                async: true,
                url: '<?php echo base_url('website/Home/add_subscribe')?>',
                data: {sub_email: sub_email},
                success: function (data) {
                    console.log('before');
                    if (data == parseInt(2)) {
                        Swal({
                            type: 'success',
                            title: '<?php echo display('subscribe_successfully')?>'
                        });
                        $("#sub_msg").html('<p style="color:green; text-shadow: 1px 3px 5px #000; font-size:1.5em;"><?php
                            echo display('subscribe_successfully')?></p>');
                        $('#sub_msg').fadeOut(4000, function () {
                            $(this).remove();
                        });
                        $("#sub_email").val(" ");
                    } else {
                        $("#sub_msg").html('<p style="color:red><?php echo display('failed')?></p>');
                        $('#sub_msg').fadeOut(4000, function () {
                            $(this).remove();
                        });
                        $("#sub_email").val(" ");
                    }
                },
                error: function () {
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('request_failed')?>'
                    })
                }
            });
        }
    });



    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }
</script>
<!-- Newsletter ajax code end-->

<script type="text/javascript">
    //check default store
    $( document ).ready(function() {
        var stok = $('#stok').val();
        if (stok == "none") {
            Swal({
                type: 'warning',
                title: '<?php echo display('please_set_default_store')?>'
            })
        }
    });

    //Add product to wishlist
    $('body').on('click', '.wishlist', function() {
        var product_id  = $(this).attr('name');
        var customer_id = '<?php echo $this->session->userdata('customer_id')?>';
        if (customer_id == 0) {
            Swal({
                type: 'warning',
                title: '<?php echo display('please_login_first')?>',
                showCancelButton: true,
                cancelButtonText:"<a href='<?php  echo base_url().'login'; ?>'><?php echo display('login')  ?></a>"
            });
            return false;
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/add_wishlist')?>',
            data: {product_id:product_id,customer_id:customer_id},
            success: function(data) {
                if (data == '1') {
                    Swal({
                        type: 'success',
                        title: '<?php echo display('product_added_to_wishlist')?>'
                    });
                    window.location.reload();
                }else if(data == '2'){
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('product_already_exists_in_wishlist')?>'
                    })
                }else if(data == '3'){
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('please_login_first')?>',
                        showCancelButton: true,
                        cancelButtonText:"<a href='<?php  echo base_url().'login'; ?>'><?php echo display('login')  ?></a>"
                    })
                }
            },
            error: function() {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });


    //remove product from wish list

    $('body').on('click', '.remove_wishlist', function() {
        var product_id  = $('.wishlist_product_id').attr('name');
        var customer_id = '<?php echo $this->session->userdata('customer_id')?>';
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/remove_wishlist')?>',
            data: {product_id:product_id,customer_id:customer_id},
            success: function(data) {
                if (data == '1') {
                    Swal({
                        type: 'success',
                        title: '<?php echo display('product_remove_from_wishlist')?>'
                    });
                    window.location.reload();
                }else if(data == '2'){
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('product_not_remove_from_wishlist')?>'
                    });
                }
            },
            error: function() {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });
</script>

<script type="text/javascript">

    //for quick view modal show
    function quick_view(product_id) {
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/quick_view_product')?>',
            data: {product_id: product_id},
            success: function (data) {
                $('#product_view').html(data);
            }
        })
    }


    //Add to cart by ajax
    function cart_btn(product_id, product_name, default_variant='') {

        if(default_variant === 'nai'){
            window.location.replace("<?php echo base_url().'product_details/'; ?>"+product_name+'/'+product_id);
        return false;
        }
        //var variant='';
        var qnty='';
        var details_page_qnty = $('#sst').val();
        var details_page_variant = $('#select_size1').val();
        // if(default_variant !== 'nai'){
        //     variant=default_variant;
        //     qnty=1;
        // }
        if (details_page_variant){
         var   variant= details_page_variant;
            qnty=details_page_qnty;
        }else{
           var variant = default_variant;
            qnty=1;
        }
        var product_quantity = qnty;
        if (product_id == 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('ooops_something_went_wrong')?>'
            });
            return false;
        }
        if (qnty <= 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('please_keep_quantity_up_to_zero')?>'
            });
            return false;
        }
        //if (variant != 'undefine') {
        //    if (variant <= 0) {
        //
        //        Swal({
        //            type: 'warning',
        //            title: '<?php //echo display('please_select_product_size')?>//'
        //        });
        //        return false;
        //    }
        //}

//before add to cart check product stock
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Product/check_quantity_wise_stock')?>',
            data: {product_quantity: product_quantity, product_id: product_id},
            success: function (data) {
                if (data == 'no') {

                    Swal({
                        type: 'warning',
                        title: '<?php echo display('not_enough_product_in_stock')?>'
                    })
                    return false;
                }
                if (data == 'yes') {
                    $.ajax({
                        type: "post",
                        async: true,
                        url: '<?php echo base_url('website/Home/add_to_cart_details')?>',
                        data: {product_id: product_id, qnty: qnty, variant: variant},
                        success: function (data) {
                            $("#tab_up_cart").load(location.href + " #tab_up_cart>*", "");
                            if(default_variant === 'buy'){
                                window.location.replace("<?php echo base_url().'checkout'; ?>");
                            }
                            Swal({
                                type: 'success',
                                title: '<?php echo display('product_added_to_cart')?>'
                            })
                        },
                        error: function () {
                            Swal({
                                type: 'warning',
                                title: '<?php echo display('request_failed')?>'
                            })
                        }
                    });
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    }


    function add_to_cart(product_id, product_name, default_variant='') {

        if(default_variant === 'nai'){
            window.location.replace("<?php echo base_url().'product_details/'; ?>"+product_name+'/'+product_id);
        return false;
        }

           var variant = default_variant;
           var qnty=1;

        var product_quantity = qnty;
        if (product_id == 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('ooops_something_went_wrong')?>'
            });
            return false;
        }
        if (qnty <= 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('please_keep_quantity_up_to_zero')?>'
            });
            return false;
        }
        //if (variant != 'undefine') {
        //    if (variant <= 0) {
        //
        //        Swal({
        //            type: 'warning',
        //            title: '<?php //echo display('please_select_product_size')?>//'
        //        });
        //        return false;
        //    }
        //}

//before add to cart check product stock
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Product/check_quantity_wise_stock')?>',
            data: {product_quantity: product_quantity, product_id: product_id},
            success: function (data) {
                if (data == 'no') {

                    Swal({
                        type: 'warning',
                        title: '<?php echo display('not_enough_product_in_stock')?>'
                    })
                    return false;
                }
                if (data == 'yes') {
                    $.ajax({
                        type: "post",
                        async: true,
                        url: '<?php echo base_url('website/Home/add_to_cart_details')?>',
                        data: {product_id: product_id, qnty: qnty, variant: variant},
                        success: function (data) {
                            $("#tab_up_cart").load(location.href + " #tab_up_cart>*", "");
                            if(default_variant === 'buy'){
                                window.location.replace("<?php echo base_url().'checkout'; ?>");
                            }
                            Swal({
                                type: 'success',
                                title: '<?php echo display('product_added_to_cart')?>'
                            })
                        },
                        error: function () {
                            Swal({
                                type: 'warning',
                                title: '<?php echo display('request_failed')?>'
                            })
                        }
                    });
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    }
</script>
<!-- Customer login by ajax start-->
<script type="text/javascript">
    //$('body').on('click', '.customer_login', function () {
    //    let login_email = $('#user_email').val();
    //    let login_password = $('#u_pass').val();
    //    let remember_me = $('#remember_me').val();
    //
    //    if (login_email === ''  || login_password ==='' ) {
    //        Swal({
    //            type: 'warning',
    //            title: '<?php //echo display('please_type_email_and_password')?>//'
    //        });
    //        return false;
    //    }
    //    $.ajax({
    //        type: "post",
    //        async: true,
    //        url: '<?php //echo base_url('website/customer/Login/checkout_login')?>//',
    //        data: {
    //            login_email: login_email,
    //            login_password: login_password,
    //            remember_me: remember_me
    //        },
    //        success: function (data) {
    //            console.log(data);
    //            if (data === 'true') {
    //                console.log('if');
    //                swal("<?php //echo display('login_successfully')?>// ", "", "success");
    //                //location.reload();
    //            } else {
    //                swal("<?php //echo display('wrong_username_or_password')?>// ", "", "warning");
    //                //location.reload();
    //                console.log('else');
    //            }
    //        },
    //        error: function () {
    //            Swal({
    //                type: 'warning',
    //                title: '<?php //echo display('request_failed')?>//'
    //            })
    //        }
    //    });
    //});
</script>


<!-- Newsletter ajax code start-->
<script type="text/javascript">
    //Subscribe entry
    $('body').on('click', '#smt_btn', function() {
        var sub_email = $('#sub_email').val();
        if (sub_email == 0) {
            Swal({
                type: 'warning',
                title: '<?php echo display('please_enter_email')?>'
            });
            return false;
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/add_subscribe')?>',
            data: {sub_email:sub_email},
            success: function(data) {
                if (data == '2') {
                    $("#sub_msg").html('<p style="color:green"><?php echo display('subscribe_successfully')?></p>');
                    $('#sub_msg').hide().fadeIn('slow');
                    $('#sub_msg').fadeIn(700);
                    $('#sub_msg').hide().fadeOut(2000);
                    $("#sub_email").val(" ");
                }else{
                    $("#sub_msg").html('<p style="color:red><?php echo display('failed')?></p>');
                    $('#sub_msg').hide().fadeIn('slow');
                    $('#sub_msg').fadeIn(700);
                    $('#sub_msg').hide().fadeOut(2000);
                    $("#sub_email").val(" ");
                }
            },
            error: function() {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });
</script>
<!-- Newsletter ajax code end-->

<script type="text/javascript">
    //Check product quantity in stock
    $('#sst,.reduced,.increase').on("change click", function () {
        var product_quantity = $('#sst').val();
        var modal_product_id = $('.single-product-id').data('product-id');
        if(modal_product_id){
            var product_id = modal_product_id;
        }else{
            var product_id = '<?php echo @$product_id;?>';
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Product/check_quantity_wise_stock')?>',
            data: {product_quantity: product_quantity, product_id: product_id},
            success: function (data) {
                if (data == 'no') {

                    Swal({
                        type: 'warning',
                        title: '<?php echo display('not_enough_product_in_stock')?>'
                    });
                    return false;
                }
                if (data == 'yes') {
                    return true;
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });
</script>

<!-- Product delete from cart by ajax -->
<script type="text/javascript">
    $('body').on('click', '.delete_cart_item', function () {
        if (!confirm("<?php echo display('are_you_sure_want_to_delete');?>")){
            return false;
        }
        var row_id = $(this).attr('name');
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/delete_cart/')?>',
            data: {row_id: row_id},
            success: function (data) {
                $("#tab_up_cart").load(location.href + " #tab_up_cart>*", "");
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });
</script>
<script>

    //Simple share
    //$('.share-button').simpleSocialShare();

    //Change language ajax
    $('body').on('change', '#change_language', function () {
        var language = $('#change_language').val();
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/change_language')?>',
            data: {language: language},
            success: function (data) {
                if (data == 2) {
                    location.reload();
                } else {
                    location.reload();
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });
    });


    //Change currency ajax
    $('body').on('change', '#change_currency', function () {
    var currency_id = $('#change_currency').val();
    $.ajax({
    type: "post",
    async: true,
    url: '<?php echo base_url('website/Home/change_currency')?>',
    data: {currency_id: currency_id},
    success: function (data) {
        if (data == 2) {
        location.reload();
        } else {
        location.reload();
        }
    },
    error: function () {
    Swal({
    type: 'warning',
    title: '<?php echo display('request_failed')?>'
        })
    }
    });
    });
</script>

<script>
    $(document).ready(function () {
        "use strict";

        /*  [ per page owl ]
         - - - - - - - - - - - - - - - - - - - - */
        var status1 = $("#callback-page1");
        function callback1(event) {

            var items = event.item.count;
            var item = event.item.index + 1;

            updateResult1(".currentItem", item);
            updateResult1(".owlItems", items);

        }
        function updateResult1(pos, value) {
            status1.find(pos).find(".result").text(value);
        }
        function callback_bg(event) {
            var corlor = $($(".owl-carousel .active .item ")).data('background');
            $('.block-section-top').css('background', corlor);

        }
        /*  [ owl-carousel ]
         - - - - - - - - - - - - - - - - - - - - */
        $(".product-slider").each(function (index, el) {
            var config = $(this).data();
            config.navText = ['', ''];
            config.smartSpeed = "800";

            if ($(this).hasClass('dotsData')) {
                config.dotsData = "true";
            }
            if ($(this).hasClass('testimonials-des')) {
                config.animateOut = "fadeOutDown";
                config.animateIn = "fadeInDown";
            }
            if ($(this).hasClass('callback-page1')) {
                config.onChanged = callback1;
            }
            if ($(this).hasClass('data-bg')) {
                config.onChanged = callback_bg;
            }
            if ($(this).parents("html").hasClass('cms-rtl')) {
                config.rtl = "true";
            }
            $(this).owlCarousel(config);

        });
        /* ------------------------------------------------
         Elevate Zoom
         ------------------------------------------------ */


        $('#img_zoom').elevateZoom({
            zoomType: "inner",
            gallery: 'thumbnails',
            galleryActiveClass: 'active',
            cursor: "crosshair",
            responsive: true,
            easing: true,
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 500,
            lensFadeIn: 500,
            lensFadeOut: 500
        });
    });
</script>

<script>
    function buy_now(product_id) {

        var qnty='';
        var details_page_qnty = $('#sst').val();
        var details_page_variant = $('#select_size1').val();

        if (details_page_variant){
            var   variant= details_page_variant;
            qnty=details_page_qnty;
        }else{
            var variant = default_variant;
            qnty=1;
        }
        var product_quantity = qnty;
        if (product_id == 0) {
            Swal({
                type: 'warning',
                title: '<?php echo display('ooops_something_went_wrong')?>'
            });
            return false;
        }
        if (qnty <= 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('please_keep_quantity_up_to_zero')?>'
            });
            return false;
        }

        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Product/check_quantity_wise_stock')?>',
            data: {product_quantity: product_quantity, product_id: product_id},
            success: function (data) {
                if (data == 'no') {

                    Swal({
                        type: 'warning',
                        title: '<?php echo display('not_enough_product_in_stock')?>'
                    })
                    return false;
                }
                if (data == 'yes') {
                    $.ajax({
                        type: "post",
                        async: true,
                        url: '<?php echo base_url('website/Home/add_to_cart_details')?>',
                        data: {product_id: product_id, qnty: qnty, variant: variant},
                        success: function (data) {
                            $("#tab_up_cart").load(location.href + " #tab_up_cart>*", "");

                            Swal({
                                type: 'success',
                                title: '<?php echo display('product_added_to_cart')?>'
                            })
                            window.location.replace("<?php echo base_url().'checkout'; ?>");
                        },
                        error: function () {
                            Swal({
                                type: 'warning',
                                title: '<?php echo display('request_failed')?>'
                            })
                        }
                    });
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                })
            }
        });

        //if(default_variant === 'nai'){
        //    var product_name = $(this).attr('data-product-name');
        //
        //    window.location.replace("<?php //echo base_url().'product_details/'; ?>//"+product_name+'/'+product_id);
        //}else{
        //    cart_btn(product_id,default_variant);
        //}
    }

//    check existing email when register user
    $('#user_email').on('blur',function () {
        var user_email = $(this).val();
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/customer/signup/check_existing_user')?>',
            data: {user_email: user_email},
            success: function (data) {
               if(data == 1){
                   Swal({
                       type: 'warning',
                       title: '<?php echo display('already_exists')?>'
                   });

                   $('#email_warning').html("<?php echo display('this_email_already_exists')  ?>");
                   $('#email_warning').css({'color':'red'});
                   $('#create_account_btn').prop('disabled', true);
                   return false;
               }else{
                   $('#email_warning').hide();
                   $('#create_account_btn').prop('disabled', false);
               }
            }
        });
    });

    //remove input field browser cache
    //$("form :input").attr("autocomplete", "off");
</script>
</body>

</html>
