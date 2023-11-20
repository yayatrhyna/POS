<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Web_settings');
$CI->load->model('Companies');
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
$company_info = $CI->Companies->company_list();
?>

<body class="style-1">
<div class="page-wrapper">
    <nav id="sidebar">
        <div id="dismiss">
            <i class="glyphicon glyphicon-arrow-left"></i>
        </div>
        <ul class="metismenu list-unstyled" id="mobile-menu">
            <?php
            if ($category_list) {
                foreach ($category_list as $parent_category) {
                    $sub_parent_cat = $this->db->select('*')
                        ->from('product_category')
                        ->where('parent_category_id', $parent_category->category_id)
                        ->order_by('menu_pos')
                        ->get()
                        ->result();
                    ?>
                    <li class="">
                        <a <?php if ($sub_parent_cat) { ?> href="#" <?php } else { ?>  href="<?php echo base_url('category/p/'.remove_space($parent_category->category_name).'/' . $parent_category->category_id) ?>" <?php } ?> ><?php echo $parent_category->category_name;
                            if ($sub_parent_cat) {
                                echo "<i class=\"fa arrow\"></i>";
                            } ?> </a>
                        <?php if ($sub_parent_cat) { ?>
                            <ul aria-expanded="false">
                                <?php foreach ($sub_parent_cat as $sub_p_cat) {
                                    $sub_category = $this->db->select('*')
                                        ->from('product_category')
                                        ->where('parent_category_id', $sub_p_cat->category_id)
                                        ->order_by('menu_pos')
                                        ->get()
                                        ->result();
                                    ?>
                                    <li>
                                        <a class="menu-link" <?php if ($sub_category) { ?>  href="#" <?php } else { ?> href="<?php echo base_url('category/p/'.remove_space($sub_p_cat->category_name).'/' . $sub_p_cat->category_id) ?>" <?php } ?>><?php echo $sub_p_cat->category_name;
                                            if ($sub_category) {
                                                echo "<i class=\"fa arrow\"></i>";
                                            } ?> </a>
                                        <?php if ($sub_category) { ?>
                                            <ul aria-expanded="false">
                                                <?php foreach ($sub_category as $sub_cat) { ?>
                                                    <li>
                                                        <a href="<?php echo base_url('category/p/'.remove_space($sub_cat->category_name).'/' . $sub_cat->category_id) ?>"><?php echo $sub_cat->category_name ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </nav>
    <!-- /.End of mobile menu side bar -->
    <div class="overlay"></div>
    <!-- /.End of Promotion -->
    <header class="main-header">
        <nav class="topBar hidden-xs">
            <div class="container">
                <ul class="list-inline pull-left">
                    <li class="pl-0"><?php echo display('call_us') ?> <a href="tel:<?php echo $company_info[0]['mobile'] ?>"><?php echo $company_info[0]['mobile'] ?></a></li>
<!--                    <li><a href="--><?php //echo base_url('about_us');  ?><!--">--><?php //echo display('about_us') ?><!--</a></li>-->
<!--                    <li><a href="--><?php //echo base_url('contact_us');  ?><!--">--><?php //echo display('contact_us') ?><!--</a></li>-->
                    <li><a href="<?php echo base_url('login') ?>"><?php  echo display('login')?></a></li>
                    <li><a href="<?php echo base_url('sign_up') ?>"><?php  echo display('register')?></a></li>
                </ul>
                <ul class="list-inline pull-right social_topBar">
                    <li>
                        <select id='change_currency' class='select resizeselect'>
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
                        <select id="cat_select">
                            <option id="cat_option"></option>
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
<!--                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>-->
<!--                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>-->
<!--                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>-->
<!--                    <li><a href="#"><i class="fa fa-youtube"></i></a></li>-->
                </ul>
            </div>
        </nav>
        <!-- /.End of Top Bar -->
        <div class="middleBar color1">
            <div class="container">
                <div class="row align-center">
                    <div class="col-xs-8 col-sm-3 col-md-2 col-lg-3 text-left">
                        <div class="btnLogo-row">
                            <div class="sidebar-toggle-btn">
                                <button type="button" id="sidebarCollapse" class="btn">
                                    <i class="lnr lnr-menu"></i>
                                </button>
                            </div>
                            <div class="header-logo">
                                <a href="<?php echo base_url() ?>"> <img src="<?php if (isset($Web_settings[0]['logo'])) {
                                        echo base_url().$Web_settings[0]['logo'];
                                    } ?>" class="img-responsive" alt="logo"></a>
                            </div>
                            <!-- /.End of Logo -->
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-8 col-lg-6 text-center hidden-xs">
<!--                        <form class="navbar-search" method="get" action="#">-->
                            <?php echo form_open('category_product_search',['class'=>'navbar-search']) ?>
                            <div class="input-group">
                                <input type="text" id="search" class="form-control search-field product-search-field" dir="ltr" value="" name="product_name" placeholder="<?php echo display('search_product_name_here') ?>" />

                                <div class="input-group-btn">
                                    <input type="hidden" id="search-param" name="post_type" value="product" />
                                    <button type="submit" class="btn searchBtn color3"><span class="lnr lnr-magnifier"></span>Search</button>
                                </div>
                            </div>
                        <?php echo form_close() ?>
<!--                        </form>-->
                        <!-- /.End of Product Search Area -->
                    </div>
                    <div class="col-xs-4 col-sm-2 col-md-2 col-lg-3 text-right" id="tab_up_cart">
                        <ul class="header-nav pull-right">

                            <?php
                            $total_wishlist = 0;
                            if ($this->session->userdata('customer_name')) {
                                $customer_id = $this->session->userdata('customer_id');
                                $this->db->select('a.*,b.*');
                                $this->db->from('wishlist a');
                                $this->db->join('product_information b', 'a.product_id=b.product_id');
                                $this->db->where('a.user_id', $customer_id);
                                $this->db->where('a.status', 1);
                                $query = $this->db->get();
                                $wishlists = $query->result();
                                $total_wishlist = count($wishlists);
                            }
                            ?>

                            <li class="dropdown hnav-li">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="false">  <i class="flaticon-like-1 extra-icon color44"></i>
                                    <div class="nav-label">
                                        <span class="icon-tips color4" id="wishlist_counter"><?php echo(($total_wishlist) ? $total_wishlist
                                                : 0
                                            ) ?></span>
                                    </div>
                                </a>
                                <?php
                                if ($total_wishlist > 0) {
                                ?>
                                <ul class="dropdown-menu cart w-250 shopping-cart" role="menu">

                                    <?php foreach ($wishlists as $wishlist): ?>
                                        <li class="clearfix">
                                            <a href="<?php echo base_url().'product_details/'.remove_space($wishlist->product_name).'/'.$wishlist->product_id;  ?>">
                                            <img src="<?php echo base_url().$wishlist->image_thumb; ?>" alt="item1" />
                                            <span class="item-name"><?php echo $wishlist->product_name; ?></span>
                                            <input href="javascript:void(0)" class="wishlist_product_id"
                                                   type="hidden"
                                                   name="<?php
                                                   echo
                                                   $wishlist->product_id;
                                                   ?>">
                                            </a>
                                            <span class="remove_wishlist pull-right">x</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php  } ?>
                            </li>

                            <li class="dropdown hnav-li">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="false"> <i class="flaticon-shopping-bag extra-icon color44"></i>
                                    <div class="nav-label">
                                        <!--<span class="label-sub">C5art</span>-->
                                        <span class="icon-tips color4"><?php echo $this->cart->total_items() ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu cart w-250 shopping-cart" role="menu">
                                    <li class="shopping-cart-header">
                                        <i class="flaticon-shopping-bag extra-icon"></i><span class="badge color4"><?php echo $this->cart->total_items() ?></span>
                                        <div class="shopping-cart-total">
                                            <span class="lighter-text"><?php echo display('total')  ?>:</span>
                                            <span class="main-color-text"><?php echo(($position == 0) ? $currency->currency_icon . ' ' . number_format($this->cart->total(), 2, '.', ',') : number_format($this->cart->total(), 2, '.', ',') . ' ' . $currency->currency_icon) ?></span>
                                        </div>
                                    </li>
                                    <?php
                                    if ($this->cart->contents()) {
                                    foreach ($this->cart->contents() as $items): ?>
                                    <li class="clearfix">
                                        <img src="<?php echo base_url().$items['options']['image'] ?>" alt="item1" />
                                        <span class="item-name"><?php echo $items['name']; ?></span>
                                        <span class="item-price"><?php echo(($position == 0) ? $currency->currency_icon . ' ' . $this->cart->format_number($items['price']) : $this->cart->format_number($items['price']) . ' ' . $currency->currency_icon) ?></span>
                                        <span class="item-quantity"><?php echo display('quantity')  ?>: <?php echo $items['qty']  ?></span>
                                        <span class="remove_cart_product pull-right"><a href="#" class="delete_cart_item"
                                                                             name="<?php echo $items['rowid'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </a></span>
                                    </li>
                                    <?php endforeach;
                                    } ?>
                                    <li class="text-center">
                                        <a href="<?php echo base_url('view_cart') ?>" class="shopping-cart-btn color4"><?php echo display('view_cart') ?></a>
                                        <a href="<?php echo base_url('checkout') ?>" class="shopping-cart-btn color4"><?php echo display('checkout') ?></a>
                                    </li>
                                </ul>
                            </li>
                            <?php if ($this->session->userdata('customer_name')) { ?>
                            <li class="dropdown hnav-li">
                                    <a href="#" class="account_btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                       data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false"><?php echo ucwords($this->session->userdata('customer_name')) ?> <i
                                                class="fa fa-user-o"></i></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo base_url('customer/customer_dashboard') ?>"><?php echo display('dashboard') ?></a>
                                        </li>
                                        <li><a href="<?php echo base_url('logout') ?>"><?php echo display('logout')
                                                ?></a></li>

                                    </ul>
                            </li>
                                <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="row mobile-search">
                    <div class="col-xs-12">
                        <?php echo form_open('category_product_search',['class'=>'navbar-search']) ?>

                        <div class="form-group has-feedback has-search">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            <input type="text"  id="mobile_search" name="product_name" class="form-control search-field product-search-field" placeholder="<?php echo display('search_product_name_here') ?>">
                            <input type="hidden" id="mobile-search-param" name="post_type" value="product" />
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
                <!-- /. End of mobile search area -->
            </div>
        </div>
        <!-- /.End of Middel Top Bar -->
        <div class="main-nav dn-xs color3">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-4">
                        <nav class="vertical-menu-content">
                            <h3 class="promotion-title"><a href="#"><i class="fa fa-list-ul"></i> <?php echo display('all_categories') ?></a></h3>
                            <ul class="list-group vertical-menu yamm make-absolute color36 hoverable">
                                <?php
                                if ($category_list) {
                                    $i = 1;
                                    foreach ($category_list as $parent_category) {
                                        $sub_parent_cat = $this->db->select('*')
                                            ->from('product_category')
                                            ->where('parent_category_id', $parent_category->category_id)
                                            ->order_by('menu_pos')
                                            ->get()
                                            ->result();
                                        if (10 == $i) {
                                            break;
                                        }
                                        ?>

                                        <li class="width-md menu-item menu-item-has-children animate-dropdown dropdown">
                                            <a title="<?php echo $parent_category->category_name ?>" data-hover="dropdown"
                                               href="<?php
                                               echo base_url('category/p/'.remove_space($parent_category->category_name).'/' . $parent_category->category_id) ?>"
                                               data-toggle="<?php if ($sub_parent_cat) {
                                                   echo "dropdown";
                                               } else {
                                                   echo "";
                                               } ?>" class="dropdown-toggle text-capitalize" aria-haspopup="true"><img src="<?php echo base_url().$parent_category->cat_favicon ?>"
                                                                      height="15"
                                                                      width="16"> <?php echo $parent_category->category_name ?>
                                                <div class="hover-fix"></div>
                                            </a>
                                            <?php
                                            if ($sub_parent_cat) {
                                                ?>
                                                <ul role="menu" class="dropdown-menu">
                                                    <li class="menu-item animate-dropdown menu-item-object-static_block">
                                                        <div class="yamm-content">
                                                            <div class="row">
                                                                <?php foreach ($sub_parent_cat as $parent_cat) { ?>
                                                                    <div class="col-sm-6">
                                                                        <div class="column-inner">
                                                                            <ul class="nav-categories">
                                                                                <li class="nav-title">
                                                                                    <a href="<?php echo base_url('category/p/'.remove_space($parent_cat->category_name).'/' . $parent_cat->category_id) ?>"><?php echo $parent_cat->category_name ?></a>
                                                                                </li>
                                                                                <?php
                                                                                $sub_cat = $this->db->select('*')
                                                                                    ->from('product_category')
                                                                                    ->where('parent_category_id', $parent_cat->category_id)
                                                                                    ->order_by('menu_pos')
                                                                                    ->get()
                                                                                    ->result();
                                                                                if ($sub_cat) {
                                                                                    foreach ($sub_cat as $s_p_cat) {
                                                                                        ?>
                                                                                        <li>
                                                                                            <a href="<?php echo base_url('category/p/'.remove_space($s_p_cat->category_name).'/' . $s_p_cat->category_id) ?>"><?php echo $s_p_cat->category_name ?></a>
                                                                                        </li>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        </li>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                                <li class="width-md menu-item menu-item-has-children animate-dropdown dropdown">
                                    <a title="<?php echo display('all_categories') ?>" data-hover="dropdown"
                                       href="#"
                                       data-toggle="<?php if ($category_list) {
                                           echo "dropdown";
                                       } else {
                                           echo "";
                                       } ?>" class="dropdown-toggle text-capitalize" aria-haspopup="true"><span
                                                class=""><i class="fa fa-bars" aria-hidden="true"></i>
                                    </span> <?php echo display('all_categories') ?>
                                        <div class="hover-fix"></div>
                                    </a>
                                    <?php if ($category_list) { ?>
                                        <ul role="menu" class="dropdown-menu">
                                            <li class="menu-item animate-dropdown menu-item-object-static_block">
                                                <div class="yamm-content">
                                                    <div class="row">
                                                        <?php foreach ($category_list as $parent_cat) { ?>
                                                            <div class="col-sm-6">
                                                                <div class="column-inner">
                                                                    <ul class="nav-categories">
                                                                        <li class="nav-title">
                                                                            <a href="<?php echo base_url('category/p/'.remove_space($parent_cat->category_name).'/' . $parent_cat->category_id) ?>"><?php echo $parent_cat->category_name ?></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    <?php } ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-9 col-sm-8">
                        <nav class="navbar content-right">
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-nav">
                                    <li class="text-uppercase <?php echo (($this->uri->segment(2) == '')? 'active':'')?>"><a class="" href="<?php echo base_url(); ?>"><?php echo display('home') ?></a></li>
                                    <?php
                                    if (!empty($category_list)):
                                        $ml=1;
                                        foreach ($category_list as $v_category_list): ?>
                                            <?php if (1 == $v_category_list->top_menu && $ml <= 5) { ?>
                                                <li class="<?php echo (($this->uri->segment(2) == $v_category_list->category_id)? 'active':'')?>">
                                                    <a class="text-uppercase"
                                                       href="<?php echo base_url('/category/p/'.remove_space($v_category_list->category_name).'/' . $v_category_list->category_id) ?>"><?php echo
                                                        $v_category_list->category_name;
                                                        ?></a></li>
                                            <?php $ml++; }  endforeach;
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php
    if (!empty($this->session->userdata('err_message'))) {
        ?>
        <script>
            Swal({
                position: 'center',
                type: 'warning',
                title: '<?php echo $this->session->userdata('err_message')?>',
                showConfirmButton: false,
                timer: 3000
            })
        </script>
        <?php
        $this->session->unset_userdata('err_message');
    }
    ?>
    <?php
    if (!empty($this->session->userdata('order_completed'))) {
        ?>
        <script>
            Swal({
                position: 'center',
                type: 'success',
                title: '<?php echo $this->session->userdata('order_completed')?>',
                showConfirmButton: false,
                timer: 4000
            }).then((result) => {
                <?php $this->session->unset_userdata('order_completed');  ?>
                location.reload();
            })
        </script>
        <?php

    }
    ?>

    <script>
        //Change currency ajax
        $('body').on('change', '#-change_currency', function () {
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

    </script>