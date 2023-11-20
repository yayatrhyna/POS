<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login - Kenan Hijab</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="my-assets/login/css/login.css">
  <link rel="icon" type="image/x-icon" href="my-assets/login/images/favicon.jpg">
  

  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>


  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">

        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="my-assets/login/images/1.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
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
              $CI = &get_instance();
              $CI->load->model('Soft_settings');
              $setting_detail = $CI->Soft_settings->retrieve_setting_editdata();
              ?>
              <div class="brand-wrapper">
                <img src="my-assets/login/images/logo.jpg" alt="logo" class="logo">
              </div>
              <div>
                <div style="color: red; font-weight: bold;" id="recover_message"></div>
                <div id="loader" class="text-center"><img style="width: 2em; height: 2em;" src="<?php echo base_url('my-assets/image/loader.gif') ?>" alt="">
                </div>
              </div>
              <p class="login-card-description">Sign into your account</p>

              <?php echo form_open('admin_dashboard/do_login', array('id' => 'validate',)) ?>
              <div class="form-group">
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="username" id="username" class="form-control" placeholder="Email address">
              </div>
              <div class="form-group mb-4">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="***********">
              </div>
              <button class="btn btn-block login-btn mb-4" id="login_button"><?php echo display('login') ?></button>

              <?php if ($setting_detail[0]['captcha'] == 0 && $setting_detail[0]['site_key'] != null && $setting_detail[0]['secret_key'] != null) { ?>
                  <div style="margin-bottom: 10px" class="g-recaptcha"
                        data-sitekey="<?php if (isset($setting_detail[0]['site_key'])) {
                            echo $setting_detail[0]['site_key'];
                        } ?>">
                  </div>
              <?php } ?>

              <!-- <a href="#!" class="forgot-password-link">Forgot password?</a> -->
              <?php echo form_close() ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script>
    $('#password').keypress(function(e) {
      if (e.which == 13) {
        $('form#validate').submit();
        return false;
      }
    });

    $('#loader').hide();
    $('#forget_password_form').hide();
    $('#forget_password_button').on('click', function() {
      $('#login_form').remove();
      $('#login_button').remove();
      $('#forget_password_form').show();
      $('#submit_button').show();
    });

    $('#submit_button').on('click', function(e) {
      e.preventDefault();
      var admin_email = $("input[name=admin_email]").val();
      $("#recover_message").html('');
      $('#loader').show();
      $.ajax({
        type: "post",
        async: true,
        dataType: "json",
        url: '<?php echo base_url('forget_admin_password') ?>',
        data: {
          admin_email: admin_email
        },
        success: function(data) {
          $('#loader').hide();
          if (3 == data) {
            $("#recover_message").html("<?php echo display('this_email_not_exits') ?>");
            $("input[name=forget_email]").css({
              "border-color": "red"
            });
            return false;
          }
          if (1 == data) {
            $("#recover_message").html("<?php echo display('you_have_receive_a_email_please_check_your_email') ?>").css({
              "color": "green"
            });
            $("input[name=forget_email]").css({
              "border-color": "#dedede"
            });
          }
          if (2 == data) {
            $("#recover_message").html("<?php echo display('email_not_send') ?>");
          }
          if (4 == data) {
            $("#recover_message").html("<?php echo display('please_try_again') ?>");
          }
        }
      });
    })
  </script>
</body>

</html>