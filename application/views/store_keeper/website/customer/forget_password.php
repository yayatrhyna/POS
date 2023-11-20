<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-breadcrumbs">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><?php echo display('home') ?></a></li>
            <li class="active"><?php echo display('forget_password') ?></li>
        </ol>
    </div>
</div>
<!--========== End Page Header Area ==========-->

<div class="lost-password" style="background: #fff">
    <?php
    if ($this->session->userdata('message')) {
        $message = $this->session->userdata('message');
        if ($message) {
            ?>
            <div class="alert alert-success">
                <strong><?php echo display('success') ?></strong> <?php echo $message ?>
            </div>
            <?php
        }
        $this->session->unset_userdata('message');
    }
    ?>

    <div class="lost-password" style="padding: 1em;">
        <p><?php echo display('lost_your_password') ?></p>
        <form action="#" method="post">
            <div class="form-group">
                <label class="control-label" for="user_email"><?php echo display('email') ?> <abbr class="required" title="required">*</abbr></label>
                <input type="text" name="forget_email" required id="forget_email" class="form-control">
            </div>
            <button type="button" id="forget_password_btn"
                    class="btn btn-primary color2"><?php echo display('reset_password') ?>

            </button>
        </form>
    </div>
    <div>
        <div style="color: red; font-weight: bold;" id="recover_message"></div>
        <div id="loader"><img style="width: 2em; height: 2em;"
                              src="<?php echo base_url('my-assets/image/loader.gif') ?>"
                              alt="">
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
    $('.page-wrapper').css({'background':'#fff'});
</script>