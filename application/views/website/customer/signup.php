<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="page-breadcrumbs">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()?>"><?php echo display('home')?></a></li>
            <li class="active"><?php echo display('sign_up')?></li>
        </ol>
    </div>
</div>
<!--========== End Page Header Area ==========-->

<div class="lost-password">
    <?php
    if ($this->session->userdata('message')) {
        $message = $this->session->userdata('message');
        if ($message) {
            ?>
            <div class="alert alert-success">
                <strong><?php echo  display('success')?></strong> <?php echo $message?>
            </div>
            <?php
        }
        $this->session->unset_userdata('message');
    }
    ?>
    <h4 class="mb-25 text-center"><?php echo display('create_your_account')?></h4>
    <form action="<?php echo base_url('user_signup')?>" method="post">
        <div class="form-group">
            <label class="control-label" for="first_name"><?php echo display
                ('first_name')?><abbr class="required" title="required">*</abbr></label>
            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="<?php echo display
            ('first_name')?>" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="last_name"><?php echo display('last_name')?><abbr class="required" title="required">*</abbr></label>
            <input type="text" id="last_name" placeholder="<?php echo display('last_name')?>" class="form-control"
                   name="last_name" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="user_email"><?php echo display('email')?><abbr class="required" title="required">*</abbr></label>
            <input type="text" id="user_email" class="form-control"name="email" placeholder="<?php echo display('email')?>" required>
            <p id="email_warning"></p>
        </div>
        <div class="form-group">
            <label class="control-label" for="mobile"><?php echo display('mobile')?><abbr class="required"
                                                                                    title="required">*</abbr></label>
            <input type="text" id="mobile" class="form-control" name="phone" placeholder="<?php echo display('mobile')
            ?>" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="user_pw"><?php echo display('password')?> <abbr class="required" title="required">*</abbr></label>
            <input type="password" id="user_pw" class="form-control" name="password" placeholder="<?php echo display('password')?>" required>
        </div>
        <div class="form-group">
            <button type="submit" class="base_button btn btn-warning color4" id="create_account_btn"><?php echo display('create_account')?></button>
            <p class="d-inline-block pull-right mt-8 already_member"><?php echo display('already_member')?><a href="<?php echo base_url('login')?>">
                    <?php echo display('login')?></a></p>
        </div>
    </form>
</div>
<!--=========for show and hide login dropdown===========-->
<script>
    $(document).ready(function () {
        $('.already_member,.hnav-li').on('click',function () {
            $('.user-register').show();
        });
    });
    $('.page-wrapper').css({'background':'#fff'});
</script>