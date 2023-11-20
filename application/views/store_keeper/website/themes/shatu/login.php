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
            <li class="active"><?php echo display('login'); ?></li>
        </ol>
    </div>
</div>

<!--========== Alert Message ==========-->
<div class="login_page">
    <div class="container">
        <div class="row db m0 login_area">
            <?php
            $message = $this->session->userdata('message');
            if (!empty($message)) {
                ?>
                <div class="alert alert-success alert-dismissible show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $message; ?>
                </div>
                <?php
                $this->session->unset_userdata('message');
            }
            ?>
            <?php
            $error_message = $this->session->userdata('error_message');
            if ($error_message) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $error_message ?>
                </div>
                <?php
                $this->session->unset_userdata('error_message');
            }
            ?>
        </div>
    </div>
</div>
<!--========== Alert Message ==========-->

<div class="lost-password">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4 class="mb-25"><?php echo display('welcome_back_to_login'); ?></h4>
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url() . 'do_login'; ?>" method="post">
                <div class="form-group">
                    <label class="control-label" for="user_email_id"><?php echo display('username_or_email'); ?><abbr
                                class="required" title="required">*</abbr></label>
                    <input type="email" id="user_email_id" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label class="control-label" for="password"><?php echo display('password'); ?>
                        <abbr class="required" title="required">*</abbr>
                    </label>
                    <input type="password" id="password" class="form-control" name="password">
                </div>
                <div class="form-group row">
                    <div class="col-xs-6">
                        <label class="checkbox_area"><?php echo display('remember_me'); ?>
                            <input type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-xs-6">
                        <a href="<?php echo base_url('forget_password_form'); ?>"
                           class="pull-right"><?php echo display('forget_password'); ?></a>
                    </div>
                </div>
                <div class="form-group">
                    <input href="<?php echo base_url('login'); ?>" type="submit"
                           class="btn btn-primary color4" value="<?php echo display('login') ?>">
                    <p class="d-inline-block pull-right mt-8">
                        <?php echo display('dont_have_an_account'); ?>
                        <a href="<?php echo base_url() . 'signup'; ?>"> <?php echo display('register_account'); ?></a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>