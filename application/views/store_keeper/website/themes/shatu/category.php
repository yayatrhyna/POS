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
            <?php if (empty($after_search)) { ?>
                <li><a href="<?php echo base_url('category/p/'.remove_space($category_name).'/' . $category_id) ?>"><?php echo
                        $category_name ?></a></li>
            <?php } ?>
        </ol>
    </div>
</div>
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
                                        <?php
                                    } ?>
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
                                        <?php
                                    }
                                    ?>
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
                                        <span class="count">(<?php  echo display('4').'-'.display('5'); ?>)</span>
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
                                        <span class="count">(<?php  echo display('3').'-'.display('5'); ?>)</span>
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
                                        <span class="count">(<?php  echo display('2').'-'.display('5'); ?>)</span>
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
                                        <span class="count">(<?php echo display('1').'-'.display('5');  ?>)</span>
                                    </a>
                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class=" <?php if (!empty($after_search)) { ?> col-sm-12 after-search-product-block<?php } else { ?>col-sm-9 <?php } ?> content">
                        <?php
                        if ($select_category_adds) {?>
                        <div class="banner-content" style="margin-top: 20px">
                            <?php
                                foreach ($select_category_adds as $ads) {
                            if (1== $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                            ?>
                            <div class="my-40">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $ads->adv_code; ?>
                                    </div>
                                </div>
                            </div>
                            <?php } else if (1 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>
                                <div class="my-40">
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
                            <div class="my-40">
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
                             <?php   }?>
                        </div>
                        <?php } ?>
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
                                <a class="btn btn-warning color4" href="<?php echo base_url()?>"><i class="fa fa-hand-o-left" aria-hidden="true"></i>
                                    <?php echo display('back_to_home') ?></a>
                            </div>
                            <div class="row product_slider">
                                <?php
                                if ($category_product) {
                                foreach ($category_product as $product) {
                                ?>
                                <div class="col-xs-6 col-sm-6 col-lg-3 col-md-4 pr-0">
                                    <div class="item boxed mb-16">
                                        <div class="item_inner pos-rel">
                                            <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                                . $product->product_id) ?>" class="item_title2">
                                            <div class="item_image">
                                                <img src="<?php echo base_url().$product->image_thumb ?>" alt="product-image">
                                            </div>
                                            </a>
                                            <div class="item_info text-center">
                                                <a href="<?php echo base_url('category/p/'.remove_space($product->category_name).'/'
                                                    . $product->category_id) ?>">
                                                <p class="category-name text-capitalize"><?php echo $product->category_name;
                                                    ?></p>
                                                </a>
                                                <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                                    . $product->product_id) ?>" class="item_title2"><?php echo $product->product_name ?></a>
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
                                            </span>
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
                                            </span>
                                                    <?php
                                                }
                                                ?>
                                                <div class="hover_content">
                                                    <ul class="nav">
                                                        <li>
                                                            <a href="#" class="wishlist" name="<?php echo $product->product_id;?>" title="<?php echo display('add_to_wishlist')?>"><i class="fa fa-heart"></i></a>
                                                       </li>
                                                        <li><a href="#"  onclick="cart_btn(<?php echo $product->product_id.', \''.remove_space($product->product_name).'\','; echo ($product->default_variant)? '\''.$product->default_variant.'\'':'\'nai\'' ;  ?>)"><i class="fa fa-shopping-bag"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            }
                        ?>
                                <div class="col-xs-12 col-sm-12">
                                    <div class="pagination-widget">
                                        <?php echo $links; ?>
                                    </div>
                                </div>
                                <?php
                                if ($select_category_adds) {?>
                                    <div class="banner-content" style="margin-top: 20px">
                                        <?php
                                        foreach ($select_category_adds as $ads) {
                                            if (2 == $ads->adv_position && !empty($ads->adv_code) && empty($ads->adv_code2) && empty($ads->adv_code3)) {
                                                ?>
                                                <div class="my-40">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php echo $ads->adv_code; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } else if (2 == $ads->adv_position && !empty($ads->adv_code) && !empty($ads->adv_code2) && empty($ads->adv_code3)) { ?>

                                                <div class="my-40">
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
                                                <div class="my-40">
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
                                        <?php   }?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="category-mobile">
<!--            <div class="modal filter-modal" tabindex="-1" role="dialog">-->
<!--                <div class="modal-dialog modal-sm" role="document">-->
<!--                    <div class="modal-content">-->
<!--                        <div class="accessories">-->
<!--                            <h3 class="sidebar-title">--><?php //echo $category_name ?><!--</h3>-->
<!--                            <ul class="subcategories">-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Men's Bag</span>-->
<!--                                        <span class="total">(1906)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Jewellery</span>-->
<!--                                        <span class="total">(1783)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Wallets & Card holders</span>-->
<!--                                        <span class="total">(1782)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Belts</span>-->
<!--                                        <span class="total">(1373)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Key Rings and Key Holder</span>-->
<!--                                        <span class="total">(882)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Sunglasses & Eyewear</span>-->
<!--                                        <span class="total">(875)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Ties & Cufflinks</span>-->
<!--                                        <span class="total">(247)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Other Accessories</span>-->
<!--                                        <span class="total">(197)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Hats & Caps</span>-->
<!--                                        <span class="total">(190)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-angle-right"></i>-->
<!--                                        <span class="name">Men's Optic</span>-->
<!--                                        <span class="total">(3)</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <div class="sidebar-widget-title">-->
<!--                            <div class="price-Pips">-->
<!--                                <h3 class="sidebar-title">By Price</h3>-->
<!--                                <input type="text" class="price-range" value="price-range" name="price-range" />-->
<!--                            </div>-->
<!--                            <div class="product-size">-->
<!--                                <h3 class="sidebar-title">By Size:</h3>-->
<!--                                <input type="radio" name="size" id="m" value="m" />-->
<!--                                <label for="m"><span class="size">M</span></label>-->
<!---->
<!--                                <input type="radio" name="size" id="l" value="l" />-->
<!--                                <label for="l"><span class="size">L</span></label>-->
<!---->
<!--                                <input type="radio" name="size" id="xl" value="xl" />-->
<!--                                <label for="xl"><span class="size">XL</span></label>-->
<!---->
<!--                                <input type="radio" name="size" id="xxl" value="xxl" />-->
<!--                                <label for="xxl"><span class="size">XXL</span></label>-->
<!--                            </div>-->
<!--                            <div class="product-color">-->
<!--                                <h3 class="sidebar-title">Color Picker:</h3>-->
<!--                                <input type="radio" name="color" id="red2" value="red2" />-->
<!--                                <label for="red2"><span class=""></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="green2" />-->
<!--                                <label for="green2"><span class="green2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="yellow2" />-->
<!--                                <label for="yellow2"><span class="yellow2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="olive2" />-->
<!--                                <label for="olive2"><span class="olive2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="orange2" />-->
<!--                                <label for="orange2"><span class="orange2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="teal2" />-->
<!--                                <label for="teal2"><span class="teal2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="blue2" />-->
<!--                                <label for="blue2"><span class="blue2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="violet2" />-->
<!--                                <label for="violet2"><span class="violet2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="purple2" />-->
<!--                                <label for="purple2"><span class="purple2"></span></label>-->
<!---->
<!--                                <input type="radio" name="color" id="pink2" />-->
<!--                                <label for="pink2"><span class="pink2"></span></label>-->
<!--                            </div>-->
<!--                            <div class="filter-nav brand-cat-widget">-->
<!--                                <h3 class="sidebar-title">By Brand:</h3>-->
<!--                                <div class="search_layered_nav sidebar-search">-->
<!--                                    <input class="form-control" type="text">-->
<!--                                </div>-->
<!--                                <div class="brand-cat-scroll">-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand13" type="checkbox">-->
<!--                                        <label for="brand13">Louis Philippe <span>(737)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand14" type="checkbox">-->
<!--                                        <label for="brand14">Van Heusen <span>(710)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand15" type="checkbox">-->
<!--                                        <label for="brand15">John Players <span>(980)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand16" type="checkbox">-->
<!--                                        <label for="brand16">Blackberrys <span>(508)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand17" type="checkbox">-->
<!--                                        <label for="brand17">Arrow <span>(631)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand18" type="checkbox">-->
<!--                                        <label for="brand18">Park Avenue <span>(120)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand19" type="checkbox">-->
<!--                                        <label for="brand19">Peter England <span>(569)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand20" type="checkbox">-->
<!--                                        <label for="brand20">Samsung <span>(2)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand21" type="checkbox">-->
<!--                                        <label for="brand21">Lg <span>(5)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand22" type="checkbox">-->
<!--                                        <label for="brand22">Infinis <span>(3)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand23" type="checkbox">-->
<!--                                        <label for="brand23">Lenovo <span>(6)</span></label>-->
<!--                                    </div>-->
<!--                                    <div class="checkbox checkbox-success">-->
<!--                                        <input id="brand24" type="checkbox">-->
<!--                                        <label for="brand24">Asus <span>(8)</span></label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="sidebar-review">-->
<!--                                <h3 class="sidebar-title">By Review:</h3>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="rating1" type="checkbox">-->
<!--                                    <label for="rating1">-->
<!--                                        <span class="product-rating">-->
<!--                                            <span class="star-rating">-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star-half-empty"></i>-->
<!--                                            </span>-->
<!--                                            <a href="#" class="review-link">-->
<!--                                                <span class="count">(1)</span>-->
<!--                                            </a>-->
<!--                                        </span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="rating2" type="checkbox">-->
<!--                                    <label for="rating2">-->
<!--                                        <span class="product-rating">-->
<!--                                            <span class="star-rating">-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star-half-empty"></i>-->
<!--                                            </span>-->
<!--                                            <a href="#" class="review-link">-->
<!--                                                <span class="count">(1)</span>-->
<!--                                            </a>-->
<!--                                        </span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="rating3" type="checkbox">-->
<!--                                    <label for="rating3">-->
<!--                                        <span class="product-rating">-->
<!--                                            <span class="star-rating">-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star-half-empty"></i>-->
<!--                                            </span>-->
<!--                                            <a href="#" class="review-link">-->
<!--                                                <span class="count">(1)</span>-->
<!--                                            </a>-->
<!--                                        </span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="rating4" type="checkbox">-->
<!--                                    <label for="rating4">-->
<!--                                        <span class="product-rating">-->
<!--                                            <span class="star-rating">-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star-half-empty"></i>-->
<!--                                            </span>-->
<!--                                            <a href="#" class="review-link">-->
<!--                                                <span class="count">(1)</span>-->
<!--                                            </a>-->
<!--                                        </span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="rating5" type="checkbox">-->
<!--                                    <label for="rating5">-->
<!--                                        <span class="product-rating">-->
<!--                                            <span class="star-rating">-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star"></i>-->
<!--                                                <i class="fa fa-star-half-empty"></i>-->
<!--                                            </span>-->
<!--                                            <a href="#" class="review-link">-->
<!--                                                <span class="count">(1)</span>-->
<!--                                            </a>-->
<!--                                        </span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="discount">-->
<!--                                <h3 class="sidebar-title">Discount:</h3>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount7" type="checkbox">-->
<!--                                    <label for="discount7">80% and above</label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount8" type="checkbox">-->
<!--                                    <label for="discount8">70% and above</label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount9" type="checkbox">-->
<!--                                    <label for="discount9">60% and above</label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount10" type="checkbox">-->
<!--                                    <label for="discount10">50% and above</label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount11" type="checkbox">-->
<!--                                    <label for="discount11">40% and above</label>-->
<!--                                </div>-->
<!--                                <div class="checkbox checkbox-success">-->
<!--                                    <input id="discount12" type="checkbox">-->
<!--                                    <label for="discount12">30% and above</label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="page-title">
                <h2 class="title"><?php echo $category_name ?></h2>
                <h5 class="sub-title"><?php echo $total; ?> <?php echo display('items') ?></h5>
            </div>
            <ul class="category">
                <?php
                if ($category_product) {
                foreach ($category_product as $product) {
                ?>
                <li class="col-xs-4 col-vxs-6">
                    <div class="item boxed mb-16">
                        <div class="item_inner pos-rel">
                            <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                . $product->product_id) ?>" class="item_title2">
                            <div class="item_image">
                                <img src="<?php echo base_url().$product->image_thumb ?>" class="m-0-auto d-block" alt="product-image">
                            </div>
                            </a>
                            <div class="item_info text-center">
                                <a href="<?php echo base_url('category/p/'.remove_space($product->category_name).'/'
                                    . $product->category_id) ?>">
                                <p class="category-name"><?php echo $product->category_name; ?></p>
                                </a>
                                <a href="<?php echo base_url('product_details/'.remove_space($product->product_name).'/'
                                    . $product->product_id) ?>" class="item_title2"><?php echo $product->product_name ?></a>
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
<!--                                <p class="product_cost">$375.00 <del class="ml-5">$399.00</del></p>-->
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
                                        <li>
                                            <a href="#" class="wishlist" name="<?php echo $product->product_id;?>" title="<?php echo display('add_to_wishlist')?>"><i class="fa fa-heart"></i></a>
                                        </li>
                                        <li><a href="#"  onclick="cart_btn(<?php echo $product->product_id.', \''.remove_space($product->product_name).'\','; echo ($product->default_variant)? '\''.$product->default_variant.'\'':'\'nai\'' ;  ?>)"><i class="fa fa-shopping-bag"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                    <?php
                }
                }
                ?>


            </ul>
            <div class="pagination-widget">
                <?php echo $links; ?>
            </div>
        </div>
        <!-- End OF category Mobile -->


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