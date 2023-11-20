<?php defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$CI->load->model('Web_settings');
$CI->load->model('Pay_withs');
$CI->load->model('Companies');
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
$Web_settings   = $CI->Web_settings->retrieve_setting_editdata();
$pay_withs      = $CI->Pay_withs->pay_with_list_for_homepage();

$company_info   = $CI->Companies->company_list();
?>
<!--======== Footer Area ========-->
<footer>
    <div class="container">
        <div class="row footer_inner">
            <div class="col-lg-4 col-md-6 hidden-sm widget about_us_widget">

                <div class="footer_logo">
                    <a href="<?php echo base_url()?>">
                        <img src="<?php echo base_url().$Web_settings[0]['footer_logo']?>" alt="company-logo">
                    </a>
                </div>

                <p><?php echo$Web_settings[0]['footer_details']; ?></p>

                <address>
                    <p><?php echo display('address')?>: <?php echo $company_info[0]['address'];?></p>
                </address>
                <div class="contact_info">
                    <span><?php echo display('mobile')?>: </span><a href="#"><?php echo $company_info[0]['mobile'];?></a>
                </div>
                <div class="contact_info">
                    <span><?php echo display('email')?>: </span><a href="#"><?php echo $company_info[0]['email'];?></a> 
                </div>
                <div class="contact_info">
                    <span><?php echo display('website')?>: </span><a href="#"><?php echo $company_info[0]['website'];?></a>
                </div>
            </div>
            <?php
            $m=1;
            if ($footer_block) {
            foreach ($footer_block as $footer) {?>
            <?php  if($m==1){ ?>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-3">
                <?php }elseif($m==2) { ?>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-2">
                    <?php }elseif($m==3){  ?>
                    <div class="col-md-6 col-lg-3">
                        <?php  } ?>
                        <?php echo $footer->details; ?>
                    </div>
                    <?php   $m++;  }
                    }
                    ?>
                    <?php if (1 == $Web_settings[0]['app_link_status']) { ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="footer-title2">
                                <h4 class="color4" style="padding:0.5em;"><?php echo display('download_the_app') ?></h4>
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
</footer>
<section class="footer_bottom color2">
    <div class="container">
        <div class="row footer_bottom_inner">
            <div class="col-lg-6 col-md-5 b_footer_left">
                <h6><?php if($Web_settings[0]['footer_text']){echo $Web_settings[0]['footer_text'];}?></h6>
            </div>
            <?php if( 1 == $Web_settings[0]['pay_with_status']){?>
            <div class="col-lg-6 col-md-7 b_footer_right">
                <ul class="justify-content-end">
                    <li><h6><?php echo display('pay_with')?> :</h6></li>
                    <?php 
                    if($pay_withs){
                    foreach($pay_withs as $pay_with):?>
                        <li><a href="<?php echo $pay_with['link']; ?>" target="_blank"><img src="<?php echo base_url()?>my-assets/image/pay_with/<?php echo $pay_with['image'];?>" alt="#"></a></li>
                    <?php endforeach;
                        }
                    ?>
                </ul>
            </div>
             <?php }?>
        </div>
    </div>
</section>
<!--====== End Footer Area ======-->