<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- Admin login area start-->
<div class="container-center">
    <!-- Alert Message -->
    <?php
    $message = $this->session->userdata('message');
    if (isset($message)) {
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>
        </div>
        <?php
        $this->session->unset_userdata('message');
    }
    $error_message = $this->session->userdata('error_message');
    if (isset($error_message)) {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>
        </div>
        <?php
        $this->session->unset_userdata('error_message');
    }
    $CI =& get_instance();
    $CI->load->model('Soft_settings');
    $setting_detail = $CI->Soft_settings->retrieve_setting_editdata();
    ?>
    <div class="panel panel-bd">
        <div class="panel-heading">
            <div class="view-header">
                <div class="header-icon">
                    <i class="pe-7s-unlock"></i>
                </div>
                <div class="header-title">
                    <h3><?php echo display('login') ?></h3>
                    <small><strong><?php echo display('please_enter_your_login_information') ?></strong></small>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div>
                <div style="color: red; font-weight: bold;" id="recover_message"></div>
                <div id="loader" class="text-center"><img style="width: 2em; height: 2em;"
                                      src="<?php echo base_url('my-assets/image/loader.gif') ?>"
                                      alt="">
                </div>
            </div>
            <div id="login_form">
                <?php echo form_open('admin_dashboard/do_login', array('id' => 'validate',)) ?>
                <div class="form-group">
                    <label class="control-label" for="username"><?php echo display('email') ?></label>
                    <input type="email" placeholder="<?php echo display('email') ?>" required="" name="username"
                           id="username" class="form-control">
                    <span class="help-block small"><?php echo display('your_unique_email') ?></span>
                </div>
                <div class="form-group">
                    <label class="control-label" for="password"><?php echo display('password') ?></label>
                    <input type="password" title="Please enter your password"
                           placeholder="<?php echo display('password') ?>" required="" value="" name="password"
                           id="password" class="form-control">
                    <span class="help-block small"><?php echo display('your_strong_password') ?></span>
                </div>

                <?php if ($setting_detail[0]['captcha'] == 0 && $setting_detail[0]['site_key'] != null && $setting_detail[0]['secret_key'] != null) { ?>
                    <div style="margin-bottom: 10px" class="g-recaptcha"
                         data-sitekey="<?php if (isset($setting_detail[0]['site_key'])) {
                             echo $setting_detail[0]['site_key'];
                         } ?>">
                    </div>
                <?php } ?>
            </div>
            <div id="forget_password_form">
                <div class="form-group">
                    <label class="control-label" for="admin_email"><?php echo display('email') ?></label>
                    <input type="email" placeholder="<?php echo display('email') ?>" required="" name="admin_email"
                           id="admin_email" class="form-control">
                    <span class="help-block small"><?php echo display('your_unique_email') ?></span>
                </div>
                <div>
                    <button class="btn btn-success" id="submit_button"><?php echo display('submit') ?></button>
                </div>
            </div>
            <div>
                <button class="btn btn-success" id="login_button"><?php echo display('login') ?></button>
                <a class="pull-right" href="#" id="forget_password_button"><?php echo display('forget_password'); ?></a>
            </div>

            <?php echo form_close() ?>
        </div>
    </div>
</div>
<!-- Admin login area end -->
<script>


    $('#password').keypress(function (e) {
        if (e.which == 13) {
            $('form#validate').submit();
            return false;    
        }
    });

    $('#loader').hide();
    $('#forget_password_form').hide();
    $('#forget_password_button').on('click', function () {
        $('#login_form').remove();
        $('#login_button').remove();
        $('#forget_password_form').show();
        $('#submit_button').show();
    });

    $('#submit_button').on('click',function (e) {
        e.preventDefault();
        var admin_email = $("input[name=admin_email]").val();
        $("#recover_message").html('');
        $('#loader').show();
        $.ajax({
            type: "post",
            async: true,
            dataType: "json",
            url: '<?php echo base_url('forget_admin_password')?>',
            data: {admin_email: admin_email},
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
    })
</script>