<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
    <meta name="author" content="<?php if ($company_info['0']['company_name']) {
        echo $company_info['0']['company_name'];
    } ?>">
    <meta name="description" content="<?php if($Web_settings[0]['meta_description']){ echo $Web_settings[0]['meta_description']; } ?>">
    <meta name="keywords" content="<?php if($Web_settings[0]['meta_keyword']){ echo $Web_settings[0]['meta_keyword']; } ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo (isset($title)) ? $title : "Isshue" ?></title>

    <link rel="shortcut icon" href="<?php echo base_url().$Web_settings[0]['favicon']; ?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/css/bootstrap.min.css') ?>"
          rel="stylesheet">
    <?php
    if ($Soft_settings[0]['rtr'] == 1) {
        ?>
        <!-- Bootstrap rtl -->
        <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/css/bootstrap.rtl.min.css') ?>"
              rel="stylesheet" type="text/css"/>
        <?php
    }
    ?>
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/animate/animate.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/font-awesome/css/font-awesome.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/flag-icon/css/flag-icon.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/linearicons/linearicons.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/metismenu/metisMenu.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/malihu-scrollbar/jquery.mCustomScrollbar.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/photoswipe.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/default-skin/default-skin.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/slick/slick.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/slick/slick-theme.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/magnific-Popup/magnific-popup.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/star-rating/star-rating.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/ion.rangeSlider/css/ion.rangeSlider.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinFlat.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/jquery-nice-select-1.1.0/css/nice-select.css') ?>"
          rel="stylesheet">

    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/css/style.css?v=2" rel="stylesheet') ?>">
    <?php
    if ($Soft_settings[0]['rtr'] == 1) {
        ?>
        <!-- Bootstrap rtl -->
        <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/css/style-rtl.css') ?>"
              rel="stylesheet" type="text/css"/>
        <?php
    }
    ?>
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/jquery-3.3.1.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.32.4/sweetalert2.all.js"></script>
    <!--    google analytics -->
    <?php echo htmlspecialchars_decode($Web_settings[0]['google_analytics']); ?>

    <!-- dynamic template color  -->
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

        .color3 {
            background: <?php echo $colors->color3;?> !important;
        }

        .color35 {
            color: <?php echo $colors->color3;?> !important;
        }

        .color36 {
            border-color: <?php echo $colors->color3;?> !important;
        }

        .color4 {
            background: <?php echo $colors->color4;?> !important;
        }

        .color5 {
            color: <?php echo $colors->color4;?> !important;
        }

        .widget_title::before {
            background: <?php echo $colors->color4;?> !important;
        }

        .bg_gray::before {
            background: <?php echo $colors->color4;?> !important;
        }

        .rate-container i {
            color: <?php echo $colors->color4;?> !important;
        }

        .rate-container i:hover {
            color: <?php echo $colors->color2;?> !important;
        }

        .product_review_area .product_review .tab-content .tab-pane a:hover, .product_review_area .product_review .tab-content .tab-pane a.active {
            background: <?php echo $colors->color2;?> !important;
        }

        .product_review_area .product_review .nav .nav-item .nav-link.active, .product_review_area .product_review .nav .nav-item .nav-link:hover {
            background: <?php echo $colors->color3;?> !important;
        }

        .account_area .account_btn {
            color: <?php echo $colors->color3;?> !important;
            border: 1px solid <?php echo $colors->color3;?> !important;
        }

        .main-nav .nav > li > a:hover {
            background-color: <?php echo $colors->color3;?> !important;
        }

        .account_area .account_btn:hover {
            background: <?php echo $colors->color2;?> !important;
        }

        .slick-prev, .slick-next {
            background: <?php echo $colors->color3;?> !important;
        }

        .vertical-menu::after {
            position: absolute;
            top: -8px;
            display: inline-block;
            border-right: 7px solid transparent;
            border-bottom: 7px solid <?php echo $colors->color3;?> !important;
            border-left: 7px solid transparent;
            content: '';
            left: 15px;
        }

        .icon-tips b {
            position: absolute;
            display: block;
            height: 0;
            line-height: 0;
            border-width: 6px;
            border-style: solid;
            bottom: -9px;
            left: 0;
            border-color: <?php echo $colors->color3;?> transparent transparent;
            z-index: 1;
}

        .box-bottom .btn-add-cart a {
            display: inline-block;
            color: #fff;
            font-size: 12px;
            height: 34px;
            line-height: 34px;
            border-radius: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 3;
            width: 70%;
            font-weight: 600;
            vertical-align: middle;
            background-color: <?php echo $colors->color3;?> !important;
        }

        .title-widget span {
            border: solid #ea5452;
        }

        .title-widget span::before {
            position: absolute;
            content: '';
            width: 0;
            height: 0;
            border-bottom: 39px solid #ea5452;
            border-right: 39px solid transparent;
            top: -1px;
            right: -39px;
        }


        .main-nav .nav > li.active > a{
            color:#fff;
            background: <?php echo $colors->color3;?> !important;
        }
    </style>

</head>
<body>
<div class="page-wrapper">
    <header class="main-header color36">
        <nav class="topBar hidden-xs">
            <div class="container">
                <ul class="list-inline pull-left">
                    <li>
                        <span class="text-primary"><?php echo display('have_a_question') ?> </span> <?php echo display('call_us') ?>
                        : <?php echo $company_info[0]['mobile'] ?></li>
                    <li><a href="#"><i class="fa fa-envelope-o"></i> <?php echo display('email') ?>
                            : <?php echo $company_info[0]['email'] ?></a></li>
                </ul>
                <ul class="topBarNav pull-right">
                    <li>
                        <select id="change_currency" name="change_currency" class='select resizeselect'>
                            <?php
                            $currency_new_id = $this->session->userdata('currency_new_id');
                            if ($currency_info) {
                                foreach ($currency_info as $currency) {
                                    ?>
                                    <option value="<?php echo $currency->currency_id ?>" <?php
                                    if (!empty($currency_new_id)) {
                                        if ($currency->currency_id == $currency_new_id) {
                                            echo "selected";
                                        }
                                    } else {
                                        if ($currency->currency_id == $selected_cur_id) {
                                            echo "selected";
                                        }
                                    }
                                    ?>><?php echo $currency->currency_name ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <?php
                        $css = [
                            'class' => 'select resizeselect',
                            'id' => 'change_language'
                        ];
                        $user_lang = $this->session->userdata('language');
                        echo form_dropdown('language', $languages, $user_lang, $css);
                        ?>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="page-wrapper">

            <?php $this->load->view('website/themes/' . $theme . '/include/admin_header'); ?>
            {content}
            <?php $this->load->view('website/themes/' . $theme . '/include/admin_footer'); ?>


            <!-- /.End of mobile footer -->
        </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/anime.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/metismenu/metisMenu.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/malihu-scrollbar/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/slick/slick.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/elevatezoom/jquery.elevateZoom-3.0.8.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/photoswipe.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/PhotoSwipe/photoswipe-ui-default.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/magnific-Popup/jquery.magnific-popup.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/star-rating/star-rating.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/theia-sticky-sidebar/ResizeSensor.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/ion.rangeSlider/js/ion.rangeSlider.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/jQuery-slimScroll/jquery.slimscroll.min.js') ?>"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/prognroll/prognroll.min.js') ?>"></script>
<?php
if ($Soft_settings[0]['rtr'] == 1) {
    ?>
    <!-- Bootstrap rtl -->
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/script-rtl.js') ?>"></script>

<?php
}else{
?>
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/js/script.js') ?>"></script>
<?php } ?>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js') ?>"></script>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- Simple Share js -->
<!--<script src="--><?php //echo base_url('application/views/website/themes/' . $theme . '/assets/js/jquery.simpleSocialShare.min.js') ?><!--"></script>-->
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5e0ae8ebb601870012fd40e1&product=inline-share-buttons" async="async"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGwh3ShY_W1hMms1wmwlHK3hpInhExn3o"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/gmap/map-active.js')
?>"></script>
<script>
    //Simple share

    // $(document).ready(function () {
    //     $('.share-button').simpleSocialShare(); // for social media share
    //     $('.select').niceSelect(); //for nice select
    // });

</script>
<style>
    .title-widget span::after {
        border-bottom: <?php echo $colors->color3;?> 38px solid !important;
    }

    .btn-add-cart a {
        background-color: <?php echo $colors->color3;?> !important;
    }

</style>
<script type="text/javascript">

    //Simple share
    // $('.share-button').simpleSocialShare();

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

    //Add to cart by ajax
    function add_to_cart(id) {
        var product_id = $('#product_id_' + id).val();
        var price = $('#price_' + id).val();
        var discount = $('#discount_' + id).val();
        var qnty = $('#qnty_' + id).val();
        var image = $('#image_' + id).val();
        var name = $('#name_' + id).val();
        var model = $('#model_' + id).val();
        var supplier_price = $('#supplier_price_' + id).val();
        var cgst = $('#cgst_' + id).val();
        var cgst_id = $('#cgst_id_' + id).val();
        var sgst = $('#sgst_' + id).val();
        var sgst_id = $('#sgst_id_' + id).val();
        var igst = $('#igst_' + id).val();
        var igst_id = $('#igst_id_' + id).val();

        if (product_id == 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('ooops_something_went_wrong')?>'
            });
            return false;
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/add_to_cart')?>',
            data: {
                product_id: product_id,
                price: price,
                discount: discount,
                qnty: qnty,
                image: image,
                name: name,
                model: model,
                supplier_price: supplier_price,
                cgst: cgst,
                cgst_id: cgst_id,
                sgst: sgst,
                sgst_id: sgst_id,
                igst: igst,
                igst_id: igst_id,
            },
            beforeSend: function () {
                $('.preloader').html("<img src='<?php echo base_url('assets/website/image/loader.gif')?>'>");
            },
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
    }

    //Add to cart by ajax
    function cart_btn(product_id) {
        var qnty = $('#sst').val();
        var variant = $('#select_size1').val();
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
        if (variant != 'undefine') {
            if (variant <= 0) {

                Swal({
                    type: 'warning',
                    title: '<?php echo display('please_select_product_size')?>'
                });
                return false;
            }
        }

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
</script>
<?php echo htmlspecialchars_decode($Web_settings[0]['facebook_messenger']); ?>
</body>
</html>