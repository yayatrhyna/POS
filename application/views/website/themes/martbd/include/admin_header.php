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
                    <a <?php if ($sub_parent_cat) { ?> href="#" <?php } else { ?>  href="<?php echo base_url('category/p/' . $parent_category->category_id) ?>" <?php } ?> ><?php echo $parent_category->category_name;
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

<!-- /.End of Top Bar -->
<div class="middleBar">
    <div class="container">
        <div class="row display-table">
            <div class="col-xs-7 col-sm-3 col-md-2 col-lg-3 vertical-align text-left">
                <div class="btnLogo-row">
                    <div class="sidebar-toggle-btn">
                        <button type="button" id="sidebarCollapse" class="btn">
                            <i class="lnr lnr-menu"></i>
                        </button>
                    </div>
                    <div class="header-logo">
                        <a href="<?php echo base_url() ?>"> <img src="<?php if (isset($Web_settings[0]['logo'])) {
                                echo base_url().$Web_settings[0]['logo'];
                            } ?>" class="img-responsive" alt=""></a>
                    </div>
                    <!-- /.End of Logo -->
                </div>
            </div>
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
            <div class="col-sm-7 col-md-8 col-lg-6 vertical-align text-center hidden-xs">
                <?php echo form_open('category_product_search') ?>
                <div class="input-group">
                    <input type="text" name="product_name" id="product_name"
                           class="form-control search-field product-search-field"
                           dir="ltr" value="" placeholder="<?php echo display('search_product_name_here') ?>"/>

                    <div class="input-group-btn">
                        <input type="hidden" id="search-param" name="post_type" value="product"/>
                        <button type="submit" class="btn btn-warning color3 color36"><span class="lnr
                        lnr-magnifier"></span>Search
                        </button>
                    </div>
                </div>
                <?php echo form_close() ?>
                <!-- /.End of Product Search Area -->
            </div>
            <div class="col-xs-5 col-sm-2 col-md-2 col-lg-3 vertical-align text-right">
                <ul class="header-nav pull-right">
                    <li class="hnav-li dropdown">
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="false">
                            <i class="flaticon-like-1 extra-icon"></i> <span class="icon-tips color3"><b
                                        class="bb-tri"></b><strong><?php echo($total_wishlist ? $total_wishlist
                                        : 0
                                    ) ?></strong></span>
                        </a>
                        <?php
                        if ($total_wishlist > 0) {
                            ?>
                            <ul class="dropdown-menu w-250 shopping-cart" role="menu">
                                <?php foreach ($wishlists as $wishlist): ?>
                                    <li class="shopping-cart-header">
                                        <a href="<?php echo base_url() . 'product_details/'.remove_space($wishlist->product_name).'/' . $wishlist->product_id ?>">
                                            <img src="<?php echo base_url().$wishlist->image_thumb; ?>" alt="image">
                                            <div class="shopping-cart-total">
                                                <span class="lighter-text"><?php echo $wishlist->product_name; ?></span>
                                                <input href="javascript:void(0)" class="wishlist_product_id"
                                                       type="hidden"
                                                       name="<?php
                                                       echo
                                                       $wishlist->product_id;
                                                       ?>">
                                                <span class="main-wishlist-color-text"><?php echo $wishlist->price; ?></span>
                                                <span class="remove_wishlist">x</span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </li>
                    <li class="dropdown hnav-li" id="tab_up_cart">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="false"> <i class="flaticon-shopping-bag extra-icon"></i>
                            <div class="nav-label">
                                <span class="icon-tips color3"><b
                                            class="bb-tri"></b><strong><?php echo $this->cart->total_items() ?></strong></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu cart w-250 product-cart" role="menu">
                            <li class="cart-header">
                                <i class="lnr lnr-cart cart-icon"></i><span
                                        class="badge color3"><?php echo $this->cart->total_items() ?></span>
                                <div class="product-cart-total">
                                    <span class="lighter-text"><?php echo display('total') ?>:</span>
                                    <span class="main-color-text color35"><?php echo(($position == 0) ? $currency . ' ' . number_format($this->cart->total(), 2, '.', ',') : number_format($this->cart->total(), 2, '.', ',') . ' ' . $currency) ?></span>
                                </div>
                            </li>

                            <?php
                            if ($this->cart->contents()) {
                                foreach ($this->cart->contents() as $items): ?>
                                    <li class="clearfix">
                                        <img src="<?php echo base_url().$items['options']['image'] ?>"
                                             alt="item1"/>
                                        <div class="product-cart-info">
                                            <span class="item-name"><?php echo $items['name']; ?></span>
                                            <span class="item-price"><?php echo(($position == 0) ? $currency . ' ' . $this->cart->format_number($items['price']) : $this->cart->format_number($items['price']) . ' ' . $currency) ?></span>

                                            <span class="remove_cart_product"><a href="#" class="delete_cart_item"
                                                                                 name="<?php echo $items['rowid'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </a></span>
                                        </div>
                                    </li>
                                <?php endforeach;
                            } ?>
                            <?php if ($this->cart->contents()) { ?>

                                <li class="text-center">
                                    <a href="<?php echo base_url('view_cart') ?>"
                                       class="shopping-cart-btn"><?php echo display('view_cart') ?></a>
                                    <a href="<?php echo base_url('checkout') ?>"
                                       class="shopping-cart-btn"><?php echo display('checkout') ?></a>

                                </li>
                            <?php } ?>
                        </ul>
                        <!--/. End of shopping cart -->
                    </li>
                    <li class="dropdown hnav-li">
                        <?php if ($this->session->userdata('customer_name')) { ?>
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
                        <?php } else { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                               data-close-others="false"> <i class="flaticon-user extra-icon"></i>
                                <div class="nav-label">
                                    <span class="label-sup"><?php echo display('your') ?></span>
                                    <span class="label-sub"><?php echo display('account') ?></span>
                                    <i class="fa fa-angle-down ml-5"></i>
                                </div>
                            </a>
                            <ul class="dropdown-menu user-register w-260" role="menu">
                                <li>
                                    <div class="user-info text-center">
                                        <h2><?php echo display('signin') ?></h2>
                                        <p>Sign in Using Your Email Address</p>
                                        <form action="<?php echo base_url('do_login') ?>" method="post">
                                            <div class="form-group">
                                                <input class="form-control" name="email" required id="email"
                                                       value="<?php echo $this->session->userdata('customer_email') ?>"
                                                       placeholder="<?php echo display('username_or_email') ?>"
                                                       type="text">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" name="password" id="pass"
                                                       placeholder="<?php echo
                                                       display('password') ?>"
                                                       required type="password">
                                            </div>
                                            <div class="block-content">
                                                <a href="<?php echo base_url('forget_password_form') ?>"
                                                   class="forgot"><?php echo
                                                    display
                                                    ('forget_password') ?></a>
                                            </div>
                                            <input type="submit" class="btn btn-primary btn-block" value="<?php echo
                                            display('login')
                                            ?> &#8702; ">

                                        </form>
                                        <div class="have-ac"><?php echo display('dont_have_an_account') ?> <a
                                                    href="<?php echo base_url('signup') ?>"><?php echo display('sign_up') ?></a>
                                        </div>
                                    </div>
                                    <!-- /.End of Login -->
                                </li>
                            </ul>
                        <?php } ?>
                    </li>
                </ul><!--  /.End of Header Nav -->
            </div>
        </div>
        <div class="row mobile-search">
            <div class="col-xs-12">
                 <?php echo form_open('category_product_search') ?>
                <div class="form-group has-feedback has-search">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    <input type="text" name="product_name" id="mobile_product_name" class="form-control" placeholder="Search for products">
                </div>
                 <?php echo form_close() ?>
            </div>
        </div>
        <!-- /. End of mobile search area -->
    </div>
</div>
<!-- /.End of Middel Top Bar -->
<?php //dd($category_list);?>
<div class=" main-nav">
    <div class="container">
        <nav class="navbar">
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="text-uppercase <?php echo (($this->uri->segment(2) == '')? 'active':'')?>"><a class=""
                                                         href="<?php echo base_url(); ?>"><?php echo display('home')
                            ?></a></li>
                    <?php

                    if (!empty($category_list)):
                        foreach ($category_list as $v_category_list): ?>
                            <?php if (1 == $v_category_list->top_menu) { ?>
                                <li class="<?php echo (($this->uri->segment(2) == $v_category_list->category_id)? 'active':'')?>">
                                    <a class="text-uppercase"
                                       href="<?php echo base_url('/category/p/'.remove_space($v_category_list->category_name).'/' . $v_category_list->category_id) ?>"><?php echo
                                        $v_category_list->category_name;
                                        ?></a></li>
                            <?php } endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </nav>
    </div>
</div>
</header>

<style type="text/css">
    input.loading {
        background: #fff url(<?php echo base_url('assets/website/image/resize.gif')?>) no-repeat center !important;
    }
</style>


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
