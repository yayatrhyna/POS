<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$CI =& get_instance();
$CI->load->model('Themes');
$theme = $CI->Themes->get_theme();
?>

<section class="contact_area">
    <div class="container">
        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $message ?>
            </div>
            <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $error_message ?>
            </div>
            <?php
            $this->session->unset_userdata('error_message');
        }
        ?>

        <div class="row text-center">
            <h2 class="m-0"><?php echo display('get_in_touch')?></h2>
            <h5><?php echo display('your_email_address_will_not_be_published')?></h5>
        </div>
        <div class="row contact_inner">
            <div class="col-sm-9">
                <form class="request_form" action="<?php echo base_url('submit_contact') ?>" method="post">
                    <div class="row comments_area">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="<?php echo display('first_name')?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="<?php echo display('last_name')?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo display('email')?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <textarea class="form-control msg_box" name="message" placeholder="<?php echo display('write_your_msg_here')?> ..."></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button href="#" class="btn-one pull-right"><?php echo display('submit')?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-3">
                <div class="row m-0">
                    <div class="contact_info">
                        <h4><?php echo display('our_location')?></h4>
                        <?php
                        if ($our_location_info) {
                            foreach ($our_location_info as $our_location) {
                                ?>
                                <div class="office_address">
                                    <h5><?php echo $our_location['headline']?></h5>
                                    <?php echo $our_location['details']?>
                                </div>
                                <?php
                            }
                        }
                        ?>
<!--                        <p>98 Green Road, Farmgate, <br>Dhaka, Bangladesh</p>-->
<!--                        <a href="#" class="">www.bdtask.com</a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Area -->

<!--========== Map Area ==========-->
<div class="container">
    <div class="map_area">
        <div id="map"></div>
    </div>
</div>
<!--======== End Map Area ==========-->


<?php
$CI =& get_instance();
$CI->load->model('Companies');
$company_info = $CI->Companies->company_list();
?>

<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_info[0]['map_api_key']?>"></script>

<script type="text/javascript">
    // When the window has finished loading create our google map below
    google.maps.event.addDomListener(window, 'load', init);

    function init() {
        var mapOptions = {
            // How zoomed in you want the map to start at (always required)
            zoom: 14,
            scrollwheel: false,
            // The latitude and longitude to center the map (always required)
            center: new google.maps.LatLng(<?php echo $map_info[0]['map_latitude']?>, <?php echo $map_info[0]['map_langitude']?>), // Dhaka
        };

        // image from external URL

        var myIcon = '<?php echo base_url('assets/website/image/marker.png')?>';

        //preparing the image so it can be used as a marker
        var catIcon = {
            url: myIcon,
        };
        var mapElement = document.getElementById('map');

        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);

        // Let's also add a marker while we're at it
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $map_info[0]['map_latitude']?>, <?php echo $map_info[0]['map_langitude']?>),
            map: map,
            icon: catIcon,
            title: '<?php echo $company_info[0]['company_name']?>',
            animation: google.maps.Animation.DROP,
        });
    }
</script>
