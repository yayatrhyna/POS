<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Web_settings');
$CI->load->model('Companies');
$Web_settings = $CI->Web_settings->retrieve_setting_editdata();
$company_info = $CI->Companies->company_list();
?>
<!--====== Header Area ======-->
<header>
    <!-- Main Header Area -->
    <div class="header_main color1">
        <div class="container">
            <nav class="navbar navbar-toggleable-md row m0 navbar-light">
                <button class="navbar-toggler navbar-toggler-right" id="open">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo base_url() ?>">
                    <img src="<?php if (isset($Web_settings[0]['logo'])) {
                        echo base_url().$Web_settings[0]['logo'];
                    } ?>">
                </a>
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
                <div class="navbar-collapse justify-content-end" id="navbarNavDropdown">

                    <!--========= Account Box start =========-->

                    <div class="account_area hidden-md-down">
                        <?php if ($this->session->userdata('customer_name')) { ?>
                            <div class="dropdown">
                                <a href="#" class="account_btn dropdown-toggle  btn-sm" type="button" id="dropdownMenuButton" style="color:#eee;"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ucwords($this->session->userdata('customer_name')) ?> &nbsp;<i
                                            class="fa fa-user-o"></i></a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <a class="dropdown-item"
                                       href="<?php echo base_url('customer/customer_dashboard') ?>"><?php echo display('dashboard') ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url('logout') ?>">Logout</a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <a href="#" class="account_btn" data-toggle="modal" data-target="#login_box"><i
                                        class="fa fa-user-o"></i></a>

                            <div class="modal fade" id="login_box">
                                <div class="modal-dialog">
                                    <a class="hiddenanchor" id="toregister"></a>
                                    <a class="hiddenanchor" id="tologin"></a>
                                    <div id="login_inner">

                                        <!--================= login form section ===============-->
                                        <div id="login" class="animate form">
                                            <h2 class="box_heading color2"><?php echo display('login') ?></h2>

                                            <form action="<?php echo base_url('do_login') ?>" method="post">

                                                <div class="input_area">
                                                    <label for="email" class="uname" data-icon="u">
                                                        <i class="fa fa-envelope color5"></i>
                                                    </label>
                                                    <input type="email" placeholder="<?php echo display('email') ?>"
                                                           name="email" required id="email"
                                                           value="<?php echo $this->session->userdata('customer_email') ?>">
                                                </div>

                                                <div class="input_area">
                                                    <label for="password" class="youpasswd" data-icon="p"> <i
                                                                class="fa fa-lock color5"></i></label>
                                                    <input type="password"
                                                           placeholder="<?php echo display('password') ?>"
                                                           name="password" required>
                                                </div>

                                                <div class="forgetpw">
                                                    <a href="#" class="btn btn-link" data-toggle="modal"
                                                       data-target="#forget_password"
                                                       data-dismiss="modal"><?php echo display('i_have_forgotten_my_password') ?></a>
                                                </div>

                                                <div class="login_btn">
                                                    <input data-dismiss="modal" type="button" style="background: red;"
                                                           value="<?php echo display('close') ?>">
                                                    <input type="submit" class="color2"
                                                           value="<?php echo display('login') ?>">
                                                </div>
                                                <div class="change_link">
                                                    <?php echo display('not_a_member_yet') ?>
                                                    <a href="#toregister"
                                                       class="to_register"><?php echo display('sign_up') ?></a>
                                                </div>
                                            </form>
                                        </div>


                                        <!-- =============register section================= -->
                                        <div id="register" class="animate form">
                                            <h2 class="box_heading color2"><?php echo display('sign_up') ?></h2>
                                            <form action="<?php echo base_url('user_signup') ?>" method="post">
                                                <div class="input_area">
                                                    <label for="usernamesignup" class="uname" data-icon="u"><i
                                                                class="fa fa-user color5"></i></label>
                                                    <input type="text" name="first_name"
                                                           placeholder="<?php echo display('first_name') ?>" required>
                                                </div>
                                                <div class="input_area">
                                                    <label for="emailsignup" class="youmail" data-icon="e"><i
                                                                class="fa fa-user color5"></i></label>
                                                    <input type="text" name="last_name"
                                                           placeholder="<?php echo display('last_name') ?>" required>
                                                </div>
                                                <div class="input_area">
                                                    <label for="phone" class="youmail" data-icon="e"><i
                                                                class="fa fa-phone color5"></i></label>
                                                    <input type="text" name="phone"
                                                           placeholder="<?php echo display('phone') ?>" required>
                                                </div>
                                                <div class="input_area">
                                                    <label for="passwordsignup" class="youpasswd" data-icon="p"><i
                                                                class="fa fa-envelope color5"></i></label>
                                                    <input type="email" name="email"
                                                           placeholder="<?php echo display('email') ?>" required>
                                                </div>
                                                <div class="input_area">
                                                    <label for="password" class="youpasswd" data-icon="p"><i
                                                                class="fa fa-lock color5"></i></label>
                                                    <input type="password" name="password"
                                                           placeholder="<?php echo display('password') ?>" required>
                                                </div>
                                                <div class="login_btn">
                                                    <input type="submit" class="color2"
                                                           value="<?php echo display('sign_up') ?>">
                                                </div>
                                                <div class="change_link">
                                                    <?php echo display('already_a_member') ?>
                                                    <a href="#tologin"
                                                       class="to_register"><?php echo display('login') ?></a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- =============forget password section start ===============-->
                        <div class="modal fade" id="forget_password" role="dialog" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel"><?php echo display('forget_password') ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="#<?php //echo base_url('forget_password')?>" method="post"
                                              class="form-inline">

                                            <div class="form-group row">
                                                <label for="email" class="uname col-sm-2" data-icon="u">
                                                    <i class="fa fa-envelope color5"></i>
                                                </label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control"
                                                           placeholder="<?php echo display('email') ?>"
                                                           name="forget_email" required id="forget_email">
                                                </div>
                                            </div>
                                            <div class="from-group">
                                                <button type="button" id="forget_password_btn"
                                                        class="btn btn-primary color2"><?php echo display('send') ?>

                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer" style="justify-content: center;">
                                        <div style="color: red; font-weight: bold;" id="recover_message"></div>
                                        <div id="loader"><img style="width: 2em; height: 2em;"
                                                              src="<?php echo base_url('my-assets/image/loader.gif') ?>"
                                                              alt=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <script>
                        // ===========prevent page reload and click to the submit in forget password section==============
                        $('#loader').hide();
                        $("#forget_email").keypress('keyup', function (event) {
                            if (event.keyCode == 13) {
                                event.preventDefault();
                                document.getElementById("forget_password_btn").click();
                                $("#recover_message").html('');
                                $('#loader').show();
                            }
                        });
                        //========================for recover email =======================
                        $(document).ready(function () {
                            $('#forget_password_btn').on('click', function () {
                                var forget_email = $("input[name=forget_email]").val();
                                $("#recover_message").html('');
                                $('#loader').show();
                                $.ajax({
                                    type: "post",
                                    async: true,
                                    dataType: "json",
                                    url: '<?php echo base_url('forget_password')?>',
                                    data: {email: forget_email},
                                    success: function (data) {
                                        $('#loader').hide();
                                        if (3 == data) {
                                            $("#recover_message").html("<?php echo display('this_email_not_exits')?>");
                                            $("input[name=forget_email]").css({"border-color": "red"});
                                            return false;
                                        }
                                        if (1 == data) {
                                            $("#recover_message").html("<?php echo display('you_have_receive_a_email_please_check_your_email')?>").css({"color": "green"});
                                            $("input[name=forget_email]").css({"border-color": "#dedede"});
                                        }
                                        if (2 == data) {
                                            $("#recover_message").html("<?php echo display('email_not_send')?>");
                                        }
                                        if (4 == data) {
                                            $("#recover_message").html("<?php echo display('please_try_again')?>");
                                        }
                                    }
                                });
                            });
                        });
                    </script>

                    <!--========= Cart Box start ====================-->
                    <div class="dropdown cart_area tab_up_cart" id="tab_up_cart">
                        <a href="#" class="dropdown-toggle color2" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="true" id="search-form">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-text"><?php echo(($position == 0) ? $currency . ' ' . number_format($this->cart->total(), 2, '.', ',') : number_format($this->cart->total(), 2, '.', ',') . ' ' . $currency) ?></span>
                            <span class="badge badge-notify my-cart-badge color4"><?php echo $this->cart->total_items() ?></span>
                        </a>
                        <ul class="cart-box dropdown-menu">
                            <li class="cart-header">
                                <h4><?php echo display('you_have') . ' ' . $this->cart->total_items() . ' ' . display('items_in_your_cart') ?></h4>
                            </li>

                            <?php
                            if ($this->cart->contents()) {
                                foreach ($this->cart->contents() as $items): ?>
                                    <li class="cart-content">
                                        <div class="img-box">
                                            <img src="<?php echo base_url().$items['options']['image'] ?>" alt="Awesome Image">
                                        </div>
                                        <div class="content">
                                            <h4><?php echo $items['name']; ?></h4>
                                            <h6><?php echo(($position == 0) ? $currency . ' ' . $this->cart->format_number($items['price']) : $this->cart->format_number($items['price']) . ' ' . $currency) ?></h6>
                                        </div>

                                        <div class="delete_box">
                                            <a href="#" class="delete_cart_item" name="<?php echo $items['rowid'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach;
                            } ?>
                            <?php if ($this->cart->contents()) { ?>
                                <li class="cart-footer clearfix">
                                    <div class="total-price">
                                        <h4><?php echo display('total_price') ?>:
                                            <span><?php echo(($position == 0) ? $currency . ' ' . number_format($this->cart->total(), 2, '.', ',') : number_format($this->cart->total(), 2, '.', ',') . ' ' . $currency) ?></span>
                                        </h4>
                                    </div>
                                    <div class="checkout-box">
                                        <a href="<?php echo base_url('checkout') ?>"><?php echo display('checkout') ?></a>
                                    </div>
                                    <!-- View cart -->
                                    <div class="checkout-box" style="margin-top: 5px">
                                        <a href="<?php echo base_url('view_cart') ?>"><?php echo display('view_cart') ?></a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!--========= Cart Box ENd =========-->
                </div>


                <!--========= Mobile Menu =========-->
                <div class="mobilemenu">
                    <div id="mySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" id="close">
                            <i class="fa fa-close"></i>
                        </a>
                        <ul class="nav flex-column">
                            <?php
                            if ($category_list) {
                                $i=1;
                                foreach ($category_list as $parent_category) {
                                    $sub_parent_cat =  $this->db->select('*')
                                        ->from('product_category')
                                        ->where('parent_category_id',$parent_category->category_id)
                                        ->order_by('menu_pos')
                                        ->get()
                                        ->result();
                                    if(9 == $i){
                                        break;
                                    }
                                    ?>

                                    <li class="menu-item menu-toggle">

                                        <a class="menu-link"  <?php if($sub_parent_cat){?> href="#" <?php }else{ ?>  href="<?php echo base_url('category/'.$parent_category->category_id)?>" <?php } ?>    ><?php echo $parent_category->category_name; if ($sub_parent_cat) {echo "<i class=\"fa fa-angle-down\"></i>";}?> </a>
                                        <?php if ($sub_parent_cat) { ?>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($sub_parent_cat as $sub_p_cat) {
                                                    $sub_category =  $this->db->select('*')
                                                        ->from('product_category')
                                                        ->where('parent_category_id',$sub_p_cat->category_id)
                                                        ->order_by('menu_pos')
                                                        ->get()
                                                        ->result();
                                                    ?>
                                                    <li class="menu-item menu-toggle">
                                                        <a class="menu-link" <?php if($sub_category){?>  href="#" <?php } else{ ?> href="<?php echo base_url('category/'.$sub_p_cat->category_id)?>" <?php } ?>><?php echo $sub_p_cat->category_name; if ($sub_category) {echo "<i class=\"fa fa-angle-down\"></i>";}?> </a>
                                                        <?php if ($sub_category) { ?>
                                                            <ul class="dropdown-menu">
                                                                <?php foreach ($sub_category as $sub_cat) { ?>
                                                                    <li><a href="<?php echo base_url('category/'.$sub_cat->category_id)?>"><?php echo $sub_cat->category_name?></a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>

                        </ul>
                    </div>
                </div>
                <!--======= ENd Mobile Menu =========-->
            </nav>
        </div>
    </div>

    <!-- header bottom start -->
    <div class="header-bottom color3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 hidden-md-down">
                    <div class="dropdown category_menu  <?php  if ($this->uri->segment(1) == '') {
                        echo "show";
                    } ?>">
                        <div class="dropdown-toggle menu_part color2" <?php if ($this->uri->segment(1) == 'category' || $this->uri->segment(1) == 'product_details' || $this->uri->segment(1) == 'category_product' || $this->uri->segment(1) == 'category_product_search' || $this->uri->segment(1) == 'checkout' || $this->uri->segment(1) == 'login' || $this->uri->segment(1) == 'signup' || $this->uri->segment(1) == 'about_us' || $this->uri->segment(1) == 'delivery_info' || $this->uri->segment(1) == 'privacy_policy' || $this->uri->segment(1) == 'terms_condition' || $this->uri->segment(1) == 'help' || $this->uri->segment(1) == 'contact_us' || $this->uri->segment(1) == 'view_cart') {
                            echo "data-toggle='dropdown'";
                        } ?>>
                            <span><?php echo display('category') ?></span><i class="fa fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu">
                            <ul>
                                <?php
                                if ($category_list) {
                                    $j=1;
                                    foreach ($category_list as $parent_category) {
                                        $sub_parent_cat = $this->db->select('*')
                                            ->from('product_category')
                                            ->where('parent_category_id', $parent_category->category_id)
                                            ->order_by('menu_pos')
                                            ->get()
                                            ->result();
                                        if(10 == $j){
                                            break;
                                        }
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url('category/p/'.remove_space($parent_category->category_name).'/' . $parent_category->category_id) ?>"
                                               class="<?php if ($sub_parent_cat) {
                                                   echo "cat_menu_link";
                                               } else {
                                                   echo "dropdown-item";
                                               } ?>">
                                                <i><img src="<?php echo base_url().$parent_category->cat_favicon ?>"
                                                        height="20"></i> <?php echo $parent_category->category_name ?>
                                            </a>
                                            <?php
                                            if ($sub_parent_cat) {
                                                ?>
                                                <div class="row m0 cat_sub_menu">
                                                    <?php
                                                    if ($sub_parent_cat) {
                                                        foreach ($sub_parent_cat as $parent_cat) {
                                                            ?>
                                                            <div class="col-sm-4">
                                                                <p>
                                                                    <a href="<?php echo base_url('category/p/'.remove_space($parent_cat->category_name).'/' . $parent_cat->category_id) ?>"><?php echo $parent_cat->category_name ?></a>
                                                                </p>
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
                                                                        <a href="<?php echo base_url('category/p/'.remove_space($s_p_cat->category_name ).'/' . $s_p_cat->category_id) ?>"><?php echo $s_p_cat->category_name ?></a>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        </li>
                                        <?php
                                        $j++;
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- category bar start -->
                    <div class="header-search">
                        <?php echo form_open('category_product_search') ?>
                        <input type="text" name="product_name" id="product_name" value="" required=""
                               placeholder="<?php echo display('search_product_name_here') ?>">
                        <button type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                        <div class="search_results scrollbar" id="style-1"></div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header bottom end -->
</header>
<!--===== End Header Area =======-->

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