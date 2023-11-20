<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
$CI =& get_instance();
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
?>
<div class="page-breadcrumbs">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()?>"><?php echo display('home')?></a></li>
            <li class="active"><?php echo $brand_name?></li>
        </ol>
    </div>
</div>
<!-- /.End of page breadcrumbs -->
<div class="category-content hidden-xs">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 leftSidebar hidden-xs">
                <div class="sidebar-widget">
                    <!-- /.End of accessories -->
                    <div class="sidebar-widget-title">
                        <?php
                        if ($max_value) {
                            ?>
                            <div class="price-Pips">
                                <form action="" method="post" id="priceForm">
                                    <h3 class="sidebar-title"><?php echo display('by_price')?></h3>
                                    <input type="text" class="price-range" value="price-range" name="price-range"/>
                                </form>
                            </div>
                            <?php
                        }
                        if ($variant_list) {
                            ?>
                            <div class="product-size">
                                <h3 class="sidebar-title"><?php echo display('by_variant')?>:</h3>
                                <?php
                                if ($variant_list) {
                                    $i=1;
                                    foreach ($variant_list as $variant) {
                                        ?>
                                        <input type="radio" class="size1" name="size" id="<?php echo $i?>" value="<?php
                                        $currentURL = current_url();
                                        $params = $_SERVER['QUERY_STRING'];
                                        $size = $this->input->get('size');
                                        if ($params) {
                                            if ($size) {
                                                $new_param = str_replace("size=".$size, "size=".$variant['variant_id'] ,$params);
                                                echo $fullURL = $currentURL . '?' .$new_param;
                                            }else{
                                                echo $fullURL = $currentURL . '?' .$params.'&size='.$variant['variant_id'];
                                            }
                                        }else{
                                            echo $fullURL = $currentURL.'?size='.$variant['variant_id'];
                                        }
                                        ?>" <?php if ($size == $variant['variant_id']) {echo "checked"; }?>/>
                                        <label for="<?php echo $i?>"><span class="size"><?php echo $variant['variant_name']?></span></label>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </div>
                            <!--  /.End of product color selector -->
                            <?php
                        }
                        $lang_id = 0;
                        $user_lang = $this->session->userdata('language');
                        if (empty($user_lang)) {
                            $lang_id = 'english';
                        }else{
                            $lang_id = $user_lang;
                        }
                        ?>
                        <div class="sidebar-review">
                            <h3 class="sidebar-title"><?php echo display('rating')?>:</h3>
                            <div class="checkbox checkbox-success">
                                <input id="rating1" type="checkbox" class="check_value" value="<?php
                                $currentURL = current_url();
                                $params = $_SERVER['QUERY_STRING'];
                                $rate = $this->input->get('rate');
                                if ($params) {
                                    if ($rate) {
                                        $new_param = str_replace("rate=".$rate, "rate=4-5" ,$params);
                                        echo $fullURL = $currentURL . '?' .$new_param;
                                    }else{
                                        echo $fullURL = $currentURL . '?' .$params.'&rate=4-5';
                                    }
                                }else{
                                    echo $fullURL = $currentURL.'?rate=4-5';
                                }
                                ?>" <?php if ($this->input->get('rate') == '4-5'){ echo("checked"); }?>>
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
                                            <span class="count">(<?php echo display('4').'-'.display('5');  ?>)</span>
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
                                        $new_param = str_replace("rate=".$rate, "rate=3-5" ,$params);
                                        echo $fullURL = $currentURL . '?' .$new_param;
                                    }else{
                                        echo $fullURL = $currentURL . '?' .$params.'&rate=3-5';
                                    }
                                }else{
                                    echo $fullURL = $currentURL.'?rate=3-5';
                                }
                                ?>" <?php if ($this->input->get('rate') == '3-5'){ echo("checked");}?>>
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
                                            <span class="count">(<?php echo display('3').'-'.display('5');  ?>)</span>
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
                                        $new_param = str_replace("rate=".$rate, "rate=2-5" ,$params);
                                        echo $fullURL = $currentURL . '?' .$new_param;
                                    }else{
                                        echo $fullURL = $currentURL . '?' .$params.'&rate=2-5';
                                    }
                                }else{
                                    echo $fullURL = $currentURL.'?rate=2-5';
                                }
                                ?>" <?php if ($this->input->get('rate') == '2-5') {echo("checked");}?>>
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
                                            <span class="count">(<?php echo display('2').'-'.display('5');  ?>)</span>
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
                                        $new_param = str_replace("rate=".$rate, "rate=1-5" ,$params);
                                        echo $fullURL = $currentURL . '?' .$new_param;
                                    }else{
                                        echo $fullURL = $currentURL . '?' .$params.'&rate=1-5';
                                    }
                                }else{
                                    echo $fullURL = $currentURL.'?rate=1-5';
                                }
                                ?>" <?php if ($this->input->get('rate') == '1-5') {echo("checked");}?>>
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
                        <!--  /.End of review rating -->

                    </div>
                </div>
            </div>
            <div class="col-sm-9 content">
                <div class="filter-row align-items-center justify-content-between">
                    <div class="filter-title">
                        <h1><?php echo $brand_name.' - '?></h1>
                        <span>
                        <?php
                        echo $item = count($brand_product);
                        if ($item <= 0) {
                            echo " ".display('item_found');
                        }elseif ($item > 0) {
                            echo " ".display('items');
                        }
                        ?>
                        </span>
                    </div>
                </div>
                <!-- /.End of filter summary -->
                <div class="row mr-0 product_slider">
                    <?php
                    $page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
                    $total = count($brand_product); //total items in array
                    $limit = 16; //per page
                    $totalPages = ceil( $total/ $limit ); //calculate total pages
                    $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                    $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                    $offset = ($page - 1) * $limit;
                    if( $offset < 0 ) $offset = 0;

                    $brand_product = array_slice( $brand_product, $offset, $limit );

                    if ($brand_product) {
                        foreach ($brand_product as $product) {
                            $select_single_category = $this->Categories->select_single_category_by_id($product['category_id']);
                            ?>
                            <div class="col-xs-6 col-sm-6 col-lg-3 col-md-4 pr-0">
                                <div class="item boxed mb-16">
                                    <div class="item_inner pos-rel">
                                        <a href="<?php echo base_url('product_details/'.remove_space($product['product_name']).'/'
                                            . $product['product_id']) ?>" class="item_title2">
                                            <div class="item_image">
                                                <img src="<?php echo((base_url().$product['image_thumb'])? base_url().$product['image_thumb']: base_url().'my-assets/image/no-image.jpg'); ?>" alt="product-image">
                                            </div>
                                        </a>
                                        <div class="item_info text-center">
                                            <a href="<?php echo base_url('category/p/'.remove_space($product['category_name']).'/'
                                                . $product['category_id']) ?>">
                                                <p class="category-name text-capitalize"><?php echo $product['category_name'];
                                                    ?></p>
                                            </a>
                                            <a href="<?php echo base_url('product_details/'.remove_space($product['product_name']).'/'
                                                . $product['product_id']) ?>" class="item_title2"><?php echo $product['product_name'] ?></a>
                                            <div class="rating_stars">
                                                <div class="rating-wrap m-0-auto">
                                                    <?php
                                                    $result = $this->db->select('sum(rate) as rates')
                                                        ->from('product_review')
                                                        ->where('product_id', $product['product_id'])
                                                        ->get()
                                                        ->row();

                                                    $rater = $this->db->select('rate')
                                                        ->from('product_review')
                                                        ->where('product_id', $product['product_id'])
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
                                            <!--                                                <p class="product_cost">$375.00 <del class="ml-5">$399.00</del></p>                                        -->

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

                                            <?php if ($product['onsale'] == 1 && !empty($product['onsale_price'])) { ?>
                                                <span class="price">
                                                <span class="price-amount">
                                                    <ins><span class="amount">
                                                    <?php

                                                    if ($target_con_rate > 1) {
                                                        $price = $product['onsale_price'] * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    if ($target_con_rate <= 1) {
                                                        $price = $product['onsale_price'] * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }
                                                    ?>
                                                    </span></ins>
                                                    <del><span class="amount">
                                                    <?php
                                                    if ($target_con_rate > 1) {
                                                        $price = $product['price'] * $target_con_rate;
                                                        echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                    }

                                                    if ($target_con_rate <= 1) {
                                                        $price = $product['price'] * $target_con_rate;
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
                                                           $price = $product['price'] * $target_con_rate;
                                                           echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                                       }

                                                       if ($target_con_rate <= 1) {
                                                           $price = $product['price'] * $target_con_rate;
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
                                                        <a href="#" class="wishlist" name="<?php echo $product['product_id'];?>" title="<?php echo display('add_to_wishlist')?>"><i class="fa fa-heart"></i></a>
                                                    </li>
                                                    <li><a href="#"  onclick="cart_btn(<?php echo $product['product_id'].', \''.remove_space($product['product_name']).'\','; echo ($product['default_variant'])? '\''.$product['default_variant'].'\'':'\'nai\'' ;  ?>)"><i class="fa fa-shopping-bag"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } }?>
                    <?php
                    if ($total > 0) {
                        ?>
                        <div class="col-xs-12 col-sm-12">
                            <div class="pagination-widget">
                                <?php
                                $start      = ( ( $page - $total ) > 0 ) ? $page - $total : 1;
                                $end        = ( ( $page + $total ) < $totalPages ) ? $page + $total : $totalPages;

                                $html       = '<ul class="pagination">';
                                $class      = ( $page == 1 ) ? "disabled" : "";
                                $html       .= '<li class="' . $class . '"><a href="?page=' . ( $page - 1 ) . '">⇽</a></li>';

                                if ( $start > 1 ) {
                                    $html   .= '<li><a href="?page=1">1</a></li>';
                                    $html   .= '<li class="disabled"><span>...</span></li>';
                                }

                                for ( $i = $start ; $i <= $end; $i++ ) {
                                    $class  = ( $page == $i ) ? "active" : "";
                                    $html   .= '<li class="' . $class . '"><a href="?page=' . $i . '">' . $i . '</a></li>';
                                }

                                if ( $end < $totalPages ) {
                                    $html   .= '<li class="disabled"><span>...</span></li>';
                                    $html   .= '<li><a href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                }

                                $class       = ( $page == $totalPages ) ? "disabled" : "";
                                $html       .= '<li class="' . $class . '"><a href="?page=' . ( $page + 1 ) . '">⇾</a></li>';

                                echo $html  .= '</ul>';
                                ?>
                            </div>
                        </div>
                        <!-- /.End of pagination -->
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.End of category content -->
<div class="category-mobile">
    <div class="mobile-filter-nav align-items-center justify-content-between">

        <div class="col-xs-12 filter text-center">
            <button type="button" class="" data-toggle="modal" data-target=".filter-modal"><i class="fa fa-filter" aria-hidden="true"></i><?php echo display('filter')?></button>
        </div>
    </div>
    <div class="modal filter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <!-- /.End of accessories -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="sidebar-widget-title">
                    <?php
                    if ($max_value) {
                        ?>
                        <div class="price-Pips">
                            <form action="" method="post" id="priceForm1">
                                <h3 class="sidebar-title"><?php echo display('by_price')?></h3>
                                <input type="text" class="price-range" value="price-range" name="price-range" />
                            </form>
                        </div>
                        <?php
                    }
                    if ($variant_list) {
                        ?>
                        <div class="product-size">
                            <h3 class="sidebar-title"><?php echo display('by_variant')?>:</h3>
                            <?php
                            if ($variant_list) {
                                $i=1;
                                foreach ($variant_list as $variant) {
                                    ?>
                                    <input type="radio" class="size1" name="size" id="<?php echo $i?>" value="<?php
                                    $currentURL = current_url();
                                    $params = $_SERVER['QUERY_STRING'];
                                    $size = $this->input->get('size');
                                    if ($params) {
                                        if ($size) {
                                            $new_param = str_replace("size=".$size, "size=".$variant['variant_id'] ,$params);
                                            echo $fullURL = $currentURL . '?' .$new_param;
                                        }else{
                                            echo $fullURL = $currentURL . '?' .$params.'&size='.$variant['variant_id'];
                                        }
                                    }else{
                                        echo $fullURL = $currentURL.'?size='.$variant['variant_id'];
                                    }
                                    ?>" <?php if ($size == $variant['variant_id']) {echo "checked"; }?>/>
                                    <label for="<?php echo $i?>"><span class="size"><?php echo $variant['variant_name']?></span></label>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <!--  /.End of product color selector -->
                        <?php
                    }
                    $lang_id = 0;
                    $user_lang = $this->session->userdata('language');
                    if (empty($user_lang)) {
                        $lang_id = 'english';
                    }else{
                        $lang_id = $user_lang;
                    }
                    ?>
                    <div class="sidebar-review">
                        <h3 class="sidebar-title"><?php echo display('rating')?>:</h3>
                        <div class="checkbox checkbox-success">
                            <input id="rating1" type="checkbox" class="check_value" value="<?php
                            $currentURL = current_url();
                            $params = $_SERVER['QUERY_STRING'];
                            $rate = $this->input->get('rate');
                            if ($params) {
                                if ($rate) {
                                    $new_param = str_replace("rate=".$rate, "rate=4-5" ,$params);
                                    echo $fullURL = $currentURL . '?' .$new_param;
                                }else{
                                    echo $fullURL = $currentURL . '?' .$params.'&rate=4-5';
                                }
                            }else{
                                echo $fullURL = $currentURL.'?rate=4-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '4-5'){ echo("checked"); }?>>
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
                                        <span class="count">(<?php echo display('4').'-'.display('5');  ?>)</span>
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
                                    $new_param = str_replace("rate=".$rate, "rate=3-5" ,$params);
                                    echo $fullURL = $currentURL . '?' .$new_param;
                                }else{
                                    echo $fullURL = $currentURL . '?' .$params.'&rate=3-5';
                                }
                            }else{
                                echo $fullURL = $currentURL.'?rate=3-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '3-5'){ echo("checked");}?>>
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
                                        <span class="count">(<?php echo display('3').'-'.display('5');  ?>)</span>
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
                                    $new_param = str_replace("rate=".$rate, "rate=2-5" ,$params);
                                    echo $fullURL = $currentURL . '?' .$new_param;
                                }else{
                                    echo $fullURL = $currentURL . '?' .$params.'&rate=2-5';
                                }
                            }else{
                                echo $fullURL = $currentURL.'?rate=2-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '2-5') {echo("checked");}?>>
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
                                        <span class="count">(<?php echo display('2').'-'.display('5');  ?>)</span>
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
                                    $new_param = str_replace("rate=".$rate, "rate=1-5" ,$params);
                                    echo $fullURL = $currentURL . '?' .$new_param;
                                }else{
                                    echo $fullURL = $currentURL . '?' .$params.'&rate=1-5';
                                }
                            }else{
                                echo $fullURL = $currentURL.'?rate=1-5';
                            }
                            ?>" <?php if ($this->input->get('rate') == '1-5') {echo("checked");}?>>
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
                    <!--  /.End of review rating -->


                </div>
            </div>
        </div>
    </div>

    <ul class="category">
        <?php
        if ($brand_product) {
            foreach ($brand_product as $product) {
                $select_single_category = $this->Categories->select_single_category_by_id($product['category_id']);
                ?>
                <li class="col-xs-4">
                    <div class="product-box">
                        <div class="imagebox">
                            <span class="product-cat"><a href="<?php echo base_url('category/p/'.remove_space($select_single_category->category_name).'/'.$product['category_id'])?>"><?php echo $select_single_category->category_name?></a></span>
                            <a href="<?php echo base_url('product_details/'.remove_space($product['product_name']).'/'.$product['product_id'])?>">
                                <h3 class="product-name"><?php echo $product['product_name']?></h3>
                                <div class="product-thumb">
                                    <img src="<?php echo base_url().$product['image_thumb']?>" data-src="<?php echo base_url().$product['image_thumb']?>" alt="">
                                </div>
                            </a>
                            <span>
                        <b>
                        <?php
                        if ($product['brand_name']) {
                            echo $product['brand_name'];
                        }else{
                            echo $product['first_name'].' '.$product['last_name'];
                        }
                        ?>
                        </b>
                    </span>
                            <div class="price-cart">
                                <?php
                                $currency_new_id =  $this->session->userdata('currency_new_id');

                                if (empty($currency_new_id)) {
                                    $result  =  $cur_info = $this->db->select('*')
                                        ->from('currency_info')
                                        ->where('default_status','1')
                                        ->get()
                                        ->row();
                                    $currency_new_id = $result->currency_id;
                                }

                                if (!empty($currency_new_id)) {
                                    $cur_info = $this->db->select('*')
                                        ->from('currency_info')
                                        ->where('currency_id',$currency_new_id)
                                        ->get()
                                        ->row();

                                    $target_con_rate = $cur_info->convertion_rate;
                                    $position1 = $cur_info->currency_position;
                                    $currency1 = $cur_info->currency_icon;
                                }
                                ?>

                                <?php if ($product['onsale'] == 1 && !empty($product['onsale_price'])) { ?>
                                    <span class="price">
                            <span class="price-amount">
                                <ins><span class="amount">
                                <?php
                                if ($target_con_rate > 1) {
                                    $price = $product['onsale_price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
                                }

                                if ($target_con_rate <= 1) {
                                    $price = $product['onsale_price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
                                }
                                ?>
                                </span></ins>
                                <del><span class="amount">
                                <?php
                                if ($target_con_rate > 1) {
                                    $price = $product['price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
                                }

                                if ($target_con_rate <= 1) {
                                    $price = $product['price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
                                }
                                ?>
                                </span></del>
                                <span class="amount"> </span>
                            </span>
                        </span><!-- /.Price -->
                                    <?php
                                }else{
                                    ?>
                                    <span class="price">
                            <span class="price-amount">
                                <ins><span class="amount">
                                <?php
                                if ($target_con_rate > 1) {
                                    $price = $product['price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
                                }

                                if ($target_con_rate <= 1) {
                                    $price = $product['price'] * $target_con_rate;
                                    echo (($position1==0)?$currency1." ".number_format($price, 2, '.', ','):number_format($price, 2, '.', ',')." ".$currency1);
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
                                            ->where('product_id', $product['product_id'])
                                            ->get()
                                            ->row();

                                        $rater = $this->db->select('rate')
                                            ->from('product_review')
                                            ->where('product_id', $product['product_id'])
                                            ->get()
                                            ->num_rows();
                                        if ($result->rates != null) {
                                            $total_rate = $result->rates/$rater;
                                            if (gettype($total_rate) == 'integer') {
                                                for ($t=1; $t <= $total_rate; $t++) {
                                                    echo "<i class=\"fa fa-star\"></i>";
                                                }
                                                for ($tt=$total_rate; $tt < 5; $tt++) {
                                                    echo "<i class=\"fa fa-star-o\"></i>";
                                                }
                                            }elseif (gettype($total_rate) == 'double') {
                                                $pieces = explode(".", $total_rate);
                                                for ($q=1; $q <= $pieces[0]; $q++) {
                                                    echo "<i class=\"fa fa-star\"></i>";
                                                    if ($pieces[0] == $q) {
                                                        echo "<i class=\"fa fa-star-half-o\"></i>";
                                                        for ($qq=$pieces[0]; $qq < 4; $qq++) {
                                                            echo "<i class=\"fa fa-star-o\"></i>";
                                                        }
                                                    }
                                                }

                                            }else{
                                                for ($w=0; $w <= 4; $w++) {
                                                    echo "<i class=\"fa fa-star-o\"></i>";
                                                }
                                            }
                                        }else{
                                            for ($o=0; $o <= 4; $o++) {
                                                echo "<i class=\"fa fa-star-o\"></i>";
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="total-rating">(<?php echo $rater?>)</div>
                                </div><!-- Rating -->
                            </div><!-- /.price-add-to-cart -->
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
        <?php
        if ($links) {
            ?>
            <div class="col-xs-12 col-sm-12">
                <div class="pagination-widget">
                    <?php echo $links;?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        /*------------------------------------
        Price range slide
        -------------------------------------- */
        $(".price-range").ionRangeSlider({
            type: "double",
            grid: true,
            min: <?php echo $min_value?>,
            max: <?php echo $max_value?>,
            from: <?php if ($from_price == 0) {echo 'null';}else{echo $from_price;}?>,
            to: <?php if ($to_price == 0) {echo 'null';}else{echo $to_price;}?>,
            prefix: "<?php echo $default_currency_icon;?> ",
            onChange: function (data) {

                //field "search";
                var pattern = /[?]/;
                var URL = location.search;
                var fullURL = document.URL;

                if(pattern.test(URL))
                {
                    var $_GET = {};
                    if(document.location.toString().indexOf('?') !== -1) {
                        var query = document.location
                            .toString()
                            // get the query string
                            .replace(/^.*?\?/, '')
                            // and remove any existing hash string (thanks, @vrijdenker)
                            .replace(/#.*$/, '')
                            .split('&');

                        for(var i=0, l=query.length; i<l; i++) {
                            var aux = decodeURIComponent(query[i]).split('=');
                            $_GET[aux[0]] = aux[1];
                        }
                    }

                    //Get from value by get method
                    if ($_GET['price']) {
                        var fullURL = window.location.href.split('?')[0];
                        var url = window.location.search;
                        url = url.replace("price="+$_GET['price'], 'price='+data.from+'-'+data.to);
                        window.location.href = fullURL+url;
                    }else{
                        var url = window.location.search;
                        window.location.href = url+'&price=' + data.from+'-'+data.to;
                    }

                }else{
                    var fullURL = window.location.href.split('?')[0];
                    window.location.href = fullURL.split('?')[0]+'?price=' + data.from+'-'+data.to
                }
            }
        });
        /*------------------------------------
        Product search by size
        -------------------------------------- */
        $('body').on('click', '.size1', function() {
            var size_location = $(this).val();
            window.location.href = size_location;
        });
        /*------------------------------------
        Sorting product by category
        -------------------------------------- */
        $('#popularity').on('change',function(){
            var sorting_location = $(this).val();
            window.location.href = sorting_location;
        });
        /*------------------------------------
        Sorting product by category for mobile
        -------------------------------------- */
        $('#popularity_mobile').on('change',function(){
            var sorting_location = $(this).val();
            window.location.href = sorting_location;
        });
        /*------------------------------------
        Sort by rating
        -------------------------------------- */
        $('.check_value').on('click',function(){
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
    });
</script>

