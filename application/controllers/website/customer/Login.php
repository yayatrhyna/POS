<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('website/customer/Llogin');
		$this->load->library('website/customer/User_auth');
		$this->load->model('website/customer/Logins');
		$this->load->model('Subscribers');
	}

	//Default loading for Home Index.
	public function index()
	{
		if ($this->user_auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url('customer/customer_dashboard'), TRUE, 302);
		}
		$content = $this->llogin->login_page();
		$this->template->full_website_html_view($content);
	}

	#============User login=========#
	public function login()
	{	
		if ($this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url('customer/customer_dashboard'), TRUE, 302);
		}
		$this->load->model('web_settings');
		$this->load->model('Soft_settings');
		$this->load->model('Blocks');
		$parent_category_list 	= $this->Logins->parent_category_list();
		$pro_category_list 		= $this->Logins->category_list();
		$best_sales 			= $this->Logins->best_sales();
		$footer_block 			= $this->Logins->footer_block();
		$slider_list 			= $this->web_settings->slider_list();
		$block_list 			= $this->Blocks->block_list(); 
		$currency_details 		= $this->Soft_settings->retrieve_currency_info();

		$data = array(
			'title' 		=> display('home'),
			'category_list' => $parent_category_list,
			'pro_category_list' => $pro_category_list,
			'slider_list' 	=> $slider_list,
			'block_list' 	=> $block_list,
			'best_sales' 	=> $best_sales,
			'footer_block' 	=> $footer_block,
			'currency' 		=> $currency_details[0]['currency_icon'],
			'position' 		=> $currency_details[0]['currency_position'],
		);
		$content = $this->parser->parse('website/customer/login',$data,true);
		$this->template->full_website_html_view($content);
	}

	#==============Do Login=======#
	public function do_login()
	{
		$error 		= '';
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');

		if ( $email == '' || $password == '' || $this->user_auth->login($email, $password) === FALSE ){
			$error = display('wrong_username_or_password');
		}

		if ( $error != '' ){
			$this->session->set_userdata(array('err_message'=>$error));
			$theme= $this->db->select('name')->from('themes')->where('status',1)->get()->row();

			if(strtolower($theme->name) === 'shatu'){
                $this->output->set_header("Location: ".base_url('login'), TRUE, 302);
            }else{
                $this->output->set_header("Location: ".base_url(''), TRUE, 302);
            }
		}else{
			$this->session->set_userdata(array('message'=>display('login_successfully')));
//			$this->output->set_header("Location: ".base_url('customer/customer_dashboard'), TRUE, 302);
            redirect(base_url());
		}
	}

	//Customer checkout login
	public function checkout_login()
	{
		$error 		= '';
		$email 		= $this->input->post('login_email');
		$password 	= $this->input->post('login_password');
		$remember_me 	= $this->input->post('remember_me');
        if ($remember_me == 1) {
            $email_cookie = array(
                'name'   =>  'email',
                'value'  =>  $email,
                'expire' =>  '86500',
            );
            $this->input->set_cookie($email_cookie);

            $pass_cookie = array(
                'name'   => 'password',
                'value'  => $password,
                'expire' => '86500',
            );
            $this->input->set_cookie($pass_cookie);
        }
		if ( $email == '' || $password == '' || $this->user_auth->login($email, $password) === FALSE ){
			$error = display('wrong_username_or_password');
		}

		if ( $error != '' ){
			$this->session->set_userdata(array('error_message'=>$error));
			echo "false";
		}else{
			$this->session->set_userdata(array('message'=>display('login_successfully')));
			echo "true";
		}
	}

	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");

		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,34);
			$rand_number=$number["$rand_value"];

			if(empty($con))
			{ 
				$con=$rand_number;
			}
			else
			{
				$con="$con"."$rand_number";}
			}
			return $con;
		}

		public function send_mail($email,$ptoken)
		{
			$CI =& get_instance();
			$CI->load->model('Soft_settings');
			$setting_detail = $CI->Soft_settings->retrieve_email_editdata();

			$subject = display("password_recovery");
			$message = display("password_recovery_details").'<br>'.base_url('/password_reset/'.$ptoken);

			$link = "<a href='".$message."'>".$message."</a>";
			$config = Array(
				'protocol' 		=> $setting_detail[0]['protocol'],
				'smtp_host' 	=> $setting_detail[0]['smtp_host'],
				'smtp_port' 	=> $setting_detail[0]['smtp_port'],
				'smtp_user' 	=> $setting_detail[0]['sender_email'], 
				'smtp_pass' 	=> $setting_detail[0]['password'], 
				'mailtype' 		=> $setting_detail[0]['mailtype'],
				'charset' 		=> 'utf-8'
			);

			$CI->load->library('email', $config);
			$CI->email->set_newline("\r\n");
			$CI->email->from($setting_detail[0]['sender_email']);
			$CI->email->to($email);
			$CI->email->subject($subject);
			$CI->email->message($link);
			// $CI->email->attach($file_path);
// jahangir.bdtask@gmail.com
			//pw: jahangir23255
			if($CI->email->send())
			{
				$CI->session->set_userdata(array('message'=>display('email_send_to_customer')));
				return true;
			}else{
				return false;
			}
		}


		public function forget_password()
		{
			$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');	

			$email = $this->input->post('email');

			
			
			// if($result){

				if ($this->form_validation->run())
				{
					$user = $this->get_user_info($email);
					$ptoken = date('ymdhis');
					if($user) {
						$email =$user[0]['customer_email'];

						$precdat = array(
							'email'     => $email,
							'token' => $ptoken,

						);
						$send_email = '';
						if (!empty($email)) {
							$send_email = $this->send_mail($email,$ptoken);
							$this->update_recovery_pass($precdat);
						}
						if($send_email){
							// $user_data['success'] = 'check Your email';
							// $user_data['status']  = true;

							echo 1;	 
						}else{
							// $user_data['exception'] = 'Sorry Email Not Send';
							// $user_data['status']  = false; 
							echo 2;
						}

					}else{
						// $user_data['exception']='Email Not found';
						// $user_data['status']  = false; 
						// echo json_encode(false);
						echo 3;
					}
				}else{
					// $user_data['exception']='please try again';
					// $user_data['status']  = false;
					echo 4; 
				}

				// echo json_encode($user_data);

			// }else{
				
			// 	echo json_encode(false);
			// }
		}



//check user exists on the database or not 
		public function get_user_info($email){
			$CI =& get_instance();
			$CI->load->model('Customers');
			$result    = $CI->Customers->get_user_info($email);
			return $result;
		}



		

		//set token to specific email 
		public function update_recovery_pass($precdat){
			$CI =& get_instance();
			$CI->load->model('Customers');
			$result    = $CI->Customers->set_token($precdat);
			return $result;
		}

		//show password reset form from email that we sent a link
		public function password_reset_form($token){
			$data['token'] = $token;
			$CI =& get_instance();
            $test = $CI->load->view('website/password_reset',$data);
			
		}


	//show password reset form
		public function forget_password_form(){
            $content = $this->llogin->forget_password_form();
            $this->template->full_website_html_view($content);

		}

		public function password_update(){
			$CI =& get_instance();
			$CI->load->model('Customers');
			$data=[
				'token'=>$this->input->post('token'),
				'password'=>	$this->input->post('password')
			];			

			$result = $CI->Customers->password_update($data);
			if($result){
				$this->session->set_userdata(array('message'=>display('successfully_updated')));
				redirect('/home');
			}

		}
		

	}