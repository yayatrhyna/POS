<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Password Reset</title>
</head>
<body>
 <div class="container mt-5">
   <div class="row justify-content-center align-items-center">
    <div class="col-sm-7">
    <div class="card">
      <div class="card-header">
        Reset Password

      </div>
      <div class="card-body">
       <?php echo form_open_multipart('admin_password_update',array('class' => 'form-vertical', 'id' => 'admin_password_update','name' => 'admin_password_update'))?>



        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label">New Password</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="admin_password" id="admininputPassword" placeholder="Password">
            <input type="hidden" name="token" value="<?php echo $token;?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label"></label>
          <div class="col-sm-8">
            <input type="submit" class="btn btn-success" value="Submit">
          </div>
        </div>
        <?php echo form_close()?>
      
    </div>
  </div>
  </div>
  </div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>