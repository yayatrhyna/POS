<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Soft_settings');
$CI->load->model('Reports');
$CI->load->model('Users');
$Soft_settings = $CI->Soft_settings->retrieve_setting_editdata();
$users = $CI->Users->profile_edit_data();
$out_of_stock = $CI->Reports->out_of_stock_count();
//$store_wise_products = $CI->Reports->store_wise_product();
//dd($store_wise_products);

$store_wise_products_count = 0;
//if ($store_wise_products) {
//    foreach ($store_wise_products as $store_wise_product) {
//        $product = $store_wise_product['quantity'] - $store_wise_product['sell'];
//        if ($product < 10) {
//            $store_wise_products_count++;
//        }
//    }
//}
?>
<!-- Admin header end -->
<header class="main-header">
    <a href="<?php echo base_url('Admin_dashboard') ?>" class="logo"> <!-- Logo -->
        <span class="logo-mini">
            <!--<b>A</b>BD-->
            <img src="<?php if (isset($Soft_settings[0]['favicon'])) {
                echo base_url($Soft_settings[0]['favicon']);
            } ?>" alt="">
         </span>
        <span class="logo-lg">
            <!--<b>Admin</b>BD-->
            <img src="<?php if (isset($Soft_settings[0]['logo'])) {
                echo base_url($Soft_settings[0]['logo']);
            } ?>" alt="">
         </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top color2">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
            <span class="sr-only">Toggle navigation</span>
            <span class="pe-7s-keypad"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if ($this->session->userdata('user_type') == 4) {
                    $individual_store_wise_products = $CI->Reports->individual_store_wise_product();
                    $individual_store_wise_products_count = 0;
                    if ($individual_store_wise_products):
                        foreach ($individual_store_wise_products as $individual_store_wise_product):
                            $store_product = $individual_store_wise_product['quantity'] - $individual_store_wise_product['sell'];

                            if ($store_product < 10) {
                                $individual_store_wise_products_count++;
                            }
                        endforeach;
                    endif;

                    ?>
                    <!-- ================================================= -->
                    <li class="dropdown notifications-menu">
                        <a href="<?php echo base_url('Store_invoice/stock_report') ?>">
                            <i class="pe-7s-culture" title="<?php echo display('stock_report_store_wise') ?>"></i>
                            <span class="label label-danger"><?php echo $individual_store_wise_products_count ?></span>
                        </a>
                    </li>

                    <!-- settings -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('Admin_dashboard/edit_profile') ?>"><i
                                            class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                            <li><a href="<?php echo base_url('Admin_dashboard/change_password_form') ?>"><i
                                            class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                            <li><a href="<?php echo base_url('Admin_dashboard/logout') ?>"><i
                                            class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                        </ul>
                    </li>

                    <!-- ================================================================================== -->

                <?php } else {
                    ?>

<!--                    <li class="dropdown notifications-menu">-->
<!--                        <a href="--><?php //echo base_url('Creport/store_wise_product') ?><!--">-->
<!--                            <i class="pe-7s-culture" title="--><?php //echo display('stock_report_store_wise') ?><!--"></i>-->
<!--                            <span class="label label-danger">--><?php //echo $store_wise_products_count ?><!--</span>-->
<!--                        </a>-->
<!--                    </li>-->
                    <li class="dropdown notifications-menu">
                        <a target="_blank" href="<?php echo base_url() ?>">
                            <i class="pe-7s-home" title="<?php echo display('go_to_website') ?>"></i>

                        </a>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="<?php echo base_url('Creport/out_of_stock') ?>">
                            <i class="pe-7s-attention" title="<?php echo display('out_of_stock') ?>"></i>
                            <span class="label label-danger"><?php echo $out_of_stock ?></span>
                        </a>
                    </li>

                    <!-- settings -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url('Admin_dashboard/edit_profile') ?>"><i
                                            class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                            <li><a href="<?php echo base_url('Admin_dashboard/change_password_form') ?>"><i
                                            class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                            <li><a href="<?php echo base_url('Admin_dashboard/logout') ?>"><i
                                            class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>

<aside class="main-sidebar color1">
    <!-- sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel text-center">
            <div class="image">
                <?php if(!empty($users[0]['logo'])){?>
                <img src="<?=$users[0]['logo']?>" class="img-circle" alt="User Image">
                <?php }else {?>
                <img src="<?php echo base_url('my-assets/image/cartoon_face.png'); ?>" class="img-circle" alt="User Image">
                <?php } ?>
            </div>
            <div class="info">
                <p><?php echo $users[0]['first_name'] . " " . $users[0]['last_name'] ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> <?php echo display('online') ?></a>
            </div>
        </div>
        <!-- sidebar menu -->
        <ul class="sidebar-menu">

            <li class="<?php if ($this->uri->segment('1') == ("")) {
                echo "active";
            } else {
                echo " ";
            } ?>">
                <a href="<?php echo base_url('Admin_dashboard') ?>"><i class="ti-dashboard"></i>
                    <span><?php echo display('dashboard') ?></span>
                    <span class="pull-right-container">
                       <span class="label label-success pull-right"></span>
                   </span>
                </a>
            </li>

            <?php if ($this->session->userdata('user_type') == 1 || $this->session->userdata('user_type') == 2) { ?>

                <!-- Invoice menu start -->
                <li class="treeview <?php if ($this->uri->segment('2') == ("new_invoice") || $this->uri->segment('2') == ("manage_invoice") || $this->uri->segment('2') == ("invoice_update_form")|| $this->uri->segment('2') == ("invoice_inserted_data")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-layout-accordion-list"></i><span><?php echo display('sales') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(2) == 'new_invoice' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cinvoice/new_invoice') ?>"><?php echo display('new_sale') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_invoice' || ($this->uri->segment(2) == 'invoice_inserted_data') ? 'active':''))?>">
                            <a href="<?php echo base_url('Cinvoice/manage_invoice') ?>"><?php echo display('manage_sale') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'pos_invoice' || ($this->uri->segment(2) == 'pos_invoice') ? 'active':''))?>">
                            <a href="<?php echo base_url('Cinvoice/pos_invoice') ?>"><?php echo display('pos_sale') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Invoice menu end -->


                <!-- Order menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Corder")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-truck"></i><span><?php echo display('order') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(2) == 'new_order' ? 'active':''))?>"><a href="<?php echo base_url('Corder/new_order') ?>"><?php echo display('new_order') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_order' ? 'active':''))?>">
                            <a href="<?php echo base_url('Corder/manage_order') ?>"><?php echo display('manage_order') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Order menu end -->

                <!-- Product menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cproduct")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-bag"></i><span><?php echo display('product') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cproduct' && ($this->uri->segment(2) == '') ? 'active':''))?>">
                            <a href="<?php echo base_url('Cproduct') ?>"><?php echo display('add_product') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'add_product_csv' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cproduct/add_product_csv') ?>"><?php echo "Import Product" ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_product' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cproduct/manage_product') ?>"><?php echo display('manage_product') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'product_details_single' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cproduct/product_details_single') ?>"><?php echo display('product_ledger') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Product menu end -->

                <!-- Customer menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ccustomer")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="fa fa-handshake-o"></i><span><?php echo display('customer') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Ccustomer' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Ccustomer') ?>"><?php echo display('add_customer') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_customer' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccustomer/manage_customer') ?>"><?php echo display('manage_customer') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'customer_ledger_report' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccustomer/customer_ledger_report') ?>"><?php echo display('customer_ledger') ?></a>
                        </li>

                    </ul>
                </li>
                <!-- Customer menu end -->

                <!-- Supplier menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Csupplier")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-user"></i><span><?php echo display('supplier') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Csupplier' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Csupplier') ?>"><?php echo display('add_supplier') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_supplier' ? 'active':''))?>">
                            <a href="<?php echo base_url('Csupplier/manage_supplier') ?>"><?php echo display('manage_supplier') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'supplier_ledger_report' ? 'active':''))?>">
                            <a href="<?php echo base_url('Csupplier/supplier_ledger_report') ?>"><?php echo display('supplier_ledger') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Supplier menu end -->

                <!-- Purchase menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cpurchase")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-shopping-cart"></i><span><?php echo display('purchase') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cpurchase' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Cpurchase') ?>"><?php echo display('add_purchase') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_purchase' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cpurchase/manage_purchase') ?>"><?php echo display('manage_purchase') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Purchase menu end -->

                <!-- Category menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ccategory")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-tag"></i><span><?php echo display('category') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Ccategory' && ($this->uri->segment(2) == '')  ?  'active':''))?>"><a href="<?php echo base_url('Ccategory') ?>"><?php echo display('add_category') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'add_category_csv' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccategory/add_category_csv') ?>"><?php echo display('import_category_csv') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_category' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccategory/manage_category') ?>"><?php echo display('manage_category') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Category menu end -->

                <!-- Brand menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cbrand")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-apple"></i><span><?php echo display('brand') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cbrand' && ($this->uri->segment(2) == '') ? 'active':''))?>"><a href="<?php echo base_url('Cbrand') ?>"><?php echo display('add_brand') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_brand' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cbrand/manage_brand') ?>"><?php echo display('manage_brand') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Brand menu end -->


                <!-- Variant menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cvariant")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-wand"></i><span><?php echo display('variant') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cvariant' && ($this->uri->segment(2) == '') ? 'active':''))?>"><a href="<?php echo base_url('Cvariant') ?>"><?php echo display('add_variant') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_variant' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cvariant/manage_variant') ?>"><?php echo display('manage_variant') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Variant menu end -->

                <!-- Unit menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cunit")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-ruler-pencil"></i><span><?php echo display('Unit') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cunit' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Cunit') ?>"><?php echo display('add_unit') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_unit' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cunit/manage_unit') ?>"><?php echo display('manage_unit') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Unit menu end -->

                <!-- Gallery menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cgallery")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-gallery"></i><span><?php echo display('product_image_gallery') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cgallery' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Cgallery') ?>"><?php echo display('add_product_image') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_image' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cgallery/manage_image') ?>"><?php echo display('manage_product_image') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Gallery menu end -->

                <!-- Tax menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ctax")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-target"></i><span><?php echo display('tax') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(2) == 'tax_product_service' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ctax/tax_product_service') ?>"><?php echo display('tax_product_service') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_tax' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ctax/manage_tax') ?>"><?php echo display('manage_product_tax') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'tax_setting' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ctax/tax_setting') ?>"><?php echo display('tax_setting') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Tax menu end -->

                <!-- Currency menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ccurrency")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-money"></i><span><?php echo display('currency') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Ccurrency' && ($this->uri->segment(2) == '') ? 'active':''))?>"><a href="<?php echo base_url('Ccurrency') ?>"><?php echo display('add_currency') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_currency' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccurrency/manage_currency') ?>"><?php echo display('manage_currency') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Currency menu end -->

                <!-- Store set menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cstore")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-truck"></i><span><?php echo display('store') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Cstore' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Cstore') ?>"><?php echo display('store_add') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'add_store_csv' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cstore/add_store_csv') ?>"><?php echo display('import_store_csv') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_store' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cstore/manage_store') ?>"><?php echo display('manage_store') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'store_transfer' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cstore/store_transfer') ?>">Store Transfer</a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_store_product' ? 'active':''))?>">
                            <a href="<?php echo base_url('Cstore/manage_store_product') ?>"><?php echo display('manage_store_product') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Store set menu end -->



                <!-- Stock menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Creport")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-bar-chart"></i><span><?php echo display('stock') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Creport' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Creport') ?>"><?php echo display('stock_report') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'stock_report_supplier_wise' ? 'active':''))?>">
                            <a href="<?php echo base_url('Creport/stock_report_supplier_wise') ?>"><?php echo display('stock_report_supplier_wise') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'stock_report_product_wise' ? 'active':''))?>">
                            <a href="<?php echo base_url('Creport/stock_report_product_wise') ?>"><?php echo display('stock_report_product_wise') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'stock_report_store_wise' ? 'active':''))?>">
                            <a href="<?php echo base_url('Creport/stock_report_store_wise') ?>"><?php echo display('stock_report_store_wise') ?></a>
                        </li>

                    </ul>
                </li>
                <!-- Stock menu end -->

                <?php if ($this->session->userdata('user_type') == '1') { ?>

                    <!-- Bank menu start -->
                    <li class="treeview <?php if ($this->uri->segment('1') == ("Csettings")) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-briefcase"></i><span><?php echo display('settings') ?></span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?php echo (($this->uri->segment(1) == 'Csettings' && ($this->uri->segment(2) == '') ? 'active':''))?>"><a href="<?php echo base_url('Csettings') ?>"><?php echo display('add_new_bank') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'bank_list' ? 'active':''))?>">
                                <a href="<?php echo base_url('Csettings/bank_list') ?>"><?php echo display('manage_bank') ?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- Bank menu end -->


                    <!-- Accounts menu start -->
                    <li class="treeview <?php if ($this->uri->segment('1') == ("Caccounts")) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-pencil-alt"></i><span><?php echo display('accounts') ?></span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?php echo (($this->uri->segment(2) == 'create_account' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/create_account') ?>"><?php echo display('create_accounts') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'manage_account' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/manage_account') ?>"><?php echo display('manage_accounts') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(1) == 'Caccounts' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Caccounts') ?>"><?php echo display('received') ?></a></li>
                            <li class="<?php echo (($this->uri->segment(2) == 'outflow' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/outflow') ?>"><?php echo display('payment') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'summary' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/summary') ?>"><?php echo display('accounts_summary') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'cheque_manager' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/cheque_manager') ?>"><?php echo display('cheque_manager') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'closing' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/closing') ?>"><?php echo display('closing') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'closing_report' ? 'active':''))?>">
                                <a href="<?php echo base_url('Caccounts/closing_report') ?>"><?php echo display('closing_report') ?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- Accounts menu end -->

                    <!-- Report menu start -->
                    <li class="treeview <?php if ($this->uri->segment('2') == ("retrieve_dateWise_SalesReports") || $this->uri->segment('2') == ("todays_sales_report") || $this->uri->segment('2') == ("todays_purchase_report") || $this->uri->segment('2') == ("sales_report_store_wise")|| $this->uri->segment('2') == ("retrieve_sales_report_store_wise") || $this->uri->segment('2') == ("transfer_report") || $this->uri->segment('2') == ("product_sales_reports_date_wise") || $this->uri->segment('2') == ("total_profit_report") || $this->uri->segment('2') == ("retrieve_dateWise_profit_report") || $this->uri->segment('2') == ("tax_report_product_wise") || $this->uri->segment('2') == ("tax_report_invoice_wise") || $this->uri->segment('2') == ("store_to_store_transfer") || $this->uri->segment(2)== ('retrieve_dateWise_PurchaseReports')) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-book"></i><span><?php echo display('report') ?></span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?php echo (($this->uri->segment(2) == 'todays_sales_report' || ($this->uri->segment(2) == 'retrieve_dateWise_SalesReports')? 'active':''))?>">
                                <a href="<?php echo base_url('Admin_dashboard/todays_sales_report') ?>"><?php echo display('sales_report') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'sales_report_store_wise' || ($this->uri->segment(2) == 'retrieve_sales_report_store_wise') ? 'active':''))?>">
                                <a href="<?php echo base_url('Admin_dashboard/sales_report_store_wise') ?>"><?php echo display('sales_report_store_wise') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'todays_purchase_report' || $this->uri->segment(2)== ('retrieve_dateWise_PurchaseReports') ? 'active':''))?>">
                                <a href="<?php echo base_url('Admin_dashboard/todays_purchase_report') ?>"><?php echo display('purchase_report') ?></a>
                            </li>

                            <li class="<?php if ($this->uri->segment('2') == ("store_to_store_transfer") || $this->uri->segment('2') == ("store_to_warehouse_transfer") || $this->uri->segment('2') == ("warehouse_to_warehouse_transfer") || $this->uri->segment('2') == ("warehouse_to_store_transfer")) {
                                echo "active";
                            } else {
                                echo " ";
                            } ?>">
                                <a href="javascript:void(0)"><?php echo display('transfer_report') ?>
                                    <span class="pull-right-container"><i
                                                class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo (($this->uri->segment(2) == 'store_to_store_transfer' ? 'active':''))?>">
                                        <a href="<?php echo base_url('Admin_dashboard/store_to_store_transfer') ?>"><?php echo display('store_to_store_transfer') ?></a>
                                    </li>

                                </ul>
                            </li>

                            <!-- <li><a href="<?php //echo base_url('Admin_dashboard/total_profit_report') ?>"><?php //echo display('profit_report') ?></a></li> -->
                            <li class="<?php echo (($this->uri->segment(2) == 'tax_report_product_wise' ? 'active':''))?>">
                                <a href="<?php echo base_url('Admin_dashboard/tax_report_product_wise') ?>"><?php echo display('tax_report_product_wise') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'tax_report_invoice_wise' ? 'active':''))?>">
                                <a href="<?php echo base_url('Admin_dashboard/tax_report_invoice_wise') ?>"><?php echo display('tax_report_invoice_wise') ?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- Report menu end -->

                    <!-- pay with method menu start -->
                    <li class="treeview <?php if ($this->uri->segment('1') == ("Cpay_with")) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-settings"></i><span><?php echo display('pay_with') ?></span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">

                            <li class="<?php echo (($this->uri->segment(1) == 'Cpay_with' && ($this->uri->segment(2) == '')  ? 'active':''))?>">
                                <a href="<?php echo base_url('Cpay_with') ?>"><?php echo display('manage_pay_with') ?> </a>
                            </li>

                        </ul>
                    </li>
                    <!-- pay with method menu end -->

                    <!-- Website Settings menu start -->
                    <li class="treeview <?php if ($this->uri->segment('1') == ("Cblock") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Cblock") || $this->uri->segment('1') == ("Cproduct_review") || $this->uri->segment('1') == ("Csubscriber") || $this->uri->segment('1') == ("Cwishlist") || $this->uri->segment('1') == ("Cweb_footer") || $this->uri->segment('1') == ("Clink_page") || $this->uri->segment('1') == ("Ccoupon") || $this->uri->segment('1') == ("Cabout_us") || $this->uri->segment('1') == ("Cour_location") || $this->uri->segment('1') == ("Cshipping_method")) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-settings"></i><span><?php echo display('web_settings') ?></span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?php echo (($this->uri->segment(2) == 'add_slider' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cweb_setting/add_slider') ?>"><?php echo display('slider') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'submit_add' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cweb_setting/submit_add') ?>"><?php echo display('advertisement') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cblock' ? 'active':''))?>"><a href="<?php echo base_url('Cblock') ?>"><?php echo display('block') ?> </a></li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cproduct_review' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cproduct_review') ?>"><?php echo display('product_review') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Csubscriber' ? 'active':''))?>"><a href="<?php echo base_url('Csubscriber') ?>"><?php echo display('subscriber') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cwishlist' ? 'active':''))?>"><a href="<?php echo base_url('Cwishlist') ?>"><?php echo display('wishlist') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cweb_footer' ? 'active':''))?>"><a href="<?php echo base_url('Cweb_footer') ?>"><?php echo display('web_footer') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Clink_page' ? 'active':''))?>"><a href="<?php echo base_url('Clink_page') ?>"><?php echo display('link_page') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Ccoupon' ? 'active':''))?>"><a href="<?php echo base_url('Ccoupon') ?>"><?php echo display('coupon') ?> </a></li>

                            <li class="<?php echo (($this->uri->segment(2) == 'manage_contact_form' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cweb_setting/manage_contact_form') ?>"><?php echo display('contact_form') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cabout_us' ? 'active':''))?>"><a href="<?php echo base_url('Cabout_us') ?>"><?php echo display('about_us') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cour_location' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cour_location') ?>"><?php echo display('our_location') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(1) == 'Cshipping_method' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cshipping_method') ?>"><?php echo display('shipping_method') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(2) == 'setting' ? 'active':''))?>">
                                <a href="<?php echo base_url('Cweb_setting/setting') ?>"><?php echo display('setting') ?> </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Website Settings menu end -->

                    <!-- Software Settings menu start -->
                    <li class="treeview <?php if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Csoft_setting") || $this->uri->segment('1') == ("Language")) {
                        echo "active";
                    } else {
                        echo " ";
                    } ?>">
                        <a href="#">
                            <i class="ti-settings"></i><span><?php echo display('software_settings') ?></span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?php echo (($this->uri->segment(2) == 'manage_company' ? 'active':''))?>">
                                <a href="<?php echo base_url('Company_setup/manage_company') ?>"><?php echo display('manage_company') ?></a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(1) == 'User' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('User') ?>"><?php echo display('add_user') ?></a></li>
                            <li class="<?php echo (($this->uri->segment(2) == 'manage_user' ? 'active':''))?>">
                                <a href="<?php echo base_url('User/manage_user') ?>"><?php echo display('manage_users') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(1) == 'Language' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Language') ?>"><?php echo display('language') ?> </a></li>
                            <li class="<?php echo (($this->uri->segment(2) == 'color_setting_frontend' ? 'active':''))?>">
                                <a href="<?php echo base_url('Csoft_setting/color_setting_frontend') ?>"><?php echo display('color_setting_frontend') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'color_setting_backend' ? 'active':''))?>">
                                <a href="<?php echo base_url('Csoft_setting/color_setting_backend') ?>"><?php echo display('color_setting_backend') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(2) == 'email_configuration' ? 'active':''))?>">
                                <a href="<?php echo base_url('Csoft_setting/email_configuration') ?>"><?php echo display('email_configuration') ?> </a>
                            </li>

                            <li class="<?php echo (($this->uri->segment(2) == 'payment_gateway_setting' ? 'active':''))?>">
                                <a href="<?php echo base_url('Csoft_setting/payment_gateway_setting') ?>"><?php echo display('payment_gateway_setting') ?> </a>
                            </li>
                            <li class="<?php echo (($this->uri->segment(1) == 'Csoft_setting' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Csoft_setting') ?>"><?php echo display('setting') ?> </a>
                            </li>
                            
                        </ul>
                    </li>
<!--                    <li><i class="fa fa-cloud-download" aria-hidden="true"></i><span><a href="--><?php //echo base_url('autoupdate') ?><!--">--><?php //echo display('update') ?><!-- </a></span></li>-->
                <?php }
            } ?>

            <?php if ($this->session->userdata('user_type') == '4') { ?> 
                <!-- user_type = 4 = store keeper -->

                <!-- Invoice menu start -->
                <li class="treeview <?php if ($this->uri->segment('2') == ("new_invoice") || $this->uri->segment('2') == ("manage_invoice") || $this->uri->segment('2') == ("invoice_update_form")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-layout-accordion-list"></i><span><?php echo display('invoice') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(2) == 'new_invoice' ? 'active':''))?>">
                            <a href="<?php echo base_url('Store_invoice/new_invoice') ?>"><?php echo display('new_invoice') ?></a>
                        </li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_invoice' ? 'active':''))?>">
                            <a href="<?php echo base_url('Store_invoice/manage_invoice') ?>"><?php echo display('manage_invoice') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Invoice menu end -->

                <!-- POS invoice menu start -->
                <li class="treeview <?php if ($this->uri->segment('2') == ("pos_invoice")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="<?php echo base_url('Store_invoice/pos_invoice') ?>" target="_blank">
                        <i class="ti-layout-tab-window"></i><span><?php echo display('pos_invoice') ?></span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
                <!-- POS invoice menu end -->

                <!-- Product menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cproduct")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="ti-bag"></i><span><?php echo display('product') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_product' ? 'active':''))?>">
                            <a href="<?php echo base_url('store_keeper/Cproduct/manage_product') ?>"><?php echo display('manage_product') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Product menu end -->

                <!-- Customer menu start -->
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ccustomer")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="#">
                        <i class="fa fa-handshake-o"></i><span><?php echo display('customer') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo (($this->uri->segment(1) == 'Ccustomer' && ($this->uri->segment(2) == '')  ? 'active':''))?>"><a href="<?php echo base_url('Ccustomer') ?>"><?php echo display('add_customer') ?></a></li>
                        <li class="<?php echo (($this->uri->segment(2) == 'manage_customer' ? 'active':''))?>">
                            <a href="<?php echo base_url('Ccustomer/manage_customer') ?>"><?php echo display('manage_customer') ?></a>
                        </li>
                    </ul>
                </li>
                <!-- Customer menu end -->

                <!-- Store Stock -->
                <li class="treeview <?php if ($this->uri->segment('2') == ("stock_report")) {
                    echo "active";
                } else {
                    echo " ";
                } ?>">
                    <a href="<?php echo base_url('Store_invoice/stock_report') ?>" target="_blank">
                        <i class="ti-layout-tab-window"></i><span><?php echo display('stock') ?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                </li>
                <!-- Store stock end -->
            <?php } ?>
        </ul>
    </div> <!-- /.sidebar -->
</aside>