<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$CI =& get_instance();
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
?>

<div class="page-breadcrumbs">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><?php echo display('home') ?></a></li>
            <li><a href="<?php echo base_url('category/p/'.remove_space($category_name).'/' . $category_id) ?>"><?php echo $category_name; ?></a></li>
            <li class="active"><?php echo $product_name; ?></li>
        </ol>
    </div>
</div>
<!-- /.End of page breadcrumbs -->

<?php
$default_currency_id = $this->session->userdata('currency_id');
$currency_new_id = $this->session->userdata('currency_new_id');
if (empty($currency_new_id)) {
    $result = $cur_info = $this->db->select('*')
        ->from('currency_info')
        ->where('default_status', '1')
        ->get()
        ->row();
    $currency_new_id = $result->currency_id;
}

if (!empty($currency_new_id)) {
    $cur_info = $this->db->select('*')
        ->from('currency_info')
        ->where('currency_id', $currency_new_id)
        ->get()
        ->row();

    $target_con_rate = $cur_info->convertion_rate;
    $position1 = $cur_info->currency_position;
    $currency1 = $cur_info->currency_icon;
}
?>
    <div class="product-details-content">
        <div class="product-details-inner">
            <div class="container">
                <div class="row">
                    <?php  if (!empty($select_details_adds)) {
                        foreach ($select_details_adds as $ads):
                            if (1 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                                ?>
                                <div class="my-10">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php echo $ads->adv_code; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } else if (1 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                                <div class="my-10">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <?php echo $ads->adv_code; ?>
                                            </div>
                                            <div class="col-xs-6">
                                                <?php echo $ads->adv_code2; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } else if (1 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                                <div class="my-10">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <?php echo $ads->adv_code; ?>
                                            </div>
                                            <div class="col-xs-4">
                                                <?php echo $ads->adv_code2; ?>
                                            </div>
                                            <div class="col-xs-4">
                                                <?php echo $ads->adv_code3; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php
                        endforeach;
                    } ?>
                    <div class="col-sm-12">
                        <div class="product-header align-items-center">
                            <div class="col-sm-8">
                                <h1 class="product-title"><?php echo $product_name ?></h1>
                                <ul class="entry-meta">
                                    <li class="meta-brand"> <?php echo display('brand') ?>: <a href="<?php echo base_url('brand_product/list/'.$brand_id)?>" class="meta-value"><?php
                                            echo $brand_name ?></a></li>
                                    <li>
                                        <div class="product-rating">
                                            <?php
                                            $result = $this->db->select('sum(rate) as rates')
                                                ->from('product_review')
                                                ->where('product_id', $product_id)
                                                ->get()
                                                ->row();
                                            $rater = $this->db->select('rate')
                                                ->from('product_review')
                                                ->where('product_id', $product_id)
                                                ->get()
                                                ->num_rows();


                                            if ($result->rates != null) {
                                                $total_rate = $result->rates / $rater;
                                                if (gettype($total_rate) == 'integer') {
                                                    for ($t = 1; $t <= $total_rate; $t++) {
                                                        echo "<i class=\"fa fa-star\"></i>";
                                                    }
                                                    for ($tt = $total_rate; $tt < 5; $tt++) {
                                                        echo "<i class=\"fa fa-star-o\"></i>";
                                                    }
                                                } elseif (gettype($total_rate) == 'double') {
                                                    $pieces = explode(".", $total_rate);
                                                    for ($q = 1; $q <= $pieces[0]; $q++) {
                                                        echo "<i class=\"fa fa-star\"></i>";
                                                        if ($pieces[0] == $q) {
                                                            echo "<i class=\"fa fa-star-half-o\"></i>";
                                                            for ($qq = $pieces[0]; $qq < 4; $qq++) {
                                                                echo "<i class=\"fa fa-star-o\"></i>";
                                                            }
                                                        }
                                                    }

                                                } else {
                                                    for ($w = 0; $w <= 4; $w++) {
                                                        echo "<i class=\"fa fa-star-o\"></i>";
                                                    }
                                                }
                                            } else {
                                                for ($o = 0; $o <= 4; $o++) {
                                                    echo "<i class=\"fa fa-star-o\"></i>";
                                                }
                                            }
                                            ?>

                                    </li>
                                    <li class="meta-sku"> <?php echo display('product_model') ?>: <span class="meta-value"><?php echo $product_model;?></span></li>
                                </ul>
                            </div>
                            <div class="col-sm-4">
                                <!-- AddToAny BEGIN -->
                                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                                    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                                    <a class="a2a_button_facebook"></a>
                                    <a class="a2a_button_twitter"></a>
                                    <a class="a2a_button_linkedin"></a>
                                    <a class="a2a_button_pinterest"></a>
                                    <a class="a2a_button_email"></a>
                                </div>
                                <script async src="https://static.addtoany.com/menu/page.js"></script>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="wrapper">
                            <!-- Product Images &amp; Alternates -->
                            <div class="product-images">
                                <div class="row">
                                    <div class="col-xs-12 -col-sm-10">
                                        <div class="product-media media-horizontal">
                                            <div class="image_preview_container images-large">
                                                <img id="img_zoom" data-zoom-image="<?php echo base_url().$image_large_details ?>" src="<?php echo base_url().$image_large_details ?>" alt="">
                                                <button class="btn-zoom open_qv"><span>zoom</span></button>
                                            </div>
                                            <div class="product_preview images-small">
                                                <div class="product-slider owl-carousel thumbnails_carousel" id="thumbnails"  data-nav="true" data-dots="false" data-margin="10" data-responsive='{"0":{"items":6},"480":{"items":6},"600":{"items":6},"768":{"items":6}}'>
                                                    <a href="#" data-image="<?php echo base_url().$image_large_details ?>" data-zoom-image="<?php echo base_url().$image_large_details ?>">
                                                        <img src="<?php echo base_url().$image_large_details ?>" data-large-image="<?php echo base_url().$image_large_details ?>" alt="image">
                                                    </a>
                                                    <?php
                                                    if ($product_gallery_img) {
                                                        foreach ($product_gallery_img as $gallery) {
                                                            ?>
                                                            <a href="#" data-image="<?php echo base_url() . $gallery->image_url; ?>"
                                                               data-zoom-image="<?php echo base_url() . $gallery->image_url; ?>">
                                                                <img src="<?php echo base_url() . $gallery->image_url; ?>"
                                                                     data-large-image="<?php echo base_url() . $gallery->image_url; ?>"
                                                                     alt="image">
                                                            </a>
                                                            <?php
                                                        }
                                                    }
                                                   ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($video) { ?>
                                    <div class="product-video-btn">
                                        <a class="popup-youtube"
                                           href="<?php echo $video; ?>"><span><?php echo display('watch_video'); ?></span></a>
                                    </div>
                                <?php } ?>
                            </div><!-- End Product Images &amp; Alternates -->
                            <!-- Begin Product Image Zoom -->
                            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                                <!-- Background of PhotoSwipe. It's a separate element, as animating opacity is faster than rgba(). -->
                                <div class="pswp__bg"></div>
                                <div class="pswp__scroll-wrap">
                                    <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
                                    <div class="pswp__container">
                                        <!-- don't modify these 3 pswp__item elements, data is added later on -->
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                    </div>
                                    <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                                    <div class="pswp__ui pswp__ui--hidden">
                                        <div class="pswp__top-bar">
                                            <!--  Controls are self-explanatory. Order can be changed. -->
                                            <div class="pswp__counter"></div>
                                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                            <button class="pswp__button pswp__button--share" title="Share"></button>
                                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                            <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                                            <!-- element will get class pswp__preloader-active when preloader is running -->
                                            <div class="pswp__preloader">
                                                <div class="pswp__preloader__icn">
                                                    <div class="pswp__preloader__cut">
                                                        <div class="pswp__preloader__donut"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  /.pswp__top-bar -->
                                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                            <div class="pswp__share-tooltip"></div>
                                        </div>
                                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

                                        <div class="pswp__caption">
                                            <div class="pswp__caption__center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  End product zoom  -->
                        </div>
                        <!-- wrapper -->
                    </div>
                    <div class="col-sm-6">
                        <div class="product-summary-content">
                            <div class="product-summary">
                                <?php  if ($onsale == 1 && !empty($onsale_price)) { ?>
                                    <span class="product-price">
                                                <span class="price-amount">
                                                    <span class="amount-details">
                                                    <?php
                                                    if ($target_con_rate > 1) {
                                                        $price1 = $onsale_price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price1, 2, '.', ',') : number_format($price1, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    if ($target_con_rate <= 1) {
                                                        $price2 = $onsale_price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price2, 2, '.', ',') : number_format($price2, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    ?>
                                                    </span>
                                                    <del><span class="amount2">
                                                    <?php
                                                    if ($target_con_rate > 1) {
                                                        $price = $price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }

                                                    if ($target_con_rate <= 1) {
                                                        $price = $price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    ?>
                                                    </span></del>
                                                    <span class="amount"> </span>
                                                </span>
                                            </span><!-- /.Price -->
                                    <?php
                                } else {
                                    ?>
                                    <span class="product-price">
                                        <span class="price-amount">
                                            <ins><span class="amount-details">
                                               <?php
                                               if ($target_con_rate > 1) {
                                                   $price = $price * $target_con_rate;
                                                   echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                               }

                                               if ($target_con_rate <= 1) {
                                                   $price = $price * $target_con_rate;
                                                   echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                               }
                                               ?>
                                            </span></ins>
                                            <span class="amount"> </span>
                                        </span>
                                    </span>
                                    <?php
                                }
                                ?>

                            </div>

                            <ul class="summary-header">
                                <li>
                                    <p class="stock"><label><?php echo display('status') ?>:</label>
                                        <input type="hidden" value="<?php echo $stok ?>" id="stok">
                                        <span>
                                        <?php
                                        if ($stok > 0) {
                                            echo display('in_stock');
                                        } else {
                                            echo display('out_of_stock');
                                        }
                                        ?>
                                    </span>
                                    </p>
                                </li>
                            </ul>
                            <div class="short-description">
                                <p><?php echo character_limiter(strip_tags($product_details), 200); ?></p>
                                <?php if ($variant) { ?>
                                    <div class="product_size" style="padding: 1em;">
                                        <form action="#" class="form-inline">
                                            <div class="form-group">
                                                <div style="display: inline-block; vertical-align: top;">
                                                    <label for="select_size1" class="variant-label"><?php echo display('product_size') ?> <span
                                                            style="color: red;font-size: 1.8em;"
                                                        >*</span> :
                                                    </label>
                                                </div>
                                                <div style="display: inline-block">
                                                    <select id="select_size1" required="" class="form-control select">
                                                        <option value="0"><?php echo display('select') ?></option>
                                                        <?php
                                                        if ($variant) {
                                                            $exploded = explode(',', $variant);
                                                            foreach ($exploded as $elem) {
                                                                $this->db->select('*');
                                                                $this->db->from('variant');
                                                                $this->db->where('variant_id', $elem);
                                                                $this->db->order_by('variant_name', 'asc');
                                                                $result = $this->db->get()->row();
                                                                ?>
                                                                <option value="<?php echo $result->variant_id ?>" <?php if($result->variant_id == $default_variant){echo "selected";}  ?>><?php echo $result->variant_name ?></option>

                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php
                                }
                                ?>
                                <!--  /.End of product Size -->
                            </div>
                            <form class="cart-row">
                                <div class="cart_counter">
                                    <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 1 ) result.value--;return false;"
                                            class="reduced items-count" type="button">
                                        <span class="qty qty-minus" data-dir="dwn">-</span>
                                    </button>
                                    <input type="text" name="qty" id="sst" maxlength="12" value="1"
                                           title="<?php echo display('quantity') ?>" class="input-text qty form-control text-center" min="1">
                                    <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                            class="increase items-count" type="button">

                                        <span class="qty qty-plus" data-dir="up">+</span>
                                    </button>
                                </div>

                                <?php
                                if ($stok > 0) {
                                    ?>
                                    <a href="#" onclick="cart_btn(<?php echo $product_id ?>)" class="cart-btn" type="submit"><?php echo display('add_to_cart') ?></a>
                                    <a href="#" onclick="buy_now(<?php echo $product_id ?>)" class="cart-btn"><?php echo display('order_now')?></a>

                                <?php } ?>
                                <a href="javascript:void(0)" class="add-wishlist wishlist" data-toggle="tooltip"
                                   data-placement="top"
                                   title="<?php echo display('wishlist')?>" name="<?php echo $product_id ?>"><i class="lnr lnr-heart"></i></a>
                            </form>
                            <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id ?>">
                            <div class="product-meta">
                                <div class="posted-in">
                                    <strong><?php echo display('category')  ?> : </strong>
                                    <a href="<?php  echo base_url().'category/p/'.remove_space($category_name).'/'.$category_id; ?>"><?php  echo $category_name; ?></a>
                                </div>
                                <?php if(!empty($tags)){?>
                                <div class="tag-as">
                                    <strong><?php echo display('tag')  ?> : </strong>
                                    <?php  $tags = explode(',', $tag);
                                    rtrim($tags,',');
                                    foreach($tags as $tag){?>
                                    <a href="#"><?php echo $tag;  ?></a>,
                                    <?php  }  ?>
                                </div>
                                <?php  }  ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php  if (!empty($select_details_adds)) {
                    foreach ($select_details_adds as $ads):
                        if (2 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                            ?>
                            <div class="my-10">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php echo $ads->adv_code; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (2 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                            <div class="my-10">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo $ads->adv_code; ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $ads->adv_code2; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (2 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                            <div class="my-10">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <?php echo $ads->adv_code; ?>
                                        </div>
                                        <div class="col-xs-4">
                                            <?php echo $ads->adv_code2; ?>
                                        </div>
                                        <div class="col-xs-4">
                                            <?php echo $ads->adv_code3; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php
                    endforeach;
                } ?>
            </div>
        </div>
        <?php
        $total_rate = 0;
        $result = $this->db->select('sum(rate) as rates')
            ->from('product_review')
            ->where('product_id', $product_id)
            ->get()
            ->row();
        $rater = $this->db->select('rate')
            ->from('product_review')
            ->where('product_id', $product_id)
            ->get()
            ->num_rows();
        if ($result->rates != null) {
            $total_rate = $result->rates / $rater;
        }
        ?>
        <div class="product-details-tab">
            <div class="text-center">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1default" data-toggle="tab"><?php echo display('review');  ?> (<?php echo $rater ?>)</a></li>
                    <li><a href="#tab2default" data-toggle="tab"><?php  echo display('description') ?></a></li>
                    <li><a href="#tab3default" data-toggle="tab"><?php  echo display('specification') ?></a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-7 content">
                                <div class="rating-block-wrapper">
                                    <h3 class="title"><?php  echo display('rating_and_reviews')?></h3>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="rating-block text-center">
                                                <h4><?php echo display('average_user_rating'); ?></h4>
                                                <div class="rating-point center-block">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                    <h3><?php
                                                        if ($total_rate) {
                                                            echo number_format("$total_rate", 1, ".", ".");
                                                        } else {
                                                            echo "0";
                                                        }
                                                        ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="rating-position">
                                                <h4><?php echo display('rating_breakdown');  ?></h4>
                                                <div class="rating-dimension">
                                                    <div class="rating-quantity">
                                                        <div><?php echo display('5')  ?> <span class="glyphicon glyphicon-star"></span></div>
                                                    </div>
                                                    <div class="rating-percent">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 100%">
                                                                <span class="sr-only"><?php echo display('100_percent_complete');  ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-rating"><?php echo $this->Products_model->get_total_five_start_rating($product_id, 5); ?></div>
                                                </div><!-- /.End of rating dimension -->
                                                <div class="rating-dimension">
                                                    <div class="rating-quantity">
                                                        <div><?php echo display('4') ?> <span class="glyphicon glyphicon-star"></span></div>
                                                    </div>
                                                    <div class="rating-percent">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 80%">
                                                                <span class="sr-only"><?php echo display('80_percent_complete');  ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-rating"><?php echo $this->Products_model->get_total_five_start_rating($product_id, 4); ?></div>
                                                </div><!-- /.End of rating dimension -->
                                                <div class="rating-dimension">
                                                    <div class="rating-quantity">
                                                        <div><?php echo display('3') ?> <span class="glyphicon glyphicon-star"></span></div>
                                                    </div>
                                                    <div class="rating-percent">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 60%">
                                                                <span class="sr-only"><?php echo display('60_percent_complete');  ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-rating"><?php echo $this->Products_model->get_total_five_start_rating($product_id, 3); ?></div>
                                                </div><!-- /.End of rating dimension -->
                                                <div class="rating-dimension">
                                                    <div class="rating-quantity">
                                                        <div> <?php echo display('2') ?> <span class="glyphicon glyphicon-star"></span></div>
                                                    </div>
                                                    <div class="rating-percent">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 40%">
                                                                <span class="sr-only"><?php echo display('40_percent_complete');  ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-rating">
                                                        <?php
                                                        echo $this->Products_model->get_total_five_start_rating($product_id, 2);
                                                        ?>
                                                    </div>
                                                </div><!-- /.End of rating dimension -->
                                                <div class="rating-dimension">
                                                    <div class="rating-quantity">
                                                        <div><?php echo display('1') ?> <span class="glyphicon glyphicon-star"></span></div>
                                                    </div>
                                                    <div class="rating-percent">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 20%">
                                                                <span class="sr-only"><?php echo display('20_percent_complete');  ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-rating"><?php echo $this->Products_model->get_total_five_start_rating($product_id, 1); ?></div>
                                                </div><!-- /.End of rating dimension -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="review-block-wrapper">
                                    <?php
                                    if($review_list){
                                    foreach($review_list as $review_item) { ?>
                                    <div class="review-block">
                                        <div class="review-block-rate">
                                            <button type="button" class="btn btn-primary btn-xs" aria-label="Left Align">
                                               <?php echo $review_item->rate;  ?> <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                        <div class="review-block-description"><?php echo $review_item->comments  ?></div>
                                        <div class="review-meta-row align-items-center justify-content-between">
                                            <div>
                                                <span class="review-block-name"><?php echo $review_item->customer_name;  ?></span>
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                <span class="review-block-date"><?php
                                                   $comment_date= date_create($review_item->date_time);
                                                    echo date_format($comment_date, 'Y-m-d H:i');
                                                    ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php  } } ?>
                                </div>
                            </div>
                            <div class="col-sm-5 rightSidebar">
                                <div class="review-content">
                                    <div class="review-product-info">
                                        <h4 class="review-product-name"><?php echo $product_name;  ?></h4>
                                        <div class="review-product-brand"><?php echo $category_name;  ?></div>
                                    </div>

                                    <form class="review-form">
                                        <div class="rating-content">
                                            <label for="input-1" class="control-label"><?php echo display('rate_it') ?>:
                                                *</label>
                                            <input type="text" id="input-1" name="rating" class="rating rating-loading"
                                                   data-min="0" data-max="5" data-step="1" value="2">
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo display('review') ?> *</label>
                                            <textarea class="form-control"  name="review_msg"
                                                      placeholder="<?php echo display('review') ?>" rows="5"
                                                      id="review_msg"></textarea>
                                        </div>
                                        <input type="button" id="review_submit" class="btn btn-warning review color4
                                    color36"
                                               value="<?php echo display('submit') ?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab2default">
                    <div class="container">
                        <div class="product-description">
                            <?php if ($description) {
                                echo $description;
                            } ?>
                        </div>
                        <!-- /.End of product description -->

                    </div>
                </div>
                <div class="tab-pane fade" id="tab3default">
                    <div class="container">
                        <?php if ($specification !='') {
                            echo $specification;
                        } ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- Similar Products Area -->
        <?php  if (!empty($select_details_adds)) {
            foreach ($select_details_adds as $ads):
                if (3 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                    ?>
                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if (3 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-6">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $ads->adv_code2; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if (3 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code2; ?>
                                </div>
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code3; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php
            endforeach;
        } ?>
        <section class="product_area mb-40"> 
            <div class="container">
                <div class="product_inner">
                    <div class="sec_heading">
                        <h2 class="sec_title"><?php echo display('related_products') ?></h2>
                    </div>
                    <div class="owl-carousel product_slider product_col5_slider slider-nav">
                        <?php
                        if ($related_product) {
                        foreach ($related_product as $product) {
                        ?>
                        <div class="item boxed">
                            <div class="item_inner">
                                <a href="<?php echo base_url('product_details/' .remove_space($product->product_name).'/'. $product->product_id) ?>" class="item_title2">
                                <div class="item_image">
                                    <img src="<?php echo base_url().$product->image_thumb ?>" alt="product-image">
                                </div>
                                </a>
                                <div class="item_info text-center">
                                    <a href="<?php echo base_url('category/p/' .remove_space($product->category_name).'/'. $product->category_id) ?>">
                                    <p class="category-name"><?php echo $product->category_name ?></p>
                                    </a>
                                    <a href="<?php echo base_url('product_details/' .remove_space($product->product_name).'/'. $product->product_id) ?>" class="item_title2"><?php echo $product->product_name ?></a>
                                    <div class="rating_stars">
                                        <div class="rating-wrap m-0-auto">
                                            <?php
                                            $result = $this->db->select('sum(rate) as rates')
                                                ->from('product_review')
                                                ->where('product_id', $product->product_id)
                                                ->get()
                                                ->row();

                                            $rater = $this->db->select('rate')
                                                ->from('product_review')
                                                ->where('product_id', $product->product_id)
                                                ->get()
                                                ->num_rows();
                                            if ($result->rates != null) {
                                                $total_rate = $result->rates / $rater;
                                                if (gettype($total_rate) == 'integer') {
                                                    for ($t = 1; $t <= $total_rate; $t++) {
                                                        echo "<i class=\"fa fa-star\"></i>";
                                                    }
                                                    for ($tt = $total_rate; $tt < 5; $tt++) {
                                                        echo "<i class=\"fa fa-star-o\"></i>";
                                                    }
                                                } elseif (gettype($total_rate) == 'double') {
                                                    $pieces = explode(".", $total_rate);
                                                    for ($q = 1; $q <= $pieces[0]; $q++) {
                                                        echo "<i class=\"fa fa-star\"></i>";
                                                        if ($pieces[0] == $q) {
                                                            echo "<i class=\"fa fa-star-half-o\"></i>";
                                                            for ($qq = $pieces[0]; $qq < 4; $qq++) {
                                                                echo "<i class=\"fa fa-star-o\"></i>";
                                                            }
                                                        }
                                                    }

                                                } else {
                                                    for ($w = 0; $w <= 4; $w++) {
                                                        echo "<i class=\"fa fa-star-o\"></i>";
                                                    }
                                                }
                                            } else {
                                                for ($o = 0; $o <= 4; $o++) {
                                                    echo "<i class=\"fa fa-star-o\"></i>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    $default_currency_id = $this->session->userdata('currency_id');
                                    $currency_new_id = $this->session->userdata('currency_new_id');

                                    if (empty($currency_new_id)) {
                                        $result = $cur_info = $this->db->select('*')
                                            ->from('currency_info')
                                            ->where('default_status', '1')
                                            ->get()
                                            ->row();
                                        $currency_new_id = $result->currency_id;
                                    }

                                    if (!empty($currency_new_id)) {
                                        $cur_info = $this->db->select('*')
                                            ->from('currency_info')
                                            ->where('currency_id', $currency_new_id)
                                            ->get()
                                            ->row();

                                        $target_con_rate = $cur_info->convertion_rate;
                                        $position1 = $cur_info->currency_position;
                                        $currency1 = $cur_info->currency_icon;
                                    }
                                    ?>
                                    <?php
                                    $currency_new_id = $this->session->userdata('currency_new_id');

                                    if (empty($currency_new_id)) {
                                        $result = $cur_info = $this->db->select('*')
                                            ->from('currency_info')
                                            ->where('default_status', '1')
                                            ->get()
                                            ->row();
                                        $currency_new_id = $result->currency_id;
                                    }

                                    if (!empty($currency_new_id)) {
                                        $cur_info = $this->db->select('*')
                                            ->from('currency_info')
                                            ->where('currency_id', $currency_new_id)
                                            ->get()
                                            ->row();

                                        $target_con_rate = $cur_info->convertion_rate;
                                        $position1 = $cur_info->currency_position;
                                        $currency1 = $cur_info->currency_icon;
                                    }
                                    ?>

                                    <?php if ($product->onsale == 1 && !empty($product->onsale_price)) { ?>
                                        <span class="price">
                                                <span class="price-amount">
                                                    <ins><span class="amount">
                                                    <?php

                                                    if ($target_con_rate > 1) {
                                                        $price = $product->onsale_price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    if ($target_con_rate <= 1) {
                                                        $price = $product->onsale_price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    ?>
                                                    </span></ins>
                                                    <del><span class="amount">
                                                    <?php
                                                    if ($target_con_rate > 1) {
                                                        $price = $product->price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }

                                                    if ($target_con_rate <= 1) {
                                                        $price = $product->price * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    ?>
                                                    </span></del>
                                                    <span class="amount"> </span>
                                                </span>
                                            </span><!-- /.Price -->
                                        <?php
                                    } else {
                                        ?>
                                        <span class="price">
                                                <span class="price-amount">
                                                    <ins><span class="amount">
                                                       <?php
                                                       if ($target_con_rate > 1) {
                                                           $price = $product->price * $target_con_rate;
                                                           echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                       }

                                                       if ($target_con_rate <= 1) {
                                                           $price = $product->price * $target_con_rate;
                                                           echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                       }
                                                       ?>
                                                    </span></ins>
                                                    <span class="amount"> </span>
                                                </span>
                                            </span><!-- /.Price -->
                                        <?php
                                    }
                                    ?>
                                    <div class="hover_content">
                                        <ul class="nav">
                                            <li><a href="#" class="wishlist"><i class="fa fa-heart-o"></i></a></li>
                                            <li><a href="#" onclick="add_to_cart(<?php echo $product->product_id.', \''.remove_space($product->product_name).'\','; echo ($product->default_variant)? '\''.$product->default_variant.'\'':'\'nai\'' ;  ?>)"><i class="fa fa-shopping-bag"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <?php
                        }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Similar Products Area -->

        <?php  if (!empty($select_details_adds)) {
            foreach ($select_details_adds as $ads):
                if (4 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                    ?>
                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if (4 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-6">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $ads->adv_code2; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if (4 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                    <div class="my-10">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code; ?>
                                </div>
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code2; ?>
                                </div>
                                <div class="col-xs-4">
                                    <?php echo $ads->adv_code3; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php
            endforeach;
        } ?>
    </div>

<script>
    //Check variant stock
    $('body').on('change', '#select_size1', function () {
        var variant_id = $(this).val();
        var product_id = '<?php echo $product_id;?>';

        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Product/check_variant_wise_stock')?>',
            data: {variant_id: variant_id, product_id: product_id},
            success: function (data) {
                if (data == '2') {
                    Swal({
                        type: 'warning',
                        title: '<?php echo display("variant_not_available")?>'
                    });
                    $(".product_size").load(location.href + " .product_size>*", "");
                    return false;
                } else {
                    return true;
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display("request_failed")?>'
                })
            }
        });
    });

</script>


<!-- Rating or review product -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.star_part a').click(function () {
            $('.star_part a').removeClass("active");
            $(this).addClass("active");
        });


        //Add review
        $('body').on('click', '.review', function () {

            var product_id = '<?php echo $product_id?>';
            var review_msg = $('#review_msg').val();
            var customer_id = '<?php echo $this->session->userdata('customer_id')?>';
            // var rate = $('.star_part a.active').attr('name');
            var rate        = $('#input-1').val();
            //Review msg check
            if (review_msg == 0) {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('write_your_comment')?>'
                });
                return false;
            }

            //Customer id check
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
                url: '<?php echo base_url('website/Home/add_review')?>',
                data: {product_id: product_id, customer_id: customer_id, review_msg: review_msg, rate: rate},
                success: function (data) {
                    if (data == '1') {
                        $('#review_msg').val('');
                        Swal({
                            type: 'success',
                            title: '<?php echo display('your_review_added')?>'
                        });
                        window.load();
                    } else if (data == '2') {
                        $('#review_msg').val('');
                        Swal({
                            type: 'warning',
                            title: '<?php echo display('already_reviewed')?>'
                        });
                        window.load();
                    } else if (data == '3') {
                        $('#review_msg').val('');
                        Swal({
                            type: 'warning',
                            title: '<?php echo display('please_login_first')?>'
                        });
                        window.load();
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
    });
</script>