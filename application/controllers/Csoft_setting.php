<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Csoft_setting extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('lsoft_setting');
		$this->load->model('Soft_settings');
		$this->load->model('Color_frontends');
		$this->load->model('Color_backends');
		$this->auth->check_admin_auth();

		//User validation check
		if ($this->session->userdata('user_type') == '2') {
			$this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
			redirect('Admin_dashboard');
		}
	}

	//Default loading for Category system.
	public function index()
	{
		$content = $this->lsoft_setting->setting_add_form();
		$this->template->full_admin_html_view($content);
	}

	// Setting Update
	public function update_setting()
	{
		if ($_FILES['logo']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
			$config['max_size']             = "1024";
			$config['max_width']            = "*";
			$config['max_height']           = "*";
			$config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('logo'))
			{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
				redirect(base_url('Csoft_setting'));
			}
			else
			{
				$image =$this->upload->data();
				$logo = "my-assets/image/logo/".$image['file_name'];
			}
		}

		if ($_FILES['favicon']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
			$config['max_size']             = "1024";
			$config['max_width']            = "*";
			$config['max_height']           = "*";
			$config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('favicon'))
			{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
				redirect(base_url('Csoft_setting'));
			}
			else
			{
				$image =$this->upload->data();
				$favicon = "my-assets/image/logo/".$image['file_name'];
			}
		}

		if ($_FILES['invoice_logo']['name']) {
			$config['upload_path']          = 'my-assets/image/logo/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
			$config['max_size']             = "1024";
			$config['max_width']            = "*";
			$config['max_height']           = "*";
			$config['encrypt_name'] 		= TRUE;
            $this->upload->initialize($config);
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('invoice_logo'))
			{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
				redirect(base_url('Csoft_setting'));
			}
			else
			{
				$image =$this->upload->data();
				$invoice_logo = "my-assets/image/logo/".$image['file_name'];
			}
		}

		$old_logo 	 = $this->input->post('old_logo');
		$old_invoice_logo = $this->input->post('old_invoice_logo');
		$old_favicon = $this->input->post('old_favicon');

		$language = $this->input->post('language');
		$this->session->set_userdata('language',$language);

		$data=array(
			'logo' 			=> (!empty($logo)?$logo:$old_logo),
			'invoice_logo' 	=> (!empty($invoice_logo)?$invoice_logo:$old_invoice_logo),
			'favicon' 		=> (!empty($favicon)?$favicon:$old_favicon),
			'footer_text'	=> $this->input->post('footer_text'),
			'language' 		=> $language,
			'rtr' 			=> $this->input->post('rtr'),
			'captcha' 		=> $this->input->post('captcha'),
			'site_key' 		=> $this->input->post('site_key'),
			'secret_key' 	=> $this->input->post('secret_key'),
			'sms_service' 	=> $this->input->post('sms_service'),
		);

		$this->Soft_settings->update_setting($data);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csoft_setting'));
	}

	//Email Configuration
	public function email_configuration(){
		$content = $this->lsoft_setting->email_configuration_form();
		$this->template->full_admin_html_view($content);
	}

	//Update email configuration
	public function update_email_configuration()
	{


		$data=array(
			'protocol' 		=> $this->input->post('protocol'),
			'mailtype'		=> $this->input->post('mailtype'),
			'smtp_host' 	=> $this->input->post('smtp_host'),
			'smtp_port' 	=> $this->input->post('smtp_port'),
			'sender_email' 	=> $this->input->post('sender_email'),
			'password' 		=> $this->input->post('password'),
		);

		$this->Soft_settings->update_email_config($data);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csoft_setting/email_configuration'));
	}




	//Payment Configuration
	public function payment_gateway_setting(){
		$content = $this->lsoft_setting->payment_configuration_form();
		$this->template->full_admin_html_view($content);
	}

	//Update payment configuration
	public function update_payment_gateway_setting($id = null)
	{
		if ($id == 2) {
			$data=array(
				'shop_id' 		=> $this->input->post('shop_id'),
				'secret_key'	=> $this->input->post('secret_key'),
				'status' 		=> $this->input->post('status'),
			);
			$this->Soft_settings->update_payment_gateway_setting($data,$id);
		}else if ($id == 1){
			$data=array(
				'public_key' => $this->input->post('public_key'),
				'private_key'=> $this->input->post('private_key'),
				'status' 	 => $this->input->post('status'),
			);

			$this->Soft_settings->update_payment_gateway_setting($data,$id);
		}else if ($id == 3){
			$data=array(
				'paypal_email' => $this->input->post('paypal_email'),
				'paypal_client_id' => $this->input->post('client_id'),
				'currency'	   => $this->input->post('currency'),
				'status' 	   => $this->input->post('status'),
			);

			$this->Soft_settings->update_payment_gateway_setting($data,$id);
		}
		else if ($id == 4){
			$data=array(
				'paypal_email' => $this->input->post('sslcommerz_email'),
				'shop_id' => $this->input->post('store_id'),
				'secret_key'	   => $this->input->post('secret_key'),
				'currency'	   => $this->input->post('currency'),
				'status' 	   => $this->input->post('status'),
			);

			$this->Soft_settings->update_payment_gateway_setting($data,$id);
		}

		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csoft_setting/payment_gateway_setting'));
	}

//shwo fronted template color edit form
	public function color_setting_frontend()
	{

		$content = $this->lsoft_setting->color_frontend_edit_form();
		$this->template->full_admin_html_view($content);
	}

	//show backend template color edit form
	public function color_setting_backend()
	{
		$content = $this->lsoft_setting->color_backend_edit_form();
		$this->template->full_admin_html_view($content);
	}

//update fronend templete color
	public function update_frontend_color()
	{
		$data=
		[
				'color1' => $this->input->post('color1'),
				'color2' => $this->input->post('color2'),
				'color3' => $this->input->post('color3'),
				'color4' => $this->input->post('color4'),
		];

		$result = $this->Color_frontends->update_color($data);
		if($result)
		{
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csoft_setting/color_setting_frontend'));
		}
	}
	public function update_backend_color()
	{
		$data=
		[
				'color1' => $this->input->post('color1'),
				'color2' => $this->input->post('color2'),
				'color3' => $this->input->post('color3'),
				'color4' => $this->input->post('color4'),
				'color5' => $this->input->post('color5'),
		];

		$result = $this->Color_backends->update_color($data);
		if($result)
		{
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csoft_setting/color_setting_backend'));
		}
	}


    public function import_database()
    {
        $data = array(
            'title' => display('import_database')
        );
        $content = $this->parser->parse('soft_setting/import_database_form',$data,true);
        $this->template->full_admin_html_view($content);
    }

    /**
     *
     */
    public function import_database_data() {
        $hostname = $this->db->hostname;
        $username = $this->db->username;
        $password = $this->db->password;
        $database = $this->db->database;
        @$mysqli = new \mysqli(
            $hostname,
            $username,
            $password,
            $database
        );

        // Check for errors
        if (mysqli_connect_errno()){
            echo 'fail to connect';
            return false;
        }


        if ($_FILES['import_database']['name']) {
            $config['upload_path'] = 'my-assets/db/';
            $config['allowed_types'] = '*';
            $config['max_size'] = "*";
            $config['max_width'] = "*";
            $config['max_height'] = "*";
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('import_database')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Csoft_setting/import_database'));
            } else {
                $file = $this->upload->data();
                $file_url = base_url()."my-assets/db/".$file['file_name'];
            }
        }

        $tables = $this->db->list_tables();

        foreach ($tables as $table)
        {

                $this->db->truncate($table);
        }
        $templine = '';
        // Read in entire file
        $lines = file($file_url);
        foreach($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current templine we are creating
            $templine.=$line;

            // If it has a semicolon at the end, it's the end of the query so can process this templine
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $result=  $this->db->query($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        if ($result){
            unlink(getcwd()."/my-assets/db/".$file['file_name']);
        }
        $this->session->set_userdata(array('message' => 'Successfully Imported '));
        redirect($_SERVER['HTTP_REFERER']);
    }


//    public function _import_database_data()
//    {
//
//        if ($_FILES['import_database']['name']) {
//            $config['upload_path']          = 'my-assets/db/';
//            $config['allowed_types']        = '*';
//            $config['max_size']             = "*";
//            $config['max_width']            = "*";
//            $config['max_height']           = "*";
//            $config['encrypt_name'] 		= true;
//            $this->upload->initialize($config);
//            $this->load->library('upload', $config);
//            if ( ! $this->upload->do_upload('import_database'))
//            {
//                $error = array('error' => $this->upload->display_errors());
//                $this->session->set_userdata(array('error_message'=> $this->upload->display_errors()));
//                redirect(base_url('Csoft_setting/import_database'));
//            }
//            else
//            {
//                $sql =$this->upload->data();
//                $file = base_url()."my-assets/db/".$sql['file_name'];
//                $file_name= $sql['file_name'];
//            }
//        }
//
//        $this->db->trans_start();
//
//        $database = $this->db->database;
//        $host = $this->db->hostname;
//        $user= $this->db->username;
//        $password= $this->db->password;
//
//        $mysqli = mysqli_connect($host, $user, $password, $database);
//        // Check for errors
//        if (mysqli_connect_errno()){
//            echo "mysqli not connect";
//        }
//
//        /* query all tables */
//        $sql = "SHOW TABLES";
//        if($result = mysqli_query($mysqli,$sql)){
//            /* add table name to array */
//            while($row = mysqli_fetch_row($result)){
//                $found_tables[]=$row[0];
//            }
//        }
//        else{
//            die("Error, could not list tables.");
//        }
//        $sql='';
//        /* loop through and drop each table */
//        foreach($found_tables as $table_name){
//            $sql .= "TRUNCATE  `$table_name`;";
//        }
//        if($result = $mysqli->multi_query($sql)){
//            // echo "Success - table $table_name deleted.<br>";
//        }
//        else{
//            // echo "Error deleting";
//        }
//
//        // Open the default SQL file
//        $query = file_get_contents($file);
//        // Execute a multi query
////        dd($query);
////        $multi_query=mysqli_multi_query($mysqli,$query);
////        $multi_query = $this->db->query($query);
////        $multi_query = $mysqli->multi_query($query);
//        if ($multi_query=$mysqli->multi_query($query) === TRUE) {
//            echo "New records created successfully";
//        } else {
//            echo "Error: " . $query . "<br>" . $mysqli->error;
//        }
//dd($multi_query);
//        // Close the connection
//        sleep(60);
//        $mysqli->close();
//        $this->db->trans_complete();
//        if ($multi_query){
//            unlink(getcwd()."/my-assets/db/".$file_name);
//        } else {
//             echo "Database not created";
//        }
//        $this->session->set_userdata(array('message'=>display('successfully_updated')));
//        redirect(base_url('Csoft_setting/import_database'));
//    }

}