<!--Update email setting start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('sms_configuration') ?></h1>
            <small><?php echo display('sms_configuration') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('software_settings') ?></a></li>
                <li class="active"><?php echo display('sms_configuration') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
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
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <p style="background: lightyellow; padding: 1em; box-shadow: 1px 3px 5px #0004; border-radius: 5px; cursor:alias;">
                            To get <b>50</b> free sms from smsrank.com click <b><a href="http://door.smsrank.com/signup"
                                                                                   target="_blank">here</a></b> and
                            register in registration section click Already envato user and put your envato purchace key
                            and product id after registration put your username and password into the password and user
                            name field this form.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr class="center bg-success">
                                <th><?php echo display('sl'); ?></th>
                                <th><?php echo display('gateway'); ?> </th>
                                <th><?php echo display('username'); ?> </th>
                                <th><?php echo display('password'); ?> </th>
                                <th><?php echo display('userid'); ?> </th>
                                <th><?php echo display('from'); ?> </th>
                                <th><?php echo display('status'); ?> </th>
                                <th><?php echo display('action'); ?> </th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $i = 1;
                            foreach ($gateways as $gateway) { ?>
                                <?php echo form_open('Csms_setting/update_sms_configuration', array('method' => 'post', 'role' => 'form')); ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <input type="hidden" name="id" value="<?php echo $gateway['id']; ?>">

                                    <td><?php echo '<a target="_blank" href="' . $gateway['link'] . '">' . $gateway['gateway'] . '</a>' ?></td>
                                    <input type="hidden" name="gateway" value="<?php $gateway['gateway'] ?>">
                                    <td><input type="text" class="form-control"
                                               value="<?php echo $gateway['user_name']; ?>" name="user_name"></td>
                                    <td>
                                        <?php if (3 == $gateway['id']) { ?>
                                            <input type="text" class="form-control" data-toggle="tooltip" title="handle"
                                                   value="<?php echo $gateway['password'] ?>" name="password">
                                        <?php } else { ?>
                                            <input type="text" class="form-control"
                                                   value="<?php echo $gateway['password'] ?>" name="password">
                                        <?php } ?>
                                    </td>
                                    <?php if (3 != $gateway['id']) { ?>
                                        <td><input type="text" class="form-control" readonly
                                                   value="<?php echo $gateway['userid'] ?>" name="userid"></td>
                                    <?php } else { ?>
                                        <td><input type="text" class="form-control"
                                                   value="<?php echo $gateway['userid'] ?>" name="userid"></td>
                                    <?php }; ?>
                                    <td><input type="text" class="form-control"
                                               value="<?php echo $gateway['sms_from'] ?>" name="sms_from"></td>
                                    <td>
                                        <select name="status" id="status">
                                            <option value="0"><?php echo display('inactive') ?></option>
                                            <option value="1" <?php echo ($gateway['status'] == 1) ? 'selected' : '' ?>><?php echo display('active') ?></option>
                                        </select>
                                    </td>
                                    <td width="70">
                                        <input type="submit" value="<?php echo display('update'); ?>"
                                               class="btn btn-xs btn-warning">
                                    </td>
                                </tr>
                                </form>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    $("form :input").attr("autocomplete", "off");
</script>