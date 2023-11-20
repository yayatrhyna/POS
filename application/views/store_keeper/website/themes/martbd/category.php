<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- /.End of header -->
<?php
$CI =& get_instance();
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
?>
<div class="page-breadcrumbs">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><?php echo display('home') ?></a></li>
            <?php if (empty($after_search)) { ?>
                <li><a href="<?php echo base_url('category/p/'.remove_space($category_name).'/' . $category_id) ?>"><?php echo
                        $category_name ?></a></li>
            <?php } ?>
        </ol>
    </div>
</div>
<!-- /.End of page breadcrumbs -->

<div class="category-content hidden-xs">
    <div class="container">
        <div class="row">
            <div class="<?php if (!empty($after_search)) { ?> '' <?php } else { ?>col-sm-3 <?php } ?> leftSidebar hidden-xs">
                <?php if (!empty($category_id)): ?>
                    <div class="sidebar-widget">
                        <?php
                        $sub_category = $this->Homes->get_sub_category($category_id);
                        if (!empty($sub_category)) {
                            ?>
                            <div class="accessories">
                                <h3 class="sidebar-title"> <?php echo $category_name ?></h3>
                                <ul class="subcategories">
                                    <?php
                                    if ($sub_category) {
                                        $i = 1;
                                        foreach ($sub_category as $cat) {
                                            if ($i == 11) break;
                                            $no_of_pro = $this->Categories->select_total_sub_cat_no_of_pro($cat['category_id']);
                                            ?>
                                            <li>
                                                <a href="<?php echo base_url('category/p/'.remove_space($cat['category_name']).'/' . $cat['category_id']) ?>">
                                                    <i class="fa fa-angle-right"></i>
                                                    <span class="name"><?php echo $cat['category_name'] ?></span>
                                                    <span class="total">(<?php echo $no_of_pro; ?>)</span>
                                                </a>
                                            </li>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <!-- /.End of accessories -->
                        <div class="sidebar-widget-title">
                            <?php
                            if (!empty($max_value)) {
                                ?>
                                <div class="price-Pips">
                                    <form action="" method="post" id="priceForm">
                                        <h3 class="sidebar-title"><?php echo display('by_price') ?></h3>
                                        <input type="text" class="price-range" value="price-range" name="price-range"/>
                                    </form>
                                </div>
                                <?php
                            }
                            if (!empty($variant_list)) {
                                ?>
                                <div class="product-size">
                                    <h3 class="sidebar-title"><?php echo display('by_variant') ?>:</h3>
                                    <?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $size = $this->input->get('size');

                                    if ($variant_list) {
                                        $i = 1;
                                        foreach ($variant_list as $variant) {
                                            ?>
                                            <input type="radio" class="size1" name="size" id="<?php echo $i ?>"
                                                   value="<?php
                                                   if ($params) {
                                                       if ($size) {
                                                           $new_param = str_replace("size=" . $size, "size=" . $variant['variant_id'], $params);
                                                           echo $fullURL = $currentURL . '?' . $new_param;
                                                       } else {
                                                           echo $fullURL = $currentURL . '?' . $params . '&size=' . $variant['variant_id'];
                                                       }
                                                   } else {
                                                       echo $fullURL = $currentURL . '?size=' . $variant['variant_id'];
                                                   }
                                                   ?>" <?php if ($size == $variant['variant_id']) {
                                                echo "checked";
                                            } ?>/>
                                            <label for="<?php echo $i ?>"><span
                                                        class="size1"><?php echo $variant['variant_name'] ?></span></label>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?>
                                </div>
                                <!--  /.End of product color selector -->
                                <?php
                            } ?>
                            <!-- /.End of product size -->


                            <?php
                            $brand_info = $this->Categories->select_sub_cat_brand_info($category_id);
                            if ($brand_info) {
                                ?>
                                <div class="filter-nav brand-cat-widget">
                                    <h3 class="sidebar-title"><?php echo display('by_brand') ?>:</h3>
                                    <div class="search_layered_nav sidebar-search">
                                        <input class="form-control brand_search" type="text"
                                               placeholder="<?php echo display('brand_search') ?>">
                                    </div>
                                    <div class="brand-cat-scroll">
                                        <?php
                                        $i = 1;
                                        $query_string = '';
                                        $query_string = $this->input->server('QUERY_STRING');
                                        if ($query_string) {
                                            $query_string = '?' . $this->input->server('QUERY_STRING');
                                        } else {
                                            $query_string = '';
                                        }
                                        $brand_url_ids = $this->uri->segment('3');
                                        if ($brand_url_ids) {
                                            $all_brand = (explode("--", $brand_url_ids));
                                            $lastElementKey = count($all_brand);
                                        } else {
                                            $lastElementKey = 0;
                                        }
                                        foreach ($brand_info as $brand_in) {
                                            if ($brand_in['brand_id']) {
                                                ?>
                                                <div class="checkbox checkbox-success">
                                                    <input id="brand<?php echo $i ?>" type="checkbox"
                                                           class="brand_class" name="brand" value="<?php
                                                    $target_id = $brand_in['brand_id'];
                                                    if (strpos($brand_url_ids, $target_id) !== false) {
                                                        if ($lastElementKey == 1) {
                                                            $output = preg_replace('/' . $target_id . '/', '', $brand_url_ids);
                                                            echo base_url('category') . '/' . $category_id . $query_string;
                                                        } else {
                                                            if (strpos($brand_url_ids, $target_id . '--') !== false) {
                                                                $output = preg_replace('/' . $target_id . '--/', '', $brand_url_ids);
                                                            } else {
                                                                $output = preg_replace('/--' . $target_id . '/', '', $brand_url_ids);
                                                            }
                                                            echo base_url('category') . '/' . $category_id . '/' . $output . $query_string;
                                                        }
                                                    } else {
                                                        if ($lastElementKey == 0) {
                                                            echo base_url('category') . '/' . $category_id . '/' . $brand_url_ids . $target_id . $query_string;
                                                        } else {
                                                            echo base_url('category') . '/' . $category_id . '/' . $brand_url_ids . '--' . $target_id . $query_string;
                                                        }
                                                    }
                                                    ?>" <?php
                                                    if (strpos($brand_url_ids, $target_id) !== false) {
                                                        echo 'checked';
                                                    }
                                                    ?>>
                                                    <label for="brand<?php echo $i ?>"><?php echo $brand_in['brand_name'] ?> </label>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <!-- /.End of filter nav -->
                                <?php
                            }
                            ?>
                            <!-- /.End of filter nav -->
                            <div class="row">
                                <div class="banner-content">
                                    <div class="-container">
                                        <?php
                                        if ($select_category_adds) {
                                            foreach ($select_category_adds as $adds) {
                                                if ($adds->adv_position == 3) {
                                                    echo $adds->adv_code;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-review">
                                <h3 class="sidebar-title"><?php echo display('rating') ?>:</h3>
                                <div class="checkbox checkbox-success">
                                    <input id="rating1" type="checkbox" class="check_value" value="<?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $rate = $this->input->get('rate');
                                    if ($params) {
                                        if ($rate) {
                                            $new_param = str_replace("rate=" . $rate, "rate=4-5", $params);
                                            echo $fullURL = $currentURL . '?' . $new_param;
                                        } else {
                                            echo $fullURL = $currentURL . '?' . $params . '&rate=4-5';
                                        }
                                    } else {
                                        echo $fullURL = $currentURL . '?rate=4-5';
                                    }
                                    ?>" <?php if ($this->input->get('rate') == '4-5') {
                                        echo("checked");
                                    } ?>>
                                    <label for="rating1">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(4-5)</span>
                                    </a>
                                </span>
                                    </label>
                                </div>
                                <div class="checkbox checkbox-success">
                                    <input id="rating2" type="checkbox" class="check_value" value="<?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $rate = $this->input->get('rate');
                                    if ($params) {
                                        if ($rate) {
                                            $new_param = str_replace("rate=" . $rate, "rate=3-5", $params);
                                            echo $fullURL = $currentURL . '?' . $new_param;
                                        } else {
                                            echo $fullURL = $currentURL . '?' . $params . '&rate=3-5';
                                        }
                                    } else {
                                        echo $fullURL = $currentURL . '?rate=3-5';
                                    }
                                    ?>" <?php if ($this->input->get('rate') == '3-5') {
                                        echo("checked");
                                    } ?>>
                                    <label for="rating2">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(3-5)</span>
                                    </a>
                                </span>
                                    </label>
                                </div>
                                <div class="checkbox checkbox-success">
                                    <input id="rating3" type="checkbox" class="check_value" value="<?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $rate = $this->input->get('rate');
                                    if ($params) {
                                        if ($rate) {
                                            $new_param = str_replace("rate=" . $rate, "rate=2-5", $params);
                                            echo $fullURL = $currentURL . '?' . $new_param;
                                        } else {
                                            echo $fullURL = $currentURL . '?' . $params . '&rate=2-5';
                                        }
                                    } else {
                                        echo $fullURL = $currentURL . '?rate=2-5';
                                    }
                                    ?>" <?php if ($this->input->get('rate') == '2-5') {
                                        echo("checked");
                                    } ?>>
                                    <label for="rating3">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(2-5)</span>
                                    </a>
                                </span>
                                    </label>
                                </div>
                                <div class="checkbox checkbox-success">
                                    <input id="rating4" type="checkbox" class="check_value" value="<?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $rate = $this->input->get('rate');
                                    if ($params) {
                                        if ($rate) {
                                            $new_param = str_replace("rate=" . $rate, "rate=1-5", $params);
                                            echo $fullURL = $currentURL . '?' . $new_param;
                                        } else {
                                            echo $fullURL = $currentURL . '?' . $params . '&rate=1-5';
                                        }
                                    } else {
                                        echo $fullURL = $currentURL . '?rate=1-5';
                                    }
                                    ?>" <?php if ($this->input->get('rate') == '1-5') {
                                        echo("checked");
                                    } ?>>
                                    <label for="rating4">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(1-5)</span>
                                    </a>
                                </span>
                                    </label>
                                </div>
                            </div>

                            <!--  /.End of review rating -->
                        </div>

                    </div>
                <?php endif; ?>
            </div>
            <div class=" <?php if (!empty($after_search)) { ?> col-sm-12 after-search-product-block<?php } else { ?>col-sm-9 <?php } ?> content">


                <?php  if (!empty($select_category_adds)) {
                    foreach ($select_category_adds as $ads):
                        if (1 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                            ?>
                            <div class="details-adds">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $ads->adv_code; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (1 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                            <div class="details-adds">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php echo $ads->adv_code; ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $ads->adv_code2; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (1 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                            <div class="details-adds">
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
                        <?php } ?>
                    <?php
                    endforeach;
                } ?>
                <div class="">
                    <div class="filter-row align-items-center justify-content-between">
                        <div class="filter-title">
                            <?php if (!empty($after_search)) { ?>

                                <h3><?php echo $total . " ";
                                    echo display('search_items');
                                    echo
                                    " \" $keyword \" ";
                                    ?> </h3>
                            <?php } else { ?>
                                <h1><?php echo $category_name; ?></h1>
                                <span>- <?php echo $total; ?> <?php echo display('items') ?></span>
                            <?php } ?>
                        </div>
                        <a class="btn btn-warning color3 color36" href="<?php echo base_url()?>"><i class="fa fa-hand-o-left" aria-hidden="true"></i>
                             <?php echo display('back_to_home') ?></a>
                    </div>

                    <!-- /.End of filter summary -->

                    <div class="row mr-0">
                        <?php

                        if ($category_product) {
                            foreach ($category_product as $product) {
                                ?>

                                <div class="col-xs-6 col-sm-4 col-md-3 pa-0">
                                    <div class="product-box">
                                        <div class="imagebox">
                                            <span class="product-cat"><a href="<?php echo base_url('category/p/'.remove_space($product->category_name).'/' .
                                                    $product->category_id) ?>"><?php echo $product->category_name;
                                                    ?></a></span>
                                            <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                                . $product->product_id) ?>">
                                                <h3 class="product-name"><?php echo $product->product_name ?></h3>
                                                <div class="product-thumb">
                                                    <img src="<?php echo base_url().$product->image_thumb ?>"
                                                         data-src="<?php echo base_url().$product->image_thumb ?>" alt="">
                                                </div>
                                            </a>

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
                                            <div class="price-cart">
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
                                                <div class="rating_stars">
                                                    <div class="rating-wrap">
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
                                                    <div class="total-rating">(<?php echo $rater ?>)</div>
                                                </div><!-- Rating -->
                                            </div><!-- /.price-add-to-cart -->
                                            <div class="box-bottom">
                                                <div class="btn-add-cart">
                                                    <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                                        . $product->product_id) ?>">
                                                        <img src="<?php echo base_url() . 'application/views/website/themes/' .
                                                            $theme . '/assets/img/add-cart.png' ?>"
                                                             alt="cart icon"><?php echo display
                                                        ('add_to_cart'); ?>
                                                    </a>
                                                </div>
                                                <div class="compare-wishlist">
                                                    <a href="#" class="wishlist" title="">
                                                        <img src="<?php echo base_url()
                                                            . 'application/views/website/themes/' .
                                                            $theme . '/assets/img/wishlist.png' ?>"
                                                             alt=""><?php echo display('wishlist'); ?>
                                                    </a>
                                                </div>
                                            </div><!-- /.box-bottom -->
                                        </div>
                                    </div>
                                    <!-- /.End of product box -->
                                </div>

                                <?php
                            }
                        }
                        ?>

                        <div class="col-xs-12 col-sm-12">
                            <div class="pagination-widget">
                                <?php echo $links ?>
                            </div>
                        </div>
                    </div>
                </div>
<!--                advertise 2 start =========-->
                <?php  if (!empty($select_category_adds)) {
                    foreach ($select_category_adds as $ads):
                        if (2 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                            ?>
                            <div class="details-adds">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $ads->adv_code; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (2 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                            <div class="details-adds">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php echo $ads->adv_code; ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $ads->adv_code2; ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else if (2 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && !empty($ads->adv_code3)) { ?>

                            <div class="details-adds">
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
                        <?php } ?>
                    <?php
                    endforeach;
                } ?>
<!--                advertise 2 end =========-->
            </div>
        </div>
    </div>
</div>
<!-- /.End of category content -->
<div class="category-mobile">
    <div class="mobile-filter-nav align-items-center justify-content-between">

        <div class="col-xs-12 filter text-center">
            <button type="button" class="" data-toggle="modal" data-target=".filter-modal"><i class="fa fa-filter"
                                                                                              aria-hidden="true"></i>Filter
            </button>
        </div>
    </div>
    <div class="modal filter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <div class="sidebar-widget-title">
                    <?php
                    if ($max_value) {
                        ?>
                        <div class="price-Pips">
                            <form action="" method="post" id="priceForm">
                                <h3 class="sidebar-title"><?php echo display('by_price') ?></h3>
                                <input type="text" class="price-range" value="price-range" name="price-range"/>
                            </form>
                        </div>
                        <?php
                    }
                    if ($variant_list) {
                        ?>
                        <div class="product-size">
                            <h3 class="sidebar-title"><?php echo display('by_variant') ?>:</h3>
                            <?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $size = $this->input->get('size');

                            if ($variant_list) {
                                $i = 1;
                                foreach ($variant_list as $variant) {
                                    ?>
                                    <input type="radio" class="size1" name="size" id="<?php echo $i ?>" value="<?php
                                    if ($params) {
                                        if ($size) {
                                            $new_param = str_replace("size=" . $size, "size=" . $variant['variant_id'], $params);
                                            echo $fullURL = $currentURL . '?' . $new_param;
                                        } else {
                                            echo $fullURL = $currentURL . '?' . $params . '&size=' . $variant['variant_id'];
                                        }
                                    } else {
                                        echo $fullURL = $currentURL . '?size=' . $variant['variant_id'];
                                    }
                                    ?>" <?php if ($size == $variant['variant_id']) {
                                        echo "checked";
                                    } ?>/>
                                    <label for="<?php echo $i ?>"><span
                                                class="size1"><?php echo $variant['variant_name'] ?></span></label>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <!--  /.End of product color selector -->
                        <?php
                    }
                    ?>

                    <?php
                    $brand_info = $this->Categories->select_sub_cat_brand_info($category_id);
                    if ($brand_info) {
                        ?>
                        <div class="filter-nav brand-cat-widget">
                            <h3 class="sidebar-title"><?php echo display('by_brand') ?>:</h3>
                            <div class="search_layered_nav sidebar-search">
                                <input class="form-control brand_search" type="text"
                                       placeholder="<?php echo display('brand_search') ?>">
                            </div>
                            <div class="brand-cat-scroll">
                                <?php
                                $i = 1;
                                $query_string = '';
                                $query_string = $this->input->server('QUERY_STRING');
                                if ($query_string) {
                                    $query_string = '?' . $this->input->server('QUERY_STRING');
                                } else {
                                    $query_string = '';
                                }
                                $brand_url_ids = $this->uri->segment('3');
                                if ($brand_url_ids) {
                                    $all_brand = (explode("--", $brand_url_ids));
                                    $lastElementKey = count($all_brand);
                                } else {
                                    $lastElementKey = 0;
                                }
                                foreach ($brand_info as $brand_in) {
                                    if ($brand_in['brand_id']) {
                                        ?>
                                        <div class="checkbox checkbox-success">
                                            <input id="brand<?php echo $i ?>" type="checkbox" class="brand_class"
                                                   name="brand" value="<?php
                                            $target_id = $brand_in['brand_id'];
                                            if (strpos($brand_url_ids, $target_id) !== false) {
                                                if ($lastElementKey == 1) {
                                                    $output = preg_replace('/' . $target_id . '/', '', $brand_url_ids);
                                                    echo base_url('category') . '/' . $category_id . $query_string;
                                                } else {
                                                    if (strpos($brand_url_ids, $target_id . '--') !== false) {
                                                        $output = preg_replace('/' . $target_id . '--/', '', $brand_url_ids);
                                                    } else {
                                                        $output = preg_replace('/--' . $target_id . '/', '', $brand_url_ids);
                                                    }
                                                    echo base_url('category') . '/' . $category_id . '/' . $output . $query_string;
                                                }
                                            } else {
                                                if ($lastElementKey == 0) {
                                                    echo base_url('category') . '/' . $category_id . '/' . $brand_url_ids . $target_id . $query_string;
                                                } else {
                                                    echo base_url('category') . '/' . $category_id . '/' . $brand_url_ids . '--' . $target_id . $query_string;
                                                }
                                            }
                                            ?>" <?php
                                            if (strpos($brand_url_ids, $target_id) !== false) {
                                                echo 'checked';
                                            }
                                            ?>>

                                            <!-- //echo $this->Categories->total_brand_pro($brand_in['brand_id'],$category_id); -->
                                            <label for="brand<?php echo $i ?>"><?php echo $brand_in['brand_name'] ?> </label>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <!-- /.End of filter nav -->
                        <?php
                    }
                    ?>

                    <!-- /.End of filter nav -->
                    <div class="sidebar-review">
                        <h3 class="sidebar-title"><?php echo display('rating') ?>:</h3>
                        <div class="checkbox checkbox-success">
                            <input id="rating1" type="checkbox" class="check_value" value="<?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $rate = $this->input->get('rate');
                            if ($params) {
                                if ($rate) {
                                    $new_param = str_replace("rate=" . $rate, "rate=4-5", $params);
                                    echo $fullURL = $currentURL . '?' . $new_param;
                                } else {
                                    echo $fullURL = $currentURL . '?' . $params . '&rate=4-5';
                                }
                            } else {
                                echo $fullURL = $currentURL . '?rate=4-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '4-5') {
                                echo("checked");
                            } ?>>
                            <label for="rating1">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(4-5)</span>
                                    </a>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox checkbox-success">
                            <input id="rating2" type="checkbox" class="check_value" value="<?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $rate = $this->input->get('rate');
                            if ($params) {
                                if ($rate) {
                                    $new_param = str_replace("rate=" . $rate, "rate=3-5", $params);
                                    echo $fullURL = $currentURL . '?' . $new_param;
                                } else {
                                    echo $fullURL = $currentURL . '?' . $params . '&rate=3-5';
                                }
                            } else {
                                echo $fullURL = $currentURL . '?rate=3-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '3-5') {
                                echo("checked");
                            } ?>>
                            <label for="rating2">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(3-5)</span>
                                    </a>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox checkbox-success">
                            <input id="rating3" type="checkbox" class="check_value" value="<?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $rate = $this->input->get('rate');
                            if ($params) {
                                if ($rate) {
                                    $new_param = str_replace("rate=" . $rate, "rate=2-5", $params);
                                    echo $fullURL = $currentURL . '?' . $new_param;
                                } else {
                                    echo $fullURL = $currentURL . '?' . $params . '&rate=2-5';
                                }
                            } else {
                                echo $fullURL = $currentURL . '?rate=2-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '2-5') {
                                echo("checked");
                            } ?>>
                            <label for="rating3">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(2-5)</span>
                                    </a>
                                </span>
                            </label>
                        </div>
                        <div class="checkbox checkbox-success">
                            <input id="rating4" type="checkbox" class="check_value" value="<?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $rate = $this->input->get('rate');
                            if ($params) {
                                if ($rate) {
                                    $new_param = str_replace("rate=" . $rate, "rate=1-5", $params);
                                    echo $fullURL = $currentURL . '?' . $new_param;
                                } else {
                                    echo $fullURL = $currentURL . '?' . $params . '&rate=1-5';
                                }
                            } else {
                                echo $fullURL = $currentURL . '?rate=1-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '1-5') {
                                echo("checked");
                            } ?>>
                            <label for="rating4">
                                <span class="product-rating">
                                    <span class="star-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                        <i class="fa fa-star-o"></i>
                                    </span>
                                    <a href="javascript:void(0)" class="review-link">
                                        <span class="count">(1-5)</span>
                                    </a>
                                </span>
                            </label>
                        </div>
                    </div>
                    <!--  /.End of review rating -->

                </div>
            </div>
        </div>
    </div>
    <div class="page-title">
        <?php if (!empty($after_search)) { ?>
            <h5 class="sub-title"> <?php echo $total . " ";
                echo display('search_items');
                echo
                " \" $keyword \" ";
                ?> </h5>
        <?php } else { ?>
            <h2 class="title"><?php echo $category_name; ?></h2>
            <span>- <?php echo $total; ?> <?php echo display('items') ?></span>
        <?php } ?>
    </div>
    <ul class="category">
        <?php

        if ($category_product) {
            foreach ($category_product as $product) {
                ?>
                <li class="col-xs-4">
                    <div class="product-box">
                        <div class="imagebox">
                                            <span class="product-cat"><a href="<?php echo base_url('category/p/'.remove_space($product->category_name).'/' .
                                                    $product->category_id) ?>"><?php echo $product->category_name;
                                                    ?></a></span>
                            <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                . $product->product_id) ?>">
                                <h3 class="product-name"><?php echo $product->product_name ?></h3>
                                <div class="product-thumb">
                                    <img src="<?php echo base_url().$product->image_thumb ?>"
                                         data-src="<?php echo base_url().$product->image_thumb ?>" alt="">
                                </div>
                            </a>

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
                            <div class="price-cart">
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
                                <div class="rating_stars">
                                    <div class="rating-wrap">
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
                                    <div class="total-rating">(<?php echo $rater ?>)</div>
                                </div><!-- Rating -->
                            </div><!-- /.price-add-to-cart -->
                            <div class="box-bottom">
                                <div class="btn-add-cart">
                                    <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                        . $product->product_id) ?>">
                                        <img src="<?php echo base_url() . 'application/views/website/themes/' .
                                            $theme . '/assets/img/add-cart.png' ?>" alt="cart icon"><?php echo display
                                        ('add_to_cart'); ?>
                                    </a>
                                </div>
                                <div class="compare-wishlist">
                                    <a href="#" class="wishlist" title="">
                                        <img src="<?php echo base_url()
                                            . 'application/views/website/themes/' .
                                            $theme . '/assets/img/wishlist.png' ?>"
                                             alt=""><?php echo display('wishlist'); ?>
                                    </a>
                                </div>
                            </div><!-- /.box-bottom -->
                        </div>
                    </div>
                    <!-- /.End of product box -->
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <div class="pagination-widget">
        <?php echo $links; ?>
        <!-- /.End of pagination -->
    </div>
</div>
<script>
    $(document).ready(function () {
        /*------------------------------------
        Price range slide
        -------------------------------------- */
        $(".price-range").ionRangeSlider({
            type: "double",
            grid: true,
            min: <?php echo $min_value?>,
            max: <?php echo $max_value?>,
            from: <?php if ($from_price == 0) {
                echo 'null';
            } else {
                echo $from_price;
            }?>,
            to: <?php if ($to_price == 0) {
                echo 'null';
            } else {
                echo $to_price;
            }?>,
            prefix: "<?php echo $default_currency_icon;?> ",
            onChange: function (data) {
                //field "search";
                var pattern = /[?]/;
                var URL = location.search;
                var fullURL = document.URL;

                if (pattern.test(URL)) {
                    var $_GET = {};
                    if (document.location.toString().indexOf('?') !== -1) {
                        var query = document.location
                            .toString()
                            // get the query string
                            .replace(/^.*?\?/, '')
                            // and remove any existing hash string (thanks, @vrijdenker)
                            .replace(/#.*$/, '')
                            .split('&');

                        for (var i = 0, l = query.length; i < l; i++) {
                            var aux = decodeURIComponent(query[i]).split('=');
                            $_GET[aux[0]] = aux[1];
                        }
                    }

                    //Get from value by get method
                    if ($_GET['price']) {
                        var fullURL = window.location.href.split('?')[0];
                        var url = window.location.search;
                        url = url.replace("price=" + $_GET['price'], 'price=' + data.from + '-' + data.to);
                        window.location.href = fullURL + url;
                    } else {
                        var url = window.location.search;
                        window.location.href = url + '&price=' + data.from + '-' + data.to;
                    }

                } else {
                    var fullURL = window.location.href.split('?')[0];
                    window.location.href = fullURL.split('?')[0] + '?price=' + data.from + '-' + data.to
                }
            }
        });
        /*------------------------------------
        Product search by size
        -------------------------------------- */
        $('body').on('click', '.size1', function () {
            var size_location = $(this).val();
            window.location.href = size_location;
        });

        /*------------------------------------
        Sort by rating
        -------------------------------------- */
        $('.check_value').on('click', function () {
            var rating_location = $(this).val();
            window.location.href = rating_location;
        });
        /*------------------------------------
        Brand
        -------------------------------------- */
        $('body').on('click', '.brand_class', function () {
            var brand_location = $(this).val();
            window.location.href = brand_location;
        });
        /*------------------------------------
        BRAND INFO SEARCH
        -------------------------------------- */
        //Brand Search
        $('body').on('keyup', '.brand_search', function () {
            var search_key = $(this).val();
            var category_id = '<?php echo $category_id?>';
            var query_string = '<?php
                $query_string = '';
                $query_string = $this->input->server('QUERY_STRING');
                if ($query_string) {
                    echo $query_string = '?' . $this->input->server('QUERY_STRING');
                } else {
                    echo $query_string = '';
                }
                ?>';
            var brand_url_ids = '<?php echo $this->uri->segment('3')?>';

            $.ajax({
                type: "post",
                async: true,
                url: '<?php echo base_url('website/Category/brand_search')?>',
                data: {
                    search_key: search_key,
                    category_id: category_id,
                    query_string: query_string,
                    brand_url_ids: brand_url_ids
                },
                success: function (data) {
                     console.log(data);
                    $('.brand-cat-scroll').html(data);
                },
                error: function (e) {
                    console.log(e);
                    swal("<?php echo display('request_failed')?> ", "", "warning");

                }
            });
        });
    });
</script>