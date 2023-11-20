<?php defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$CI->load->model('Web_settings');
$CI->load->model('Pay_withs');
$CI->load->model('Companies');
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
$pay_withs = $CI->Pay_withs->pay_with_list_for_homepage();
$company_info = $CI->Companies->company_list();
?>
<!--=========== Newsletter Area ===========-->
<section class="newsletter color3">
    <div class="container">
        <div class="row m0 newsletter_inner bg_gray">
            <div class="col-lg-6 col-xl-5">
                <div class="row m0 newsletter_left_area">
                    <h4 class="newsletter-title"><?php echo display('sign_up_for_news_and') ?> <span
                                class="color5"><?php echo display('offers') ?></span></h4>
                </div>
            </div>
            <div class="col-lg-6 col-xl-7">
                <div id="sub_msg"></div>
                <form action="#" class="row m0 newsletter_form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="<?php echo display('enter_your_email') ?>"
                               required="" id="sub_email">
                        <span class="input-group-btn">
                            <a href="#" style="margin-top: 0;" class="btn btn-default subscribe color5" type="button"
                                    id="smt_btn"><i class="fa
                             fa-paper-plane color11"></i></a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!--========= End Newsletter Area =========-->

<!-- Newsletter ajax code start-->
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

<footer class="big-footer color2">
    <div class="container">
        <div class="page-scroll back-top color4" data-section="#top">
            <i class="lnr lnr-chevron-up"></i>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="footer-box">
                    <div class="footer-logo">
                        <a href="<?php echo base_url() ?>"><img src="<?php echo base_url().$Web_settings[0]['footer_logo'] ?>" class="img-responsive" alt="logo"></a>
                    </div>
                    <p><?php echo$Web_settings[0]['footer_details']; ?></p>
                    <address>
                        <p><?php echo display('address') ?>: <?php echo $company_info[0]['address']; ?></p>
                    </address>
                    <div class="contact_info">
                        <span><?php echo display('mobile') ?>: </span><a href="tel:<?php echo $company_info[0]['mobile']; ?>"><?php echo $company_info[0]['mobile']; ?></a>
                    </div>
                    <div class="contact_info">
                        <span><?php echo display('email') ?>: </span><a
                                href="#"><?php echo $company_info[0]['email']; ?></a>
                    </div>
                    <div class="contact_info">
                        <span><?php echo display('website') ?>: </span><a
                                href="#"><?php echo $company_info[0]['website']; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 col-sm-offset-1 hidden-xs">
                <div class="row footer_inner">
                    <?php
                    $q=1;
                    if ($footer_block) {
                        foreach ($footer_block as $footer) {?>
                            <div class="col-md-4 <?php echo(($q==1)? "hidden-sm":"");?>">
                                <div class="footer-box">
                                    <?php echo $footer->details; ?>
                                </div>
                            </div>
                            <?php
                            $q++;
                        }
                    }
                    ?>
                    <?php if (1 == $Web_settings[0]['app_link_status']) { ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="footer-title2">
                                <h4 class="color4" style="padding:0.5em; color:#f6f6f6;"><?php echo display('download_the_app') ?></h4>
                            </div>
                            <div class="app-text">
                                <p><?php echo display('get_access_to_all_exclusive_offers') ?></p>
                            </div>
                            <br>
                            <div class="apps color44">
                                <a href="<?php if($Web_settings[0]['apps_url']){ echo $Web_settings[0]['apps_url'];}else{ echo "https://play.google.com/store/apps/details?id=com.bdtask.isshues";}  ?>" target="_blank"><img
                                            src="<?php echo base_url() . 'application/views/website/themes/' . $theme . '/assets/img/play-store-1.png' ?>"
                                            class="img-responsive"
                                            alt="image">
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="subfooter">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="copy-text"><?php if ($Web_settings[0]['footer_text']) {
                        echo $Web_settings[0]['footer_text'];
                    } ?></div>
            </div>
            <div class="col-sm-6">
                <?php if (1 == $Web_settings[0]['pay_with_status']) { ?>
                    <ul class="list-inline pull-right">
                        <li><h6><?php echo display('pay_with') ?> :</h6></li>
                        <?php
                        if ($pay_withs) {
                            foreach ($pay_withs as $pay_with):?>
                                <li><a href="<?php echo $pay_with['link']; ?>" target="_blank"><img width="30" height="30" src="<?php echo base_url() ?>my-assets/image/pay_with/<?php echo $pay_with['image']; ?>" alt="#"></a></li>
                            <?php endforeach;
                        }
                        ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- /.End of Footer -->
<footer class="mobile-footer">
    <div class="container">
        <div class="row">
            <?php  if ($footer_block) { ?>
                <?php
                foreach ($footer_block as $footer) {
                    echo $footer->details;
                }
                ?>
            <?php } ?>
            <span class="copyright"><?php echo display('copyright')?></span>
        </div>
    </div>
</footer>


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

<!-- Add to cart by ajax -->
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
                title: '<?php echo display('please_login_first')?>'
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
                    })
                }else if(data == '2'){
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('product_already_exists_in_wishlist')?>'
                    })
                }else if(data == '3'){
                    Swal({
                        type: 'warning',
                        title: '<?php echo display('please_login_first')?>'
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
                })
            }else if(data == '2'){
                Swal({
                    type: 'warning',
                    title: '<?php echo display('product_not_remove_from_wishlist')?>'
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
</script>


