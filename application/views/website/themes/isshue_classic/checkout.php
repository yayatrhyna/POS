<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- /.End of header -->
<?php
$CI =& get_instance();
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
?>
<link href="<?php echo base_url('application/views/website/themes/' . $theme . '/assets/website/css/custom.css') ?>"
      rel="stylesheet">


<div class="checkout">
    <nav aria-label="breadcrumb" style="background: #eceeef;">
        <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><?php echo display('home') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo display('checkout') ?></li>
        </ol>
        </div>
    </nav>

    <!-- /.End of page breadcrumbs -->
    <?php if ($this->cart->contents()) { ?>
        <form action="<?php echo base_url('submit_checkout') ?>" id="validateForm" method="post"
              class="checkout-conent">
            <div class="container">
                <h1><?php echo display('checkout') ?></h1>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <i class="fa fa-question-circle"></i> <?php echo display('returning_customer') ?>
                                        <a
                                                data-toggle="collapse"
                                                data-parent="#accordion"
                                                href="#collapseOne"> <?php echo display('click_here_to_login') ?> </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p> <?php echo display('if_you_have_shopped_with_us') ?></p>
                                        <div class="row">
                                            <?php
                                            if ($this->user_auth->is_logged() != 1) {
                                                ?>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"
                                                               for="login_email"><?php echo display('email') ?><abbr
                                                                    class="required" title="required">*</abbr></label>
                                                        <input type="text" id="login_email" class="form-control"
                                                               name="user_email">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label" for="login_password"><?php echo
                                                            display('password'); ?>
                                                            <abbr class="required" title="required">*</abbr></label>
                                                        <input type="password" id="login_password" class="form-control"
                                                               name="login_password"
                                                               value="<?php echo get_cookie("password"); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="checkbox checkbox-success">
                                                        <input id="remember_me" type="checkbox" name="remember_me"
                                                               value="1">
                                                        <label for="remember_me"><?php echo display('remember_me') ?></label>
                                                    </div>
                                                    <a href="#"
                                                       class="btn btn-warning customer_login"><?php echo display('login') ?></a>
                                                    <a href="#"
                                                       class="lost-pass"><?php echo display('i_have_forgotten_my_password') ?></a>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-12">
                                                    <a href="<?php echo base_url('logout') ?>"
                                                       class="btn btn-danger"><?php echo display('logout') ?></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer login by ajax start-->
                                <script type="text/javascript">
                                    $('body').on('click', '.customer_login', function () {
                                        let login_email = $('#login_email').val();
                                        let login_password = $('#login_password').val();
                                        let remember_me = $('#remember_me').val();

                                        if (login_email === 0 || login_password === 0) {
                                            Swal({
                                                type: 'warning',
                                                title: '<?php echo display('please_type_email_and_password')?>'
                                            });
                                            return false;
                                        }
                                        $.ajax({
                                            type: "post",
                                            async: true,
                                            url: '<?php echo base_url('website/customer/Login/checkout_login')?>',
                                            data: {
                                                login_email: login_email,
                                                login_password: login_password,
                                                remember_me: remember_me
                                            },
                                            success: function (data) {
                                                if (data === 'true') {
                                                    swal("<?php echo display('login_successfully')?> ", "", "success");
                                                    location.reload();
                                                } else {
                                                    swal("<?php echo display('wrong_username_or_password')?> ", "", "warning");
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
                            </div>
                        </div>
                        <div class="billing-form">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="billing-title"><?php echo display('billing_address') ?></h3>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group has-error">
                                        <label class="control-label" for="first_name"><?php echo display('first_name')
                                            ?><abbr class="required" title="required">*</abbr></label>
                                        <input type="text" id="first_name" class="form-control" name="first_name"
                                               placeholder="<?php echo display('first_name') ?>" value="<?php echo
                                        $this->session->userdata('first_name') ?>" required>
                                        <!--                                        <span class="help-block">First Name must be between 1 and 32 characters!</span>-->
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="last_name"><?php echo display('last_name')
                                            ?> <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" id="last_name" class="form-control" name="last_name"
                                               value="<?php echo $this->session->userdata('last_name') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="company"><?php echo display('company') ?></label>
                                        <input type="text" id="company" class="form-control" name="company"
                                               placeholder="<?php echo display('company') ?>"
                                               value="<?php echo $this->session->userdata('company') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="country"><?php echo display('country') ?><abbr
                                                    class="required" title="required">*</abbr> : </label>
                                        <select name="country" id="country" class="form-control form-input custom_select" required>
                                            <option value=""> --- <?php echo display('select_category') ?> ---</option>
                                            <?php
                                            if ($selected_country_info) {
                                                foreach ($selected_country_info as $country) {
                                                    ?>
                                                    <option value="<?php echo $country->id ?>"><?php echo $country->name ?> </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="email"><?php echo display('customer_email') ?>
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" id="customer_email" name="customer_email"
                                               class="form-control"
                                               placeholder="<?php echo display('customer_email') ?>"
                                               required
                                               value="<?php echo $this->session->userdata('customer_email') ?>">
                                    </div>
                                    <?php
                                    if ($this->user_auth->is_logged() != 1) {
                                        ?>

                                        <div class="form-group">
                                            <label class="control-label" for="ac_pass"><?php echo display('password') ?>
                                                <abbr class="required" title="required">*</abbr></label>
                                            <input type="password" name="ac_pass" id="ac_pass"
                                                   placeholder="<?php echo display('password') ?>" class="form-control"
                                                   required
                                                   value="<?php echo $this->session->userdata('ac_pass') ?>">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="customer_address_1"
                                               class="control-label"><?php echo display('customer_address_1') ?><abbr
                                                    class="required" title="required">*</abbr> : </label>
                                        <input type="text" placeholder="<?php echo display('customer_address_1') ?>"
                                               name="customer_address_1" id="customer_address_1" class="form-control"
                                               required
                                               value="<?php echo $this->session->userdata('customer_address_1') ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer_address_2"
                                               class="control-label"><?php echo display('customer_address_2') ?>
                                            : </label>
                                        <input type="text" name="customer_address_2" id="customer_address_2"
                                               placeholder="<?php echo display('customer_address_2') ?>"
                                               class="form-control"
                                               value="<?php echo $this->session->userdata('customer_address_2') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="city" class="control-label"><?php echo display('city') ?> <abbr
                                                    class="required" title="required">*</abbr>:</label>
                                        <input type="text" name="city" id="city"
                                               placeholder="<?php echo display('city') ?>"
                                               class="form-control" required
                                               value="<?php echo $this->session->userdata('city') ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="state"><?php echo display('state') ?> <abbr
                                                    class="required" title="required">*</abbr>: </label>
                                        <select name="state" id="state" class="form-control form-input custom_select" required>
                                            <option value=""> --- <?php echo display('select_state') ?> ---</option>
                                            <?php
                                            if ($state_list) {
                                                foreach ($state_list as $state) {
                                                    ?>
                                                    <option value="<?php echo $state->name ?>" <?php if ($this->session->userdata('state') == $state->name) {
                                                        echo "selected";
                                                    } ?>><?php echo $state->name ?> </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>


                                    <div class="form-group">
                                        <label for="zip" class="control-label"><?php echo display('zip') ?>
                                            :</label>
                                        <input type="text" name="zip" id="zip"
                                               placeholder="<?php echo display('zip') ?>" class="form-control"
                                                value="<?php echo $this->session->userdata('zip') ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"
                                               for="phone"><?php echo display('customer_mobile') ?> <abbr
                                                    class="required" title="required">*</abbr></label>
                                        <input type="text" id="customer_mobile" class="form-control"
                                               name="customer_mobile"
                                               placeholder="<?php echo display('customer_mobile') ?>" required
                                               value="<?php echo $this->session->userdata('customer_mobile') ?>"
                                               data-toggle="tooltip" data-placement="bottom"
                                               title="<?php echo display('add_country_code') ?>">
                                        <span class="color5"
                                              style="padding: 0.3em; font-size: 0.8em;"><?php echo display('add_country_code') ?></span>

                                    </div>


                                </div>
                            </div>


                            <div class="checkbox checkbox-success">
                                <input type="checkbox" id="privacy_policy" name="privacy_policy" value="1"
                                       required="" <?php if ($this->session->userdata('privacy_policy') == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="privacy_policy"><?php echo display('privacy_policy') ?></label>
                                <a href="<?php echo base_url('privacy_policy') ?>" target="_blank"><i
                                            class="fa fa-external-link"></i></a>
                            </div>
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" id="diff_ship_adrs" name="diff_ship_adrs" value="1"
                                       <?php if ($this->session->userdata('diff_ship_adrs') == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="diff_ship_adrs"
                                       data-target="#billind-different-address" aria-expanded="false"><?php echo
                                    display('ship_to_a_different_address')
                                    ?></label>

                            </div>

                            <div class="-collapse <?php if ($this->session->userdata('diff_ship_adrs') == 1) {
                                echo "in";
                            } ?>" id="billind-different-address">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group has-error">
                                            <label class="control-label"
                                                   for="ship_first_name"><?php echo display('first_name') ?> <abbr
                                                        class="required" title="required">*</abbr></label>
                                            <input type="text" name="ship_first_name" id="ship_first_name"
                                                   placeholder="<?php echo display('first_name') ?>"
                                                   class="form-control"
                                                   required
                                                   value="<?php echo $this->session->userdata('ship_first_name') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="ship_last_name"><?php echo display('last_name') ?><abbr
                                                        class="required" title="required">*</abbr></label>
                                            <input type="text" name="ship_last_name" id="ship_last_name"
                                                   placeholder="<?php echo display('last_name') ?>" class="form-control"
                                                   required
                                                   value="<?php echo $this->session->userdata('ship_last_name') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="c_name2"><?php echo display('company') ?></label>
                                            <input type="text" name="ship_company" id="ship_company"
                                                   placeholder="<?php echo display('company') ?>" class="form-control"
                                                   value="<?php echo $this->session->userdata('ship_company') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ship_country"
                                                   class="control-label"><?php echo display('country') ?>
                                                <abbr class="required" title="required">*</abbr> : </label>
                                            <select name="ship_country" id="ship_country"
                                                    class="form-control form-input custom_select">
                                                <option value=""> --- <?php echo display('select_country') ?>---
                                                </option>
                                                <?php
                                                if ($selected_country_info) {
                                                    foreach ($selected_country_info as $country) {
                                                        ?>
                                                        <option value="<?php echo $country->id ?>"><?php echo $country->name ?> </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="ship_customer_email"><?php echo display('customer_email') ?>
                                                <abbr
                                                        class="required" title="required">*</abbr></label>
                                            <input type="text" id="ship_customer_email" name="ship_customer_email"
                                                   class="form-control"
                                                   placeholder="<?php echo display('customer_email') ?>" required
                                                   value="<?php echo $this->session->userdata('ship_customer_email') ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="ship_address_1"
                                                   class="control-label"><?php echo display('customer_address_1') ?>
                                                <abbr
                                                        class="required" title="required">*</abbr> :</label>
                                            <input type="text" name="ship_address_1" id="ship_address_1"
                                                   placeholder="<?php echo display('customer_address_1') ?>"
                                                   class="form-control" required
                                                   value="<?php echo $this->session->userdata('ship_address_1') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ship_address_2"
                                                   class="control-label"><?php echo display('customer_address_2') ?>
                                                :</label>
                                            <input type="text" name="ship_address_2" id="ship_address_2"
                                                   placeholder="<?php echo display('customer_address_2') ?>"
                                                   class="form-control"
                                                   value="<?php echo $this->session->userdata('ship_address_2') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ship_city" class="control-label"><?php echo display('city') ?>
                                                <abbr
                                                        class="required" title="required">*</abbr> :</label>
                                            <input type="text" name="ship_city" id="ship_city" class="form-control"
                                                   placeholder="<?php echo display('city') ?>" required
                                                   value="<?php echo $this->session->userdata('ship_city') ?>">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="ship_state"><?php echo display('state') ?>
                                                <abbr class="required" title="required">*</abbr> :</label>
                                            <select name="ship_state" id="ship_state" class="form-control form-input custom_select">
                                                <option value=""> --- <?php echo display('state') ?> ---</option>
                                                <?php
                                                if ($ship_state_list) {
                                                    foreach ($ship_state_list as $ship_state) {
                                                        ?>
                                                        <option value="<?php echo $ship_state->name ?>" <?php if ($this->session->userdata('ship_state') == $ship_state->name) {
                                                            echo "selected";
                                                        } ?>><?php echo $ship_state->name ?> </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select></div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="ship_zip"
                                                           class="control-label"><?php echo display('zip') ?>
                                                        :</label>
                                                    <input type="text" name="ship_zip" id="ship_zip"
                                                           placeholder="<?php echo display('zip') ?>"
                                                           class="form-control"
                                                           value="<?php echo $this->session->userdata('ship_zip') ?>">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="ship_mobile"
                                                           class="control-label"><?php echo display('mobile') ?> <abbr
                                                                class="required" title="required">*</abbr> :</label>
                                                    <input type="text" name="ship_mobile" id="ship_mobile"
                                                           placeholder="<?php echo display('mobile') ?>"
                                                           class="form-control" required
                                                           value="<?php echo $this->session->userdata('ship_mobile') ?>"
                                                           data-toggle="tooltip" data-placement="bottom"
                                                           title="<?php echo display('add_country_code') ?>"><span
                                                            class="color5"
                                                            style="padding: 0.3em; font-size: 0.8em;"><?php echo display('add_country_code') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label"
                                       for="ordre_notes"><?php echo display('add_coment_about_your_order') ?></label>
                                <textarea class="form-control" id="ordre_notes" rows="5" placeholder="<?php echo
                                display('notes_about_your_order') ?>"><?php echo
                                    $this->session->userdata('delivery_details') ?></textarea>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <h3 class="text-danger"><span id="coupon_error"></span></h3>
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="fa fa-question-circle"></i><?php echo display('use_coupon_code') ?> <a
                                            data-toggle="collapse" data-parent="#accordion"
                                            href="#collapseThree"><?php echo display('enter_your_coupon_here') ?></a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body coupon">
                                    <div class="form-inline">
                                        <input type="text" class="form-control" id="coupon_code" name="coupon_code"
                                               placeholder="<?php
                                               echo display('enter_your_coupon_here') ?>" required="">
                                        <a href="#" class="btn color2 text-white"
                                           id="coupon_value"><?php echo display('apply_coupon') ?></a>

                                    </div>
                                </div>
                            </div>

                            <!--======= End coupon area ======-->
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="check-orde">
                            <h4>Your order</h4>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="product-name"><?php echo display('product') ?></th>
                                    <th class="product-total text-right"><?php echo display('total') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;
                                $cgst = 0;
                                $sgst = 0;
                                $igst = 0;
                                $discount = 0;
                                $coupon_amnt = 0; ?>
                                <?php

                                foreach ($this->cart->contents() as $items):
                                    ?>
                                    <?php echo form_hidden($i . '[rowid]', $items['rowid']);

                                    if (!empty($items['options']['cgst'])) {
                                        $cgst = $cgst + ($items['options']['cgst'] * $items['qty']);
                                    }

                                    if (!empty($items['options']['sgst'])) {
                                        $sgst = $sgst + ($items['options']['sgst'] * $items['qty']);
                                    }

                                    if (!empty($items['options']['igst'])) {
                                        $igst = $igst + ($items['options']['igst'] * $items['qty']);
                                    }

                                    //Calculation for discount
                                    if (!empty($items['discount'])) {
                                        $discount = $discount + ($items['discount'] * $items['qty']) + $this->session->userdata('coupon_amnt');
                                        $this->session->set_userdata('total_discount', $discount);
                                    }
                                    ?>
                                    <tr class="cart_item">
                                        <td class="product-name"><?php echo $items['name']; ?>
                                            <strong class="product-sum">× <?php echo $items['qty']; ?></strong>
                                        </td>
                                        <td class="product-total text-right">
                                            <span class="woocommerce-Price-amount amount">
                                                <span class="woocommerce-Price-currencySymbol"><?php echo(($position == 0) ? $currency . " " . $this->cart->format_number($items['price'] * $items['qty']) : $this->cart->format_number($items['actual_price'] * $items['qty']) . " " . $currency) ?></span>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>

                                </tbody>
                                <tfoot>
                                <!--                                <tr>-->
                                <!--                                    <td colspan="6" class="text-right">-->
                                <!--                                        <strong>-->
                                <?php //echo display('total_discount') ?><!--:</strong></td>-->
                                <!--                                    <td class="text-right">-->
                                <?php //echo(($position == 0) ? $currency . " " . number_format($discount, 2, '.', ',') : number_format($discount, 2, '.', ',') . " " . $currency) ?><!--</td>-->
                                <!--                                </tr>-->
                                <?php
                                $total_tax = $cgst + $sgst + $igst;
                                if ($total_tax > 0) {
                                    ?>
                                    <tr>
                                        <td class="total_tax">
                                            <?php echo display('total_tax') ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            $total_tax = $cgst + $sgst + $igst;
                                            $this->_cart_contents['total_tax'] = $total_tax;
                                            echo(($position == 0) ? $currency . " " . number_format($total_tax, 2, '.', ',') : number_format($total_tax, 2, '.', ',') . " " . $currency) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                //                            if ($this->_cart_contents['cart_ship_name']) {
                                ?>
                                <tr id="shipCostRow">
                                    <td class="cart_ship_name">
                                        <span id="set_cart_ship_name"></span>
                                    </td>
                                    <td class="text-right">

                                        <?php
                                        //                                        $total_ship_cost = $this->_cart_contents['cart_ship_cost'];
                                        //                                        $this->session->set_userdata('cart_ship_cost', $total_ship_cost);
                                        //                                        echo(($position == 0) ? $currency . " " . number_format($total_ship_cost, 2, '.', ',') : number_format($total_ship_cost, 2, '.', ',') . " " . $currency);
                                        echo(($position == 0) ? $currency . ' <span id="set_ship_cost"></span> '
                                            : '<span id="set_ship_cost"></span>' . $currency);
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                //                            }
                                //                            $coupon_amnt = $this->session->userdata('coupon_amnt');
                                //                            if ($coupon_amnt > 0) {
                                ?>
                                <tr id="couponAmountRow">
                                    <td class="coupon_discount">
                                        <strong><?php echo display('coupon_discount') ?>:</strong>
                                    </td>
                                    <td class="text-right">
                                        <span id="set_coupon_price"></span>
                                        <?php
                                        //                                        if ($coupon_amnt > 0) {
                                        //                                            echo(($position == 0) ? $currency . " " . number_format($coupon_amnt, 2, '.', ',') : number_format($coupon_amnt, 2, '.', ',') . " " . $currency);
                                        //                                        }
                                        echo(($position == 0) ? $currency . ' <span id="set_coupon_price"></span> '
                                            : '<span id="set_coupon_price"></span>' . $currency);
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                //                            }
                                ?>
                                <tr>
                                    <td class="text-right"><strong><?php echo display('total') ?>
                                            :</strong></td>
                                    <td class="text-right">
                                        <strong>
                                            <?php
                                            $cart_total = $this->cart->total() + $total_tax;

                                            //                                        $cart_total = $this->cart->total() + $this->_cart_contents['cart_ship_cost']
                                            //                                            + $total_tax - $coupon_amnt;
                                            //
                                            //                                        $this->session->set_userdata('cart_total', $cart_total);
                                            //
                                            //                                        $total_amnt = $this->_cart_contents['cart_total'] = $cart_total;
                                            //
                                            //                                        echo(($position == 0) ? $currency . " " . number_format($total_amnt, 2, '.', ',') : number_format($total_amnt, 2, '.', ',') . " " . $currency);
                                            //                                        ?>
                                            <span id="total_amount"></span>
                                            <input type="hidden" name="order_total_amount" id="order_total_amount">
                                        </strong>
                                        <input type="hidden" name="cart_total_amount" id="cart_total_amount"
                                               value="<?php echo $cart_total; ?>">


                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <!-- /.End of product list table -->
                            <hr>
                            <h3><?php echo display('shipping_method') ?></h3>
                            <p><?php echo display('kindly_select_the_preferred_shipping_method_to_use_on_this_order') ?></p>
                            <?php
                            if ($select_shipping_method) {
                                foreach ($select_shipping_method as $shipping_method) {
                                    ?>
                                    <p><strong><?php echo $shipping_method->method_name ?></strong></p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" class="shipping_cost completed" name="shipping_cost"
                                                   id="<?php echo $shipping_method->method_id ?>"
                                                   value="<?php echo $shipping_method->charge_amount ?>"
                                                   alt="<?php echo $shipping_method->details ?>" <?php if ($this->session->userdata('method_id') == $shipping_method->method_id) {
                                                echo "checked";
                                            } ?>>
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

                                            <?php echo $shipping_method->details ?> -

                                            <?php
                                            if ($target_con_rate > 1) {
                                                $price = $shipping_method->charge_amount * $target_con_rate;
                                                echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                            }

                                            if ($target_con_rate <= 1) {
                                                $price = $shipping_method->charge_amount * $target_con_rate;
                                                echo(($position1 == 0) ? $currency1 . " " . number_format($price, 2, '.', ',') : number_format($price, 2, '.', ',') . " " . $currency1);
                                            }
                                            ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <hr>
                            <h3><?php echo display('payment_method') ?></h3>

                            <div class="payment-block" id="payment">
                                <!-- Cash on delivery payment method -->
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_method" required
                                               value="1" <?php if ($this->session->userdata('payment_method') == 1) {
                                            echo "checked ='checked'";
                                        } ?> checked>
                                        
                                        <?php echo display('cash_on_delivery') ?>
                                    </label>
                                </div>

                                <?php
                                if ($bitcoin_status == 1) {
                                    ?>
                                    <!-- Bit coin payment method -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method"
                                                   value="3" <?php if ($this->session->userdata('payment_method') == 3) {
                                                echo "checked = 'checked'";
                                            } ?>>
                                            <img src="<?php echo base_url('my-assets/image/bitcoin.png') ?>" alt="bitcoin
                                         image">
                                            <!-- <?php echo display('bitcoin') ?> -->
                                        </label>
                                    </div>
                                <?php } ?>

                                <?php
                                if ($payeer_status == 1) {
                                    ?>
                                    <!-- Payeer payment method -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method"
                                                   value="4" <?php if ($this->session->userdata('payment_method') == 4) {
                                                echo "checked = 'checked'";
                                            } ?>>
                                            <img src="<?php echo base_url('my-assets/image/payeer.png') ?>">
                                        </label>
                                    </div>
                                <?php } ?>
                                <?php
                                if ($paypal_status == 1) {
                                    ?>
                                    <!-- Paypal payment method -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method"
                                                   value="5" <?php if ($this->session->userdata('payment_method') == 5) {
                                                echo "checked = 'checked'";
                                            } ?>>
                                            <img src="<?php echo base_url('my-assets/image/paypal.png') ?>" alt="paypal
                                        image">
                                        </label>
                                    </div>
                                <?php } ?>
                                <?php
                                if ($sslcommerz_status == 1) {
                                    ?>
                                    <!-- Paypal payment method -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method"
                                                   value="6" <?php if ($this->session->userdata('payment_method') == 6) {
                                                echo "checked = 'checked'";
                                            } ?>>
                                            <img style="padding-left: 5px;" src="<?php echo base_url('my-assets/image/sslcommerz.png') ?>" alt="sslcommerz
                                        image">
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- /.End of payment method -->
                            <button type="submit"
                                    class="btn btn-warning btn-block"
                                    id="payment_method_sumbmit"><?php echo display('confirm_order') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <div class="container-fluid text-center" style="background: #fff;padding-top: 1em;">
            <img style="width: 10em; height: 8em;" src="<?php echo base_url() ?>assets/website/image/oops.png"
                 alt="oops image">
            <h1 class="text-center" style="margin: 2em;"><?php echo display('oops_your_cart_is_empty')
                ?></h1>
            <a href="<?php echo base_url() ?>" class="base_button btn btn-success"
               style="margin:
                         1em;"><?php echo display
                ('got_to_shop_now') ?></a>
        </div>
    <?php } ?>

</div>
<!-- Latest compiled and minified JavaScript -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
<!-- Retrive district ajax code start-->
<script type="text/javascript">

    //Retrive district
    $('body').on('change', '#country', function () {
        let country_id = $('#country').val();
        if (country_id === 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('please_select_country')?>'
            });
            return false;
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/retrive_district')?>',
            data: {country_id: country_id},
            success: function (data) {
                if (data) {
                    $("#state").html(data);
                } else {
                    $("#state").html('<p style="color:red"><?php echo display('failed')?></p>');
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                });

            }
        });
    });
</script>
<!-- Retrive district ajax code end-->

<!-- Retrive shipping district ajax code start-->
<script type="text/javascript">
    //Retrive district
    $('body').on('change', '#ship_country', function () {
        var country_id = $('#ship_country').val();
        if (country_id === 0) {

            Swal({
                type: 'warning',
                title: '<?php echo display('please_select_country')?>'
            });
            return false;
        }
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/retrive_district')?>',
            data: {country_id: country_id},
            success: function (data) {
                if (data) {
                    $("#ship_state").html(data);
                } else {
                    $("#ship_state").html('<p style="color:red"><?php echo display('failed')?></p>');
                }
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                });
            }
        });
    });
</script>
<!-- Retrive shipping district ajax code end -->

<!-- Push delivery cost to cache by ajax -->
<script type="text/javascript">
    var couponAmount = 0;
    $('body').on('click', '.shipping_cost', function () {
        var cart_total_amount = 0;
        var shipping_cost = $(this).val();
        var ship_cost_name = $(this).attr('alt');
        var method_id = $(this).attr('id');
        cart_total_amount = $('#cart_total_amount').val(); //include tax
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/set_ship_cost_cart')?>',
            data: {shipping_cost: shipping_cost, ship_cost_name: ship_cost_name, method_id: method_id},
            success: function (data) {
                $('#shipCostRow').show();
                $('#set_cart_ship_name').html(ship_cost_name);
                $('#set_ship_cost').html(shipping_cost);
                let total_cost = +cart_total_amount + +shipping_cost - +couponAmount;
                $('#total_amount').html(parseFloat(total_cost).toFixed(2));
                $('#order_total_amount').val(parseFloat(total_cost).toFixed(2));
            },
            error: function () {
                Swal({
                    type: 'warning',
                    title: '<?php echo display('request_failed')?>'
                });
            }
        });
    });
</script>
<!-- Push delivery cost to cache by ajax  -->


<!--coupon amount set-->
<script type="text/javascript">
    $(document).ready(function () {
        var cart_total_amount = $('#cart_total_amount').val(); //include tax
        $('input[type=radio]').attr('checked', false);
        $('#shipCostRow').hide();
        $('#couponAmountRow').hide();
        $('#total_amount').html(parseFloat(cart_total_amount).toFixed(2));
        $('#order_total_amount').val(parseFloat(cart_total_amount).toFixed(2));


        //check coupon amount
        $('#coupon_value').on('click', function (e) {
            e.preventDefault();
            let couponInfo = $('#coupon_code').val();
            let coupon_code =$.trim(couponInfo);
            $.ajax({
                url: "<?php echo base_url('home/apply_coupon')?>",
                type: "post",
                data: {coupon_code: coupon_code},
                success: function (data) {
                    console.log(data);
                    if (data == '1' || data == '2') {
                        couponAmount = "<?php echo $this->session->userdata('coupon_amnt')?>";
                        $('#set_coupon_price').html(couponAmount);
                        $('#couponAmountRow').show();
                        var afterCouponTotalAmount = parseFloat(cart_total_amount).toFixed(2) - +parseFloat(couponAmount).toFixed(2);
                        $('#total_amount').html(parseFloat(afterCouponTotalAmount).toFixed(2));
                        $('#order_total_amount').val(parseFloat(afterCouponTotalAmount).toFixed(2));
                        $('#coupon_error').html("<?php echo $this->session->userdata('message')?>")
                        $('#coupon_error_text_color').css({'color':'#155724'});
                    } else {
                        $('#coupon_error').html("<?php echo $this->session->userdata('error_message')?>");
                        $('#coupon_error_text_color').css({'color':'#721c24'});
                    }
                }
            });

        });
    });
</script>
<!-- check product quantity in stock is available or not  -->
<script type="text/javascript">
    $('body').on('click', '.sw-btn-next', function () {
        $.ajax({
            type: "get",
            async: true,
            url: '<?php echo base_url('website/Home/check_product_store')?>',
            success: function (data) {
                if (data === 'no') {

                    Swal({
                        type: 'warning',
                        title: '<?php echo display('not_enough_product_in_stock')?>'
                    });
                    window.location.href = "<?php echo base_url('view_cart')?>";
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#validateForm").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                first_name: {
                    required: true
                },
                ship_first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                ship_last_name: {
                    required: true
                },
                customer_mobile: {
                    required: true,
                },
                ship_mobile: {
                    required: true,
                },
                country: {
                    required: true,
                },
                ship_country: {
                    required: true,
                },
                customer_address_1: {
                    required: true,
                },
                ship_address_1: {
                    required: true,
                },
                state: {
                    required: true,
                },
                ship_state: {
                    required: true,
                },
            },
            messages: {
                first_name: {
                    required: "<?php echo display('first_name_is_required');?>",
                },
                ship_first_name: {
                    required: "<?php echo display('first_name_is_required');?>",
                },
                last_name: {
                    required: "<?php echo display('last_name_is_required');?>",
                },
                ship_last_name: {
                    required: "<?php echo display('last_name_is_required');?>",
                },
                customer_mobile: {
                    required: "<?php echo display('mobile_is_required');?>",
                },
                ship_mobile: {
                    required: "<?php echo display('mobile_is_required');?>",
                },
                country: {
                    required: "<?php echo display('country_is_required');?>",
                },
                ship_country: {
                    required: "<?php echo display('country_is_required');?>",
                },
                customer_address_1: {
                    required: "<?php echo display('address_is_required');?>",
                },
                ship_address_1: {
                    required: "<?php echo display('address_is_required');?>",
                },
                state: {
                    required: "<?php echo display('state_is_required');?>",
                },
                ship_state: {
                    required: "<?php echo display('state_is_required');?>",
                },
            },
            errorPlacement: function (error, element) {
                if (error) {
                    $(element).parent().attr('class', 'form-group has-error');
                    $(element).parent().append(error);
                } else {
                    $(element).parent().attr('class', 'form-group');
                }
            },
            success: function (error, element) {
                $(element).parent().attr('class', 'form-group');
            }
        });
    });
</script>


<script type="text/javascript">
    // $(document).ready(function () {
    //     $('#diff_ship_adrs').on('click',function() {
    //         var check=$('[name="diff_ship_adrs"]:checked').length;
    //         if (check > 0) {
    //             $('input[name="diff_ship_adrs"]').attr("checked", "checked");
    //         }else {
    //             $('input[name="diff_ship_adrs"]').removeAttr('checked');
    //         }
    //     });
    // })


    //Shipping to different address
    $('#diff_ship_adrs').on('click', function () {
        var check = $('[name="diff_ship_adrs"]:checked').length;
        if (check > 0) {
            $('input[name="diff_ship_adrs"]').attr("checked", "checked");
        } else {
            $('input[name="diff_ship_adrs"]').removeAttr('checked');
        }
    });

    //Privacy policy
    $('#privacy_policy').click(function () {
        var check = $('[name="privacy_policy"]:checked').length;
        if (check > 0) {
            $('input[name="privacy_policy"]').attr("checked", "checked");
        } else {
            $('input[name="privacy_policy"]').removeAttr('checked');
        }
    });


    //Onkeyup change session value
    $('body').on('keyup click change', '#first_name,#last_name,#customer_email,#customer_mobile,#customer_address_1,#customer_address_2,#company,#city,#zip,#country,#state,#ac_pass,#privacy_policy,.shipping_cost,#ship_first_name,#ship_last_name,#ship_email,#ship_mobile,#ship_country,#ship_address_1,#ship_address_2,#ship_city,#ship_state,#ship_zip,#ship_company,#order_details,#creat_ac', function () {

        var shipping_cost = $('input[name=shipping_cost]:checked').val();
        var ship_cost_name = $('input[name=shipping_cost]:checked').attr('alt');
        var method_id = $('input[name=shipping_cost]:checked').attr('id');

        //Ship and billing info
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var customer_email = $('#customer_email').val();
        var customer_mobile = $('#customer_mobile').val();
        var customer_address_1 = $('#customer_address_1').val();
        var customer_address_2 = $('#customer_address_2').val();
        var company = $('#company').val();
        var city = $('#city').val();
        var zip = $('#zip').val();
        var country = $('#country').val();
        var state = $('#state').val();
        var ac_pass = $('#ac_pass').val();
        var privacy_policy = $('#privacy_policy').attr("checked") ? 1 : 0;
        var creat_ac = $('#creat_ac').attr("checked") ? 1 : 0;

        var ship_first_name = $('#ship_first_name').val();
        var ship_last_name = $('#ship_last_name').val();
        var ship_company = $('#ship_company').val();
        var ship_mobile = $('#ship_mobile').val();
        var ship_email = $('#ship_email').val();
        var ship_address_1 = $('#ship_address_1').val();
        var ship_address_2 = $('#ship_address_2').val();
        var ship_city = $('#ship_city').val();
        var ship_zip = $('#ship_zip').val();
        var ship_country = $('#ship_country').val();
        var ship_state = $('#ship_state').val();
        var payment_method = $('input[name=\'payment_method\']:checked').val();
        var order_details = $('#order_details ').val();
        var diff_ship_adrs = $('#diff_ship_adrs').attr("checked") ? 1 : 0;
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('website/Home/set_ship_cost_cart')?>',
            data: {
                shipping_cost: shipping_cost,
                ship_cost_name: ship_cost_name,
                method_id: method_id,
                first_name: first_name,
                last_name: last_name,
                customer_email: customer_email,
                customer_mobile: customer_mobile,
                customer_address_1: customer_address_1,
                customer_address_2: customer_address_2,
                company: company,
                city: city,
                zip: zip,
                country: country,
                state: state,
                ac_pass: ac_pass,
                privacy_policy: privacy_policy,
                creat_ac: creat_ac,
                ship_first_name: ship_first_name,
                ship_last_name: ship_last_name,
                ship_company: ship_company,
                ship_mobile: ship_mobile,
                ship_email: ship_email,
                ship_address_1: ship_address_1,
                ship_address_2: ship_address_2,
                ship_city: ship_city,
                ship_zip: ship_zip,
                ship_country: ship_country,
                ship_state: ship_state,
                payment_method: payment_method,
                order_details: order_details,
                diff_ship_adrs: diff_ship_adrs,
            },
            success: function (data) {
                return true;
            },
            error: function () {
                // alert('Request Failed, Please check your code and try again!');
            }
        });
    });

        $('#billind-different-address').hide();
    $('#diff_ship_adrs').click(function(){
        $('#billind-different-address').toggle();
    })

    $("#payment_method_sumbmit").attr("disabled", true);
    $('.completed').click(function(){
        $("#payment_method_sumbmit").removeAttr("disabled");
    })

</script>


