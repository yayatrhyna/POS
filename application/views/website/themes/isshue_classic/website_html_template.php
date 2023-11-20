<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
    $CI =& get_instance();
    $CI->load->model('Web_settings');
    $CI->load->model('Companies');
    $CI->load->model('Color_frontends');
    $CI->load->model('Themes');
    $theme = $CI->Themes->get_theme();
    $Web_settings = $CI->Web_settings->retrieve_setting_editdata();
    $company_info = $CI->Companies->company_list();
    $colors = $CI->Color_frontends->retrieve_color_editdata();
    ?>
    <meta name="author" content="<?php if ($company_info['0']['company_name']) {
        echo $company_info['0']['company_name'];
    } ?>">
    <meta name="description" content="<?php if($Web_settings[0]['meta_description']){ echo $Web_settings[0]['meta_description']; } ?>">
    <meta name="keywords" content="<?php if($Web_settings[0]['meta_keyword']){ echo $Web_settings[0]['meta_keyword']; } ?>">

    <title><?php echo (isset($title)) ? $title : "Isshue" ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php if (isset($Web_settings[0]['logo'])) {
        echo base_url().$Web_settings[0]['favicon'];
    } ?>">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/bootstrap/css/bootstrap.min.css')
    ?>"
          rel="stylesheet">

    <!-- FontAwesome Icon CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/font-awesome/css/font-awesome.min.css')
    ?>"
          rel="stylesheet" type="text/css">

    <!-- Jquery UI CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/jquery-ui/jquery-ui.min.css')
    ?>"
          rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/owl-carousel/owl.carousel.min.css')
    ?>"
          rel="stylesheet">

    <!-- Animate CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/wow-js/animate.css')
    ?>"
          rel="stylesheet">

    <!-- Lightbox CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/lightbox/css/lightbox.min.css')
    ?>"
          rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/css/style.css') ?>"
          rel="stylesheet">
    <!-- EasyZoom CSS -->
    <link rel="stylesheet"
          href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/easyzoom/easyzoom.min.css')
          ?>">

    <!-- Responsive Style -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/css/responsive.css') ?>"
          rel="stylesheet">

    <!-- Include SmartWizard CSS -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/SmartWizard-master/dist/css/smart_wizard.css')
    ?>"
          rel="stylesheet" type="text/css">

    <!-- Optional SmartWizard theme -->
    <link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/SmartWizard-master/dist/css/smart_wizard_theme_dots.css')
    ?>" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.32.4/sweetalert2.all.js"></script>
    <!-- Jquery  -->
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/jquery/jquery-3.2.1.min.js')
    ?>"
            type="text/javascript"></script>

    <!-- jquery-ui.min.js -->
    <script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/jquery-ui/jquery-ui.min.js')
    ?>"
            type="text/javascript"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--    google analytics -->
    <?php echo htmlspecialchars_decode($Web_settings[0]['google_analytics']); ?>


    <!-- dynamic template color  -->
    <style>
        .color1 {
            background: <?php echo $colors->color1;?> !important;
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

        .account_area .account_btn:hover {
            background: <?php echo $colors->color2;?> !important;
        }

        .slider_style .owl-nav .owl-next:hover {
            background: <?php echo $colors->color2;?> !important;
            color: #fff;
        }

        .slider_style .owl-nav .owl-prev, .slider_style .owl-nav .owl-next{
            border-color: 1px solid <?php echo $colors->color2;?> !important;
        }
        .slider_style .owl-nav .owl-prev:hover {
            background: <?php echo $colors->color2;?> !important;
            color: #fff;
        }
        .category_menu a:hover {
            background: <?php echo $colors->color2;?> !important;
            color: #fff;
        }

        .slider_style .item .item_hover .nav li a {
            color: <?php echo $colors->color2;?> !important;
        }
        .best_sale .single_product .media-body a:hover {
            color: <?php echo $colors->color3;?> !important;
        }
        .price_range .ui-slider .ui-slider-range {
            background:<?php echo $colors->color3;?> !important;
        }
        .header_top .header_top_inner .connect_area .connectus a:hover {
            color: <?php echo $colors->color3;?> !important;
        }
        .category_menu .dropdown-menu ul li .cat_sub_menu a:hover {
            color: <?php echo $colors->color3;?> !important;
        }
    </style>

</head>

<body>
<!--==== Preloader =======-->
<div class="preloader"></div>

<!--========== Header Top Area ==========-->
<div class="header_top">
    <div class="container">
        <div class="row m0 header_top_inner">
            <div class="col-lg-5 hidden-md-down">
                <div class="row connect_area">
                    <div class="connectus">
                        <a href="#"><i
                                    class="fa fa-phone"></i><?php echo display('have_a_question') ?> <?php echo display('call_us') ?>
                            : <?php echo $company_info[0]['mobile'] ?></a>
                    </div>
                    <div class="connectus">
                        <a href="#"><i class="fa fa-envelope-o"></i> <?php echo display('email') ?>
                            : <?php echo $company_info[0]['email'] ?></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row changing_area justify-content-end justify-content-center-sm">
                    <div class="l_change">
                        <span class="hidden-md-down"><?php echo display('language'); ?>: </span>
                        <?php
                        $user_lang = $this->session->userdata('language');
                        echo form_dropdown('language', $languages, $user_lang, 'id="change_language"');
                        ?>
                    </div>
                    <div class="m_change">
                        <span class="hidden-md-down"><?php echo display('currency') ?>:</span>
                        <select id="change_currency" name="change_currency">
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
                    </div>
                    <ul class="login_option hidden-lg-up">
                        <li><a href="<?php echo base_url('login') ?>" class="go_btn"><?php echo display('login');
                                ?></a></li>
                        <li><a href="<?php echo base_url('signup') ?>" class="go_btn"><?php echo display('signup');
                        ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--========== End Header Top Area ==========-->

<?php $this->load->view('website/themes/' . $theme . '/include/admin_header'); ?>
{content}
<?php $this->load->view('website/themes/' . $theme . '/include/admin_footer'); ?>

<a href="#0" class="cd-top color4">
    <i class="fa fa-arrow-up"></i>
</a>

<!-- Bootstrap  -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/bootstrap/js/tether.min.js') ?>"
        type="text/javascript"></script>
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/bootstrap/js/bootstrap.min.js') ?>"
        type="text/javascript"></script>

<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<!-- Include SmartWizard JavaScript source -->
<script type="text/javascript"
        src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/SmartWizard-master/dist/js/jquery.smartWizard.min.js') ?>"></script>

<!-- Owl Carousel -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/owl-carousel/owl.carousel.min.js') ?>"
        type="text/javascript"></script>

<!-- EasyZoom -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/easyzoom/easyzoom.min.js') ?>"
        type="text/javascript"></script>

<!-- DSCount JS -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/dscountdown/dscountdown.min.js') ?>"
        type="text/javascript"></script>

<!-- WoW js -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/wow-js/wow.min.js') ?>"></script>

<!-- Lightbox js -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/vendor/lightbox/js/lightbox.min.js') ?>"></script>

<!-- Simple Share js -->
<!--<script src="--><?php //echo base_url('application/views/website/themes/' . $theme . '/assets/website/js/jquery.simpleSocialShare.min.js') ?><!--"></script>-->
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5e0ae8ebb601870012fd40e1&product=inline-share-buttons" async="async"></script>

<!-- Custom scripts for this template -->
<script src="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/js/theme.js') ?>"></script>

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
