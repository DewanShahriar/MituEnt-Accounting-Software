<?php

defined('BASEPATH') OR exit('No direct script access allowed');


//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends CI_Controller {

	function __construct() {

		parent::__construct();

		//$this->load->library('pdf');

		$this->lang->load('content', $_SESSION['lang']);

		if (!isset($_SESSION['user_auth']) || $_SESSION['user_auth'] != true) {
			redirect('login', 'refresh');
		}
		if ($_SESSION['userType'] != 'admin')
			redirect('login', 'refresh');
		//Model Loading
		$this->load->model('AdminModel');
		$this->load->library("pagination");
		$this->load->helper("url");
		$this->load->helper("text");
		$this->load->helper("quickfunction"); 

		date_default_timezone_set("Asia/Dhaka");
	}

	public function index() {

		$data['balance_income_list'] = $this->AdminModel->get_income_balance_list();
		$data['balance_expense_list'] = $this->AdminModel->get_expense_balance_list();
		// echo "<pre>";
		// print_r($data['balance_income_list']);
		// exit;

		$data['total_income']  = $this->db->select_sum('tbl_accounts.amount')->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')->where('tbl_customer_balance.accounts_type', '1')->get('tbl_customer_balance')->row()->amount;
		$data['total_expense']  = $this->db->select_sum('tbl_accounts.amount')->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')->where('tbl_customer_balance.accounts_type', '0')->get('tbl_customer_balance')->row()->amount;
		
		$data['title']      = 'Admin Panel • HRSOFTBD Admin Panel';
		$data['page']       = 'backEnd/dashboard_view';
		$data['activeMenu'] = 'dashboard_view';

		$this->load->view('backEnd/master_page', $data);
	}

	public function add_user($param1 = '') {


		$messagePage['divissions']  = $this->db->get('tbl_divission')->result_array();
		$messagePage['userType']    = $this->db->get('user_type')->result_array();

		$messagePage['title']       = 'Add User Admin Panel • HRSOFTBD Admin Panel';
		$messagePage['page']        = 'backEnd/admin/add_user';
		$messagePage['activeMenu']  = 'add_user';
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$saveData['firstname']       = $this->input->post('first_name', true);
			$saveData['lastname']        = $this->input->post('last_name', true);
			$saveData['username']        = $this->input->post('user_name', true);
			$saveData['email']           = $this->input->post('email', true);
			$saveData['phone']           = $this->input->post('phone', true);
			$saveData['password']        = sha1($this->input->post('password', true));
			$saveData['address']         = $this->input->post('address', true);
			$saveData['roadHouse']       = $this->input->post('road_house', true);
			$saveData['userType']        = $this->input->post('user_type', true);
			$saveData['photo']           = 'assets/userPhoto/defaultUser.jpg';


			//This will returns as third parameter num_rows, result_array, result
			$username_check = $this->AdminModel->isRowExist('user', array('username' => $saveData['username']), 'num_rows');
			$email_check    = $this->AdminModel->isRowExist('user', array('email'    => $saveData['email']), 'num_rows');

			if ($username_check > 0 || $email_check > 0) {
				//Invalid message
				$messagePage['page'] = 'backEnd/admin/insertFailed';
				$messagePage['noteMessage']      = "<hr> UserName: " . $saveData['username'] . " can not be create.";
				if ($username_check > 0) {

					$messagePage['noteMessage'] .= '<br> Cause this username is already exist.';
				} else if ($email_check > 0) {

					$messagePage['noteMessage'] .= '<br> Cause this email is already exist.';
				}
			} else {
				//success
				$insertId = $this->AdminModel->saveDataInTable('user', $saveData, 'true');

				$messagePage['page'] = 'backEnd/admin/insertSuccessfull';
				$messagePage['noteMessage'] = "<hr> UserName: " . $saveData['username'] . " has been created successfully.";

				// Category allocate for users
				if (!empty($this->input->post('selectCategory', true))) {

					foreach ($this->input->post('selectCategory', true) as $cat_value) {

						$this->db->insert('category_user', array('userId' => $insertId, 'categoryId' => $cat_value));
					}
				}
			}
		}


		$this->load->view('backEnd/master_page', $messagePage);
	}

	public function edit_user($param1 = '') {
		// Update using post method 
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$saveData['firstname'] = $this->input->post('first_name', true, true);
			$saveData['lastname'] = $this->input->post('last_name', true, true);
			$saveData['phone'] = $this->input->post('phone', true, true);
			$saveData['address'] = $this->input->post('address', true, true);
			$saveData['roadHouse'] = $this->input->post('road_house', true, true);
			$saveData['userType'] = $this->input->post('user_type', true, true);
			$user_id = $this->input->post('user_id', true, true);


			$this->db->where('id', $user_id);
			$this->db->update('user', $saveData);
			
			$data['page'] = 'backEnd/admin/insertSuccessfull';
			$data['noteMessage'] = "<hr> Data has been Updated successfully.";

		} else if ($this->AdminModel->isRowExist('user', array('id' => $param1), 'num_rows') > 0) {

			$data['userDetails'] = $this->AdminModel->isRowExist('user', array('id' => $param1), 'result_array');

			$myupozilla_id = $this->db->get_where('tbl_upozilla', array("id"=>$data['userDetails'][0]['address']))->row();

			$data['myzilla_id'] = $myupozilla_id->zilla_id;
			$data['mydivision_id'] = $myupozilla_id->division_id;

			$data['divissions'] = $this->db->get('tbl_divission')->result();
		
			$data['distrcts'] = $this->db->get_where('tbl_zilla',array('divission_id'=>$data['mydivision_id']))->result();
			$data['upozilla'] = $this->db->get_where('tbl_upozilla',array('zilla_id'=>$data['myzilla_id']))->result();

			$data['userType'] = $this->db->get('user_type')->result_array();
			$data['user_id'] = $param1;
			$data['page'] = 'backEnd/admin/edit_user';

		} else {

			$data['page'] = 'errors/invalidInformationPage';
			$data['noteMessage'] = $this->lang->line('wrong_info_search');
		}
		
		$data['title'] = 'Users List Admin Panel • HRSOFTBD Admin Panel';
		$data['activeMenu'] = 'user_list';
		$this->load->view('backEnd/master_page', $data);
	}

	public function suspend_user($id, $setvalue) {

		$this->db->where('id', $id);
		$this->db->update('user', array('status' => $setvalue));
		$this->session->set_flashdata('message', 'Data Saved Successfully.');
		redirect('admin/user_list', 'refresh');
	}

	public function delete_user($id) {

		$old_image_url=$this->db->where('id', $id)->get('user')->row();
		$this->db->where('id', $id)->delete('user');
		if(isset($old_image_url->photo)){
			unlink($old_image_url->photo);
		}

		$this->session->set_flashdata('message', 'Data Deleted.');
		redirect('admin/user_list', 'refresh');
	}

	public function user_list() {

		$this->db->where('userType !=', 'admin');
		$data['myUsers'] = $this->db->get('user')->result_array();
		$data['title'] = 'Users List Admin Panel • HRSOFTBD Admin Panel';
		$data['page'] = 'backEnd/admin/user_list';
		$data['activeMenu'] = 'user_list';
		$this->load->view('backEnd/master_page', $data);
	}


	public function image_size_fix($filename, $width = 600, $height = 400, $destination = '') {

		// Content type
		// header('Content-Type: image/jpeg');
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);

		// Output 20 May, 2018 updated below part
		if ($destination == '' || $destination == null)
			$destination = $filename;

		$extention = pathinfo($destination, PATHINFO_EXTENSION);
		if ($extention != "png" && $extention != "PNG" && $extention != "JPEG" && $extention != "jpeg" && $extention != "jpg" && $extention != "JPG") {
 
			return true;
		}
		// Resample 
		$image_p = imagecreatetruecolor($width, $height);
		$black = imagecolorallocate($image_p, 0, 0, 0);

        // Make the background transparent
        imagecolortransparent($image_p, $black);

		$image   = imagecreatefromstring(file_get_contents($filename));
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

		

		if ($extention == "png" || $extention == "PNG") {
			imagepng($image_p, $destination, 9);
		} else if ($extention == "jpg" || $extention == "JPG" || $extention == "jpeg" || $extention == "JPEG") {
			imagejpeg($image_p, $destination, 70);
		} else {
			imagepng($image_p, $destination);
		}
		return true;
	}


	public function get_division() {

		$result = $this->db->select('id, name')->get('tbl_divission')->result();
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function get_zilla_from_division($division_id = 1) {

		$result = $this->db->select('id, name')->where('divission_id', $division_id)->get('tbl_zilla')->result();
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function get_upozilla_from_division_zilla($zilla_id = 1) {

		$result = $this->db->select('id, name')->where('zilla_id', $zilla_id)->get('tbl_upozilla')->result();
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function download_file($file_name = '', $fullpath='') {

		// echo $file_name; exit();
		$filePath = 'assets/ebookDocument/' . $file_name;

		if($file_name=='full' && ($fullpath != '' || $fullpath != null)) $filePath = $fullpath;

		if($_GET['file_path']) $filePath = $_GET['file_path'];
		// echo $filePath; exit();
		if (file_exists($filePath)) {
			$fileName = basename($filePath);
			$fileSize = filesize($filePath);

			// Output headers.
			header("Cache-Control: private");
			header("Content-Type: application/stream");
			header("Content-Length: " . $fileSize);
			header("Content-Disposition: attachment; filename=" . $fileName);

			// Output file.
			readfile($filePath);
			exit();
		} else {
			die('The provided file path is not valid.');
		}
	}
	
	public function profile($param1 = '')
	{

		$user_id            = $this->session->userdata('userid');
		$data['user_info']  = $this->AdminModel->get_user($user_id);


		$myzilla_id         = $data['user_info']->zilla_id;
		$mydivision_id      = $data['user_info']->division_id;

		$data['divissions'] = $this->db->get('tbl_divission')->result();

		$data['distrcts']   = $this->db->get_where('tbl_zilla', array('divission_id' => $mydivision_id))->result();
		$data['upozilla']   = $this->db->get_where('tbl_upozilla', array('zilla_id'  => $myzilla_id))->result();

		if ($param1 == 'update_photo') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			    
			    
			    //exta work
                $path_parts               = pathinfo($_FILES["photo"]['name']);
                $newfile_name             = preg_replace('/[^A-Za-z]/', "", $path_parts['filename']);
                $dir                      = date("YmdHis", time());
                $config['file_name']      = $newfile_name . '_' . $dir;
                $config['remove_spaces']  = TRUE;
                //exta work
                $config['upload_path']    = 'assets/userPhoto/';
                $config['max_size']       = '20000'; //  less than 20 MB
                $config['allowed_types']  = 'jpg|png|jpeg|jpg|JPG|JPG|PNG|JPEG';

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('photo')) {

                    // case - failure
					$upload_error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message', "Failed to update image.");

                } else {

                    $upload                 = $this->upload->data();
                    $newphotoadd['photo']   = $config['upload_path'] . $upload['file_name'];

                    $old_photo              = $this->db->where('id', $user_id)->get('user')->row()->photo;
                    
                    if(file_exists($old_photo)) unlink($old_photo);

                    $this->image_size_fix($newphotoadd['photo'], 200, 200);

                    $this->db->where('id', $user_id)->update('user', $newphotoadd);

                    $this->session->set_userdata('userPhoto', $newphotoadd['photo']);
					$this->session->set_flashdata('message', 'User Photo Updated Successfully!');
					
					redirect('admin/profile','refresh');
                }
                
			  }
			  
		}else if($param1 == 'update_pass'){

		   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		       
			   $old_pass    = sha1($this->input->post('old_pass', true)); 
			   $new_pass    = sha1($this->input->post('new_pass', true)); 
			   $user_id     = $this->session->userdata('userid');

			   $get_user    = $this->db->get_where('user',array('id'=>$user_id, 'password'=>$old_pass));
			   $user_exist  = $get_user->row();

			   if($user_exist){
			       
					$this->db->where('id',$user_id)
							->update('user',array('password'=>$new_pass));
					$this->session->set_flashdata('message', 'Password Updated Successfully');
					redirect('admin/profile','refresh');
					
			   }else{
			       
				    $this->session->set_flashdata('message', 'Password Update Failed');
				    redirect('admin/profile','refresh');
				   
			   }
			   
			}
			
		}else if($param1 == 'update_info'){

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			    
				$update_data['firstname']   = $this->input->post('firstname', true);
				$update_data['lastname']    = $this->input->post('lastname', true);
				$update_data['roadHouse']   = $this->input->post('roadHouse', true);
				$update_data['address']     = $this->input->post('address', true);


				$db_email     = $this->db->where('id!=', $user_id)->where('email', $this->input->post('email', true))->get('user')->num_rows();
				$db_username  = $this->db->where('id!=', $user_id)->where('username', $this->input->post('username', true))->get('user')->num_rows();


				if ( $db_username == 0) {

					 $update_data['username']    = $this->input->post('username', true);
					 
				}if ( $db_email == 0) {

					 $update_data['email']       = $this->input->post('email', true);
					 
				}
				

    			if ($this->AdminModel->update_pro_info($update_data, $user_id)) {
    			    
    			    $this->session->set_userdata('username_first', $update_data['firstname']);
    			    $this->session->set_userdata('username_last', $update_data['lastname']);
    			    $this->session->set_userdata('username', $update_data['username']);
    			    
    				$this->session->set_flashdata('message', 'Information Updated Successfully!');
    				redirect('admin/profile', 'refresh');
    				
    			} else {
    			    
    				$this->session->set_flashdata('message', 'Information Update Failed!');
    				redirect('admin/profile', 'refresh');
    				
    			} 
				
			}
		}
		
		$data['title']        = 'Profile Admin Panel • HRSOFTBD Admin Panel';
		$data['activeMenu']   = 'Profile';
		$data['page']         = 'backEnd/admin/profile';
		
		$this->load->view('backEnd/master_page',$data);
	}
	

	public function projects($param1 = '', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$insert_project['name']               	= $this->input->post('name', true);
				$insert_project['address']            	= $this->input->post('address', true);
				$insert_project['remark']            	= $this->input->post('remark', true);
				$insert_project['project_start_date'] 	= date("Y-m-d", strtotime($this->input->post('project_start_date', true)));
				$insert_project['insert_by']          	= $this->session->userdata('userid');

				if ($this->AdminModel->add_project($insert_project)) {

					$this->session->set_flashdata('message','Project Added Successfully!');
					redirect('admin/projects','refresh');

				} else {

					$this->session->set_flashdata('message','Project Add Failed!');
					redirect('admin/projects','refresh');

				}
				
			}
			
		} elseif ($param1 == 'edit') {

			$param2 = $this->input->post('project_id', true);

			$data['edit_info'] = $this->db->get_where('tbl_project',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$update_project['name']               = $this->input->post('name', true);
					$update_project['remark']             = $this->input->post('remark', true);
					$update_project['address']            = $this->input->post('address', true);
					$update_project['project_start_date'] = date("Y-m-d", strtotime($this->input->post('project_start_date', true)));


					if ($this->AdminModel->project_update($update_project,$param2)) {

						$this->session->set_flashdata('message','Project Updated Successfully!');
						redirect('admin/projects','refresh');

					} else {

						$this->session->set_flashdata('message','Project Update Failed!');
						redirect('admin/projects','refresh');

					}
				
				}

			}else{

			}

			

		} elseif ($param1 == 'statuschange') {

			$param2 = $this->input->post('project_note_id', true);

			$data['edit_info'] = $this->db->get_where('tbl_project',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {


					$update_project['remark']             = $this->input->post('remark_status', true);
					$update_project['completed']          = $this->input->post('project_status', true);

					if ($this->AdminModel->project_update($update_project,$param2)) {

						$this->session->set_flashdata('message','Project Updated Successfully!');
						redirect('admin/projects','refresh');

					} else {

						$this->session->set_flashdata('message','Project Update Failed!');
						redirect('admin/projects','refresh');

					}
				
				}

			}else{
                return false;
			}



		} elseif ($param1 == 'delete' && $param2 > 0) {

			$projects_delete = $this->db->where('id',$param2)->delete('tbl_project');

			if ($projects_delete) {

				$this->session->set_flashdata('message','Project Deleted Successfully!');
				redirect('admin/projects','refresh');

			} else {

				$this->session->set_flashdata('message','Project Delete Failed!');
				redirect('admin/projects','refresh');

			}

		}


		$data['title']      = 'Project';
		$data['activeMenu'] = 'projects';
		$data['page']       = 'backEnd/admin/projects';
		$data['projects']   = $this->AdminModel->project_lists();

		
		$this->load->view('backEnd/master_page',$data);
	}


	public function accounthead($param1 = '', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$insert_accounthead['name']      = $this->input->post('name', true);
				$insert_accounthead['category']  = $this->input->post('category', true);
				$insert_accounthead['parent_id'] = $this->input->post('parent_id', true);
				$insert_accounthead['insert_time'] = date('Y-m-d H:i');

				if ($this->AdminModel->add_accounthead($insert_accounthead)) {

					$this->session->set_flashdata('message','Account Head Added Successfully!');
					redirect('admin/accounthead','refresh');

				} else {

					$this->session->set_flashdata('message','Account Head Add Failed!');
					redirect('admin/accounthead','refresh');

				}
				
			}			

		}elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info'] = $this->db->get_where('tbl_account_head',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				$data['edit_info']    = $data['edit_info']->row();
				$data['edit_info_id'] = $param2;

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$update_accounthead['name']      = $this->input->post('name', true);
				$update_accounthead['category']  = $this->input->post('category', true);
				$update_accounthead['parent_id'] = $this->input->post('parent_id', true);
				$update_accounthead['insert_time'] = date('Y-m-d H:i');

				if ($this->AdminModel->accounthead_update($update_accounthead, $param2)) {

					$this->session->set_flashdata('message','Account Head Updated Successfully!');
					redirect('admin/accounthead/add','refresh');

				} else {

					$this->session->set_flashdata('message','Account Head Update Failed!');
					redirect('admin/accounthead/edit/'.$param2,'refresh');

				}
				
			}

			} else {

				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/accounthead','refresh');
			}
			

		}elseif ($param1 == 'delete' && $param2 > 0) {

			$account_head_delete = $this->db->where('id',$param2)->where('status', '1')->delete('tbl_account_head');

			if ($account_head_delete) {

				$this->session->set_flashdata('message','Account Head Deleted Successfully!');
				redirect('admin/accounthead','refresh');

			} else {

				$this->session->set_flashdata('message','Account Head Delete Failed!');
				redirect('admin/accounthead','refresh');

			}

		}

		$data['title']        = 'Account Head';
		$data['activeMenu']   = 'account_head';
		$data['page']         = 'backEnd/admin/account_head';
		$data['parent_zero']  = $this->db->get_where('tbl_account_head',array('parent_id'=>0))->result();
		$data['account_head'] = $this->db->order_by('id','desc')->get('tbl_account_head')->result();
		$this->load->view('backEnd/master_page',$data);
	}

	public function get_account_head ($type){
	    
	    $result = $this->db->where('category', $type)->or_where('category','2')->get('tbl_account_head')->result();
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		
	}

	public function accounts($param1 = 'add', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add' ) {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$insert_account['date']        		= date("Y-m-d", strtotime($this->input->post('date', true)));
				$insert_account['insert_by']   		= $this->session->userdata('userid');
				$insert_account['accounts_type']   		= $this->input->post('accounts_type', true);
				$insert_account['insert_time'] 		= date('Y-m-d H:i');

				foreach ($this->input->post('amount', true) as $key => $value) {

					if($value == 0 ) continue; 

					$insert_account['amount']          = $value;
					$insert_account['account_head_id'] = $this->input->post('account_head_id', true)[$key];
					$insert_account['description']     = $this->input->post('description', true)[$key];
					
					
					$this->AdminModel->add_accounts($insert_account);
				}

				if(true){
					$this->session->set_flashdata('message','Account Added Successfully!');
					redirect('admin/accounts','refresh');

				} else{
					$this->session->set_flashdata('message','Account Added Failed!');
					redirect('admin/accounts','refresh');
				}
				
			}


			$data['title']        = 'Accounts Add';
			$data['activeMenu']   = 'accounts_add';
			$data['page']         = 'backEnd/admin/accounts_add';
			
			$data['account_head'] = $this->db->get('tbl_account_head')->result();
			

		} elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info'] = $this->db->get_where('tbl_accounts',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				$data['edit_info']    = $data['edit_info']->row();
				$data['edit_info_id'] = $param2;

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$update_account['date']        		= date("Y-m-d", strtotime($this->input->post('date', true)));
					$update_account['amount']          = $this->input->post('amount', true);
					$update_account['account_head_id'] = $this->input->post('account_head_id', true);
					$update_account['description']     = $this->input->post('description', true);
					$update_account['status']        = $this->input->post('editable', true);

					if ($this->AdminModel->accounts_update($update_account,$param2)) {

						$this->session->set_flashdata('message', 'Account Updated Successfully!');
						redirect('admin/accounts/list', 'refresh');

					} else {

						$this->session->set_flashdata('message', 'Account Update Failed!');
						redirect('admin/accounts/edit/'.$param2, 'refresh');

					}
				}
			} else {
				
				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/accounts/list','refresh');
			}

			$data['title']        = 'Accounts Update';
			$data['activeMenu']   = 'accounts_edit';
			$data['page']         = 'backEnd/admin/accounts_edit';
			
			$data['account_head'] = $this->db->get('tbl_account_head')->result();
			

		}elseif ($param1 == 'delete' && $param2 > 0) {

			$account_delete = $this->db->where('id',$param2)->where('status', '1')->delete('tbl_accounts');

			if ($account_delete) {

				$this->session->set_flashdata('message','Account Deleted Successfully!');
				redirect('admin/accounts/list','refresh');

			} else {

				$this->session->set_flashdata('message','Account Delete Failed!');
				redirect('admin/accounts/list','refresh');

			}

		}elseif ($param1 == 'list') {

			$data['search'] = array();

    		$data['search']['start_date']       = '';
    		$data['search']['end_date']         = '';

    		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			
				if ($this->input->post('start_date', true)){

					$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date', true)));
				}

				if ($this->input->post('end_date', true)){

					$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->post('end_date', true)));
				}
			}

			$config = array();
	        $config["base_url"] = base_url() . "admin/accounts/list";
	        $config["total_rows"] = $this->AdminModel->all_accounts_count($data['search']);
	        $config["per_page"] = 10;
	        $config["uri_segment"] = 4;

	        //custom
	        $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            
            $config['first_link'] = "First";
            $config['last_link'] = "Last";
            
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
                
            $config['prev_link'] = '«';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            
            $config['next_link'] = '»';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

	        $this->pagination->initialize($config);

	        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

	        $data["links"] = $this->pagination->create_links();

			$data['accounts_list'] = $this->AdminModel->all_accounts_lists($config["per_page"], $page, $data['search']);
			
		
			$data['title']        = 'Accounts List';
			$data['activeMenu']   = 'accounts_list';
			$data['page']         = 'backEnd/admin/accounts_list';
			

		}

		
		$this->load->view('backEnd/master_page',$data);
	}
	
	

	public function report($param1 = 'profit_loss', $param2 = '', $param3 = '')
	{
		if ($param1 == 'profit_loss') {

			$data['title']      = 'Project Profit Loss';
			$data['activeMenu'] = 'profit_loss';
			$data['projects']   = $this->db->get('tbl_project')->result();
			$data['page']       = 'backEnd/admin/project_profit_loss';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$data['project_id'] = $this->input->post('project_id', true);

				$data['start_date'] = $this->input->post('start_date', true) != null ? date("Y-m-d", strtotime($this->input->post('start_date', true))) : '' ;

				$data['end_date']   = $this->input->post('end_date', true) != null ? date("Y-m-d", strtotime($this->input->post('end_date', true))) : '' ;

				$data['search_data']  = $this->AdminModel->project_search($data['project_id']);

				$data['account_data'] = $this->AdminModel->get_account($data['project_id'], $data['start_date'], $data['end_date']);

			}

		}elseif ($param1 == 'income_expance') {

			$data['title']      = 'Daily Income Expence';
			$data['activeMenu'] = 'income_expance';
			$data['page']       = 'backEnd/admin/daily_income_expence';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$data['income_expance'] = $this->input->post('income_expance', true);

				$data['start_date']     = $this->input->post('start_date', true) != null ? date("Y-m-d", strtotime($this->input->post('start_date', true))) : '' ;

				$data['end_date']       = $this->input->post('end_date', true) != null ? date("Y-m-d", strtotime($this->input->post('end_date', true))) : '' ;

				$data['search_data']    = $this->AdminModel->income_expence_search($data['income_expance'], $data['start_date'], $data['end_date']);
			}

		}elseif ($param1 == 'project_cost_analysis') {

			$data['title']       = 'Project Cost Analysis';
			$data['activeMenu']  = 'project_cost_analysis';
			$data['projects']    = $this->db->get('tbl_project')->result();
			$data['accounthead'] = $this->db->get('tbl_account_head')->result();
			$data['page']        = 'backEnd/admin/project_cost_analysis';
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$data['project_id']      = $this->input->post('project_id', true);
				$data['account_head_id'] = $this->input->post('account_head_id', true);

				$data['start_date']      = $this->input->post('start_date', true) != null ? date("Y-m-d", strtotime($this->input->post('start_date', true))) : '' ;

				$data['end_date']        = $this->input->post('end_date', true) != null ? date("Y-m-d", strtotime($this->input->post('end_date', true))) : '' ;

				$data['project_data']    = $this->AdminModel->project_search($data['project_id']);

				$data['search_data']     = $this->AdminModel->project_cost_analysis_search($data['project_id'],$data['account_head_id'], $data['start_date'], $data['end_date']);

			}

		}elseif ($param1 == 'search') {

			$search_data['accounthead_id'] = $this->input->get('account_head_id', true);
			$search_data['project_id']     = $this->input->get('project_id', true);

			$data['accounthead_id']        = $search_data['accounthead_id'];


			$find_withdraw_id      = $this->db->where('cash_in_id', $search_data['accounthead_id'])->or_where('withraw_id', $search_data['accounthead_id'])->get('tbl_cashidwithdrawjoin');

			if ($find_withdraw_id->num_rows() > 0) {

				$withdraw_id = $find_withdraw_id->row()->withraw_id;

				if($withdraw_id == $data['accounthead_id']) {
				
					$search_data['accounthead_id'] = $data['accounthead_id'] = $find_withdraw_id->row()->cash_in_id;

				}
                
                $data['is_withdraw'] = true;

				$data['search_info'] = $this->AdminModel->cash_in_withdraw_join_search($search_data['accounthead_id'], $withdraw_id, $search_data['project_id']);
				/*echo "<pre>";
				print_r($data['search_info']);
				echo "</pre>";
				exit();*/

			}else {
                
                $data['is_withdraw'] = false;
                
				$data['search_info'] = $this->AdminModel->search_project($search_data['accounthead_id'], $search_data['project_id']);
			}

			$data['title']       = 'Account Head';
			$data['activeMenu']  = 'account_head';
			$data['page']        = 'backEnd/admin/account_head_search';
			
		}elseif ($param1 == 'all_report') {

			$data['title']                     = 'All Report';
			$data['activeMenu']                = 'all_report';
			$data['page']                      = 'backEnd/admin/all_report';
			$data['projects']                  = $this->db->get('tbl_project')->result();

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$project_id = $this->input->post('project_id', true);
				redirect('admin/all_report_print/'.$project_id, 'refresh');
				
			}else{

			}

		}

		$this->load->view('backEnd/master_page',$data);
	}
	
	public function all_report_print($project_id)
	{
		
		$start_date = $this->db->order_by('date','asc')->get_where('tbl_accounts',array('project_id'=>$project_id))->row()->date;

		$end_date   = $this->db->order_by('date','desc')->get_where('tbl_accounts',array('project_id'=>$project_id))->row()->date;
		
		$findsearial = $this->db->select('date')->where('project_id', $project_id)->order_by('id', 'asc')->get('tbl_serialgenerate')->result();

		$temp_serial_array = array("0");
		$data['serial_no'] = 0;

		foreach ($findsearial as $key => $datevalue) {

			array_push($temp_serial_array, $datevalue->date);

		}
		

		while (strtotime($start_date) <= strtotime($end_date)) {
            
            
			$data['project_id'] = $project_id;
			$data['start_date'] = $start_date;

			$data['print_break'] = true;
			
			$data['todays_expenditure']        = $this->AdminModel->todays_expenditure($data['project_id'], $data['start_date']);
			
			if(count($data['todays_expenditure']) > 0) {
			    
			    $data['previous_accounts_income']  = $this->AdminModel->previous_accounts($data['project_id'], $data['start_date'], 1);

    			$data['previous_accounts_expense'] = $this->AdminModel->previous_accounts($data['project_id'], $data['start_date'], 0);
    
    			$data['todays_depostite']          = $this->AdminModel->todays_depostite($data['project_id'], $data['start_date'], 1);
    
    			$data['todays_all_depostite']      = $this->AdminModel->todays_all_depostite($data['project_id'], $data['start_date'], 1);
    			
    			$data['previous_withdraw'] = $this->AdminModel->previous_accounts($data['project_id'], $data['start_date'], 2);

    			$data['todays_withdraw'] = $this->AdminModel->todays_withdraw($data['project_id'], $data['start_date']);
    			
    			
    			$data['serial_no'] = array_search($data['start_date'], $temp_serial_array);
    			
    
    			$data['project_info']  	           = $this->AdminModel->project_search($data['project_id']);
    
    			$this->load->view('backEnd/admin/print_income_expance',$data);
    			
    			
			}
			
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));

			
		}
	}

	public function dailyledger()
	{

		$data['title']       = 'Daily Ledger';
		$data['activeMenu']  = 'daily_ledger';
		$data['page']        = 'backEnd/admin/daily_ledger';
		$data['projects']    = $this->db->get('tbl_project')->result();
		$this->load->view('backEnd/master_page',$data);	
	}

	public function print_income_expance()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    
			$search_data['project_id']         = $this->input->post('project_id', true);
			$data['start_date'] 	           = $this->input->post('start_date', true) != null ? date("Y-m-d", strtotime($this->input->post('start_date', true))) : '' ;

			$data['todays_expenditure']        = $this->AdminModel->todays_expenditure($search_data['project_id'], $data['start_date']);
			
			$findsearial = $this->db->select('date')->where('project_id', $search_data['project_id'])->order_by('id', 'asc')->get('tbl_serialgenerate')->result();

			$data['serial_no'] = 0;

			foreach ($findsearial as $key => $datevalue) {
				
				if($datevalue->date == $data['start_date']) {
					$data['serial_no'] = $key+1;
					break;
				}
			}
			
			if(count($data['todays_expenditure']) > 0 || true) {
			
    			$data['previous_accounts_income']  = $this->AdminModel->previous_accounts($search_data['project_id'], $data['start_date'], 1);
    
    			$data['previous_accounts_expense'] = $this->AdminModel->previous_accounts($search_data['project_id'], $data['start_date'], 0);
    
    			$data['todays_depostite']          = $this->AdminModel->todays_depostite($search_data['project_id'], $data['start_date'], 1);
    			$data['todays_all_depostite']      = $this->AdminModel->todays_all_depostite($search_data['project_id'], $data['start_date'], 1);
                
                
                $data['previous_withdraw'] = $this->AdminModel->previous_accounts($search_data['project_id'], $data['start_date'], 2);

    			$data['todays_withdraw'] = $this->AdminModel->todays_withdraw($search_data['project_id'], $data['start_date']);
    			
    			
    			$data['project_info']  	           = $this->AdminModel->project_search($search_data['project_id']);
    	        
    			$this->load->view('backEnd/admin/print_income_expance',$data);
    			
			} else {
			    echo "<script>window.close();</script>";
			    return false;
			    
			}
			
		}else{
			redirect('admin/dailyledger','refresh');
		}	
	}

	public function cashin_withdraw_join($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$insert_cashin_withdraw['cash_in_id']  = $this->input->post('cash_in_id', true);

				$insert_cashin_withdraw['withraw_id']  = $this->input->post('withraw_id', true);

				$insert_cashin_withdraw['insert_by']   = $_SESSION['userid'];
				$insert_cashin_withdraw['insert_time'] = date('Y-m-d H:i:s');

				$join_exist = $this->db->get_where('tbl_cashidwithdrawjoin',array('cash_in_id'=>$insert_cashin_withdraw['cash_in_id'], 'withraw_id'=>$insert_cashin_withdraw['withraw_id']))->num_rows();

				if ($join_exist > 0) {

					$this->session->set_flashdata('message','Join Already Exists!');
						redirect('admin/cashin_withdraw_join','refresh');

				}else{

					$add_cashin_withdraw = $this->db->insert('tbl_cashidwithdrawjoin',$insert_cashin_withdraw);

					if ($add_cashin_withdraw) {

						$this->session->set_flashdata('message','Cash In And Withdraw Added Successfully');
						redirect('admin/cashin_withdraw_join','refresh');

					} else {

						$this->session->set_flashdata('message','Cash In And Withdraw Add Failed');
						redirect('admin/cashin_withdraw_join','refresh');

					}
				}

			}	

		}elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info'] = $this->db->get_where('tbl_cashidwithdrawjoin',array('id'=>$param2));
			
			if ($data['edit_info']->num_rows() > 0) {

				$data['edit_info'] = $data['edit_info']->row();
				$data['edit_info_id'] = $param2;

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$update_cashin_withdraw['cash_in_id']  = $this->input->post('cash_in_id', true);

					$update_cashin_withdraw['withraw_id']  = $this->input->post('withraw_id', true);

					$cashin_withdraw_update = $this->db->where('id',$param2)->update('tbl_cashidwithdrawjoin',$update_cashin_withdraw);

					if ($cashin_withdraw_update) {

						$this->session->set_flashdata('message','Cash In And Withdraw Updated Successfully');
						redirect('admin/cashin_withdraw_join/edit/'.$param2,'refresh');

					} else {

						$this->session->set_flashdata('message','Cash In And Withdraw Update Failed');
						redirect('admin/cashin_withdraw_join/edit/'.$param2,'refresh');

					}

			}

			} else {

				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/cashin_withdraw_join','refresh');
			}
			

		}elseif ($param1 == 'delete' && $param2 > 0) {

			$cashin_withdraw_delete = $this->db->where('id',$param2)->delete('tbl_cashidwithdrawjoin');

			if ($cashin_withdraw_delete) {

				$this->session->set_flashdata('message','Cash In And Withdraw Deleted Successfully');
				redirect('admin/cashin_withdraw_join','refresh');

			} else {

				$this->session->set_flashdata('message','Cash In And Withdraw Delete Failed');
				redirect('admin/cashin_withdraw_join','refresh');

			}

		}

		$data['title']            = 'Cash In and Withdraw';
		$data['activeMenu']       = 'cashin_withdraw_join';
		$data['page']             = 'backEnd/admin/cashin_withdraw_join';
		$data['accounthead']      = $this->db->get('tbl_account_head')->result();
		$data['cash_in_withdraw'] = $this->db->order_by('id','desc')->get('tbl_cashidwithdrawjoin')->result();
		$this->load->view('backEnd/master_page',$data);
	}

	public function mail_setting()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			foreach ($this->input->post('mail_setting_id', true) as $key => $id_value) {

				$id    = $id_value;
				$value = $this->input->post('value', true)[$key];

				$this->db->where('id', $id)->update('tbl_mail_send_setting', array('value'=>$value));

			}

			$this->session->set_flashdata('message','Mail Send Setting Updated Successfully!');
			redirect('admin/mail_setting','refresh');
		}

		$data['title']             = 'Mail Setting';
		$data['activeMenu']        = 'mail_setting';
		$data['page']              = 'backEnd/admin/mail_setting';
		$data['mail_setting_info'] = $this->db->get('tbl_mail_send_setting')->result();
		$this->load->view('backEnd/master_page', $data);
	}

	//customer crud

	public function customer($param1 = 'add', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$customer_data['name']           = $this->input->post('name', true);
				$customer_data['phone']          = $this->input->post('phone', true);
				
				$customer_data['email']          = $this->input->post('email', true);
				$customer_data['address']        = $this->input->post('address', true);
				$customer_data['insert_by']      = $_SESSION['userid'];
				$customer_data['insert_time']    = date('Y-m-d H:i');

				if (!empty($_FILES['photo']['name'])) {

					$path_parts                   = pathinfo($_FILES["photo"]['name']);
					$newfile_name                 = preg_replace('/[^A-Za-z]/', "", $path_parts['filename']);
					$dir                          = date("YmdHis", time());
					$config_c['file_name']        = $newfile_name . '_' . $dir;
					$config_c['remove_spaces']    = TRUE;
					$config_c['upload_path']      = 'assets/customerPhoto/';
					$config_c['max_size']         = '20000'; //  less than 20 MB
					$config_c['allowed_types']    = 'jpg|png|jpeg|jpg|JPG|JPG|PNG|JPEG';

					$this->load->library('upload', $config_c);
					$this->upload->initialize($config_c);
					if (!$this->upload->do_upload('photo')) {

					} else {

						$upload_c = $this->upload->data();
						$customer_data['photo'] = $config_c['upload_path'] . $upload_c['file_name'];
						$this->image_size_fix($customer_data['photo'], 400, 400);
					}
					
				}

				$add_customers = $this->db->insert('tbl_customers',$customer_data);

				if ($add_customers) {

					$this->session->set_flashdata('message','Customer Added Successfully!');
					redirect('admin/customer/list','refresh');

				} else {

				   $this->session->set_flashdata('message','Customer Add Failed!');
					redirect('admin/customer','refresh');
				}
			}

			$data['title']             = 'Customer Add';
			$data['activeMenu']        = 'customer_add';
			$data['page']              = 'backEnd/admin/customer_add';
			
		} elseif ($param1 == 'list' ) {

			$config = array();
	        $config["base_url"] = base_url() . "admin/customer/list";
	        $config["total_rows"] = $this->db->count_all('tbl_customers');
	        $config["per_page"] = 10;
	        $config["uri_segment"] = 4;

	        //custom
	        $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            
            $config['first_link'] = "First";
            $config['last_link'] = "Last";
            
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
                
            $config['prev_link'] = '«';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            
            $config['next_link'] = '»';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

	        $this->pagination->initialize($config);

	        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

	        $data["links"] = $this->pagination->create_links();

			

			$data['customer_list'] = $this->db->limit($config["per_page"], $page)->get('tbl_customers')->result();

			$data['title']        = 'customer List';
			$data['activeMenu']   = 'customer_list';
			$data['page']         = 'backEnd/admin/customer_list';

		   
		} elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info']   = $this->db->get_where('tbl_customers',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				$data['edit_info']    = $data['edit_info']->row();

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$customer_data['name']           = $this->input->post('name', true);
					$customer_data['phone']          = $this->input->post('phone', true);
					
					$customer_data['email']          = $this->input->post('email', true);
					$customer_data['address']        = $this->input->post('address', true);

					if (!empty($_FILES['photo']['name'])) {

						$path_parts                 = pathinfo($_FILES["photo"]['name']);
						$newfile_name               = preg_replace('/[^A-Za-z]/', "", $path_parts['filename']);
						$dir                        = date("YmdHis", time());
						$config_c['file_name']      = $newfile_name . '_' . $dir;
						$config_c['remove_spaces']  = TRUE;
						$config_c['upload_path']    = 'assets/customerPhoto/';
						$config_c['max_size']       = '20000'; //  less than 20 MB
						$config_c['allowed_types']  = 'jpg|png|jpeg|jpg|JPG|JPG|PNG|JPEG';

						$this->load->library('upload', $config_c);
						$this->upload->initialize($config_c);
						if (!$this->upload->do_upload('photo')) {

						} else {

							$upload_c = $this->upload->data();
							$customer_data['photo'] = $config_c['upload_path'] . $upload_c['file_name'];
							$this->image_size_fix($customer_data['photo'], 400, 400);
						}
						
					}

					if ($this->AdminModel->updateCustomer($customer_data, $param2)) {

						$this->session->set_flashdata('message','customer  Updated Successfully!');
						redirect('admin/customer/list', 'refresh');

					} else {

					   $this->session->set_flashdata('message','customer Update Failed!');
						redirect('admin/customer/edit/'.$param2, 'refresh');
					}
				}

			} else {

				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/customer/list','refresh');
			}

			$data['title']      = 'customer Edit';
			$data['activeMenu'] = 'customer_edit';
			$data['page']       = 'backEnd/admin/customer_edit';
			
		   
		} elseif($param1 == 'delete' && $param2 > 0) {
			
		   	if ($this->AdminModel->delete_customer($param2)) {

				$this->session->set_flashdata('message','customer  Deleted Successfully!');
				redirect('admin/customer/list','refresh');

			} else {

			    $this->session->set_flashdata('message','customer Deleted Failed!');
				redirect('admin/customer/list','refresh');
			}
			
		} else {

			$this->session->set_flashdata('message', 'Wrong Attempt!');
			redirect('admin/customer/list','refresh');

		}

		$this->load->view('backEnd/master_page',$data);        
	}

	// transaction medium

	public function transaction_medium($param1 = 'add', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$transaction_data['name']           = $this->input->post('name', true);
				$transaction_data['number']          = $this->input->post('number', true);
				
				
				$transaction_data['insert_by']      = $_SESSION['userid'];
				$transaction_data['insert_time']    = date('Y-m-d H:i');

				if (!empty($_FILES['photo']['name'])) {

					$path_parts                   = pathinfo($_FILES["photo"]['name']);
					$newfile_name                 = preg_replace('/[^A-Za-z]/', "", $path_parts['filename']);
					$dir                          = date("YmdHis", time());
					$config_c['file_name']        = $newfile_name . '_' . $dir;
					$config_c['remove_spaces']    = TRUE;
					$config_c['upload_path']      = 'assets/transactionIcon/';
					$config_c['max_size']         = '20000'; //  less than 20 MB
					$config_c['allowed_types']    = 'jpg|png|jpeg|jpg|JPG|JPG|PNG|JPEG';

					$this->load->library('upload', $config_c);
					$this->upload->initialize($config_c);
					if (!$this->upload->do_upload('photo')) {

					} else {

						$upload_c = $this->upload->data();
						$transaction_data['icon'] = $config_c['upload_path'] . $upload_c['file_name'];
						$this->image_size_fix($transaction_data['icon'], 200, 200);
					}
					
				}

				$add_customers = $this->db->insert('tbl_transaction_medium',$transaction_data);

				if ($add_customers) {

					$this->session->set_flashdata('message','transaction_medium Added Successfully!');
					redirect('admin/transaction-medium/list','refresh');

				} else {

				   $this->session->set_flashdata('message','transaction_medium Add Failed!');
					redirect('admin/transaction-medium','refresh');
				}
			}

			$data['title']             = 'Transaction Medium Add';
			$data['activeMenu']        = 'transaction_medium_add';
			$data['page']              = 'backEnd/admin/transaction_medium_add';
			
		} elseif ($param1 == 'list' ) {


			$data['transaction_medium_list'] = $this->db->get('tbl_transaction_medium')->result();

			$data['title']        = 'transaction_medium List';
			$data['activeMenu']   = 'transaction_medium_list';
			$data['page']         = 'backEnd/admin/transaction_medium_list';

		   
		} elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info']   = $this->db->get_where('tbl_transaction_medium',array('id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {

				$data['edit_info']    = $data['edit_info']->row();

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$transaction_data['name']           = $this->input->post('name', true);
					$transaction_data['number']         = $this->input->post('number', true);
				

					if (!empty($_FILES['photo']['name'])) {

						$path_parts                 = pathinfo($_FILES["photo"]['name']);
						$newfile_name               = preg_replace('/[^A-Za-z]/', "", $path_parts['filename']);
						$dir                        = date("YmdHis", time());
						$config_c['file_name']      = $newfile_name . '_' . $dir;
						$config_c['remove_spaces']  = TRUE;
						$config_c['upload_path']    = 'assets/transactionIcon/';
						$config_c['max_size']       = '20000'; //  less than 20 MB
						$config_c['allowed_types']  = 'jpg|png|jpeg|jpg|JPG|JPG|PNG|JPEG';

						$this->load->library('upload', $config_c);
						$this->upload->initialize($config_c);
						if (!$this->upload->do_upload('photo')) {

						} else {

							$upload_c = $this->upload->data();
							$transaction_data['icon'] = $config_c['upload_path'] . $upload_c['file_name'];
							$this->image_size_fix($transaction_data['icon'], 200, 200);
						}
						
					}

					if ($this->AdminModel->updateTransactionMedium($transaction_data, $param2)) {

						$this->session->set_flashdata('message','transaction-medium  Updated Successfully!');
						redirect('admin/transaction-medium/list', 'refresh');

					} else {

					   $this->session->set_flashdata('message','transaction-medium Update Failed!');
						redirect('admin/transaction-medium/edit/'.$param2, 'refresh');
					}
				}

			} else {

				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/transaction-medium/list','refresh');
			}

			$data['title']      = 'transaction_medium Edit';
			$data['activeMenu'] = 'transaction_medium_edit';
			$data['page']       = 'backEnd/admin/transaction_medium_edit';
			
		   
		} elseif($param1 == 'delete' && $param2 > 0) {
			
		   	if ($this->AdminModel->delete_transaction_medium($param2)) {

				$this->session->set_flashdata('message','transaction-medium  Deleted Successfully!');
				redirect('admin/transaction-medium/list','refresh');

			} else {

			    $this->session->set_flashdata('message','transaction-medium Deleted Failed!');
				redirect('admin/transaction-medium/list','refresh');
			}
			
		} else {

			$this->session->set_flashdata('message', 'Wrong Attempt!');
			redirect('admin/transaction-medium/list','refresh');

		}

		$this->load->view('backEnd/master_page',$data);        
	}


	//customer crud

	public function customer_balance($param1 = 'add', $param2 = '', $param3 = '')
	{
		if ($param1 == 'add') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$accounts_data['account_head_id']  = 1;
				$accounts_data['accounts_type']    = $this->input->post('accounts_type', true);
				$accounts_data['date']             = date('Y-m-d', strtotime($this->input->post('transaction_date', true)));
				$accounts_data['description']      = $this->input->post('description', true);
				$accounts_data['amount']           = $this->input->post('amount', true);
				$accounts_data['status']           = 0;
				$accounts_data['insert_by']        = $_SESSION['userid'];
				$accounts_data['insert_time']      = date('Y-m-d H:i');

				$this->db->insert('tbl_accounts', $accounts_data);
				$last_id = $this->db->insert_id();

				$customer_balance_data['customer_id']           = $this->input->post('customer_id', true);
				$customer_balance_data['accounts_id']           = $last_id;
				$customer_balance_data['accounts_type']         = $this->input->post('accounts_type', true);
				$customer_balance_data['transaction_date']      = date('Y-m-d', strtotime($this->input->post('transaction_date', true)));
				$customer_balance_data['description']           = $this->input->post('description', true);
				$customer_balance_data['transaction_medium_id'] = $this->input->post('transaction_medium_id', true);
				$customer_balance_data['receiver']              = $this->input->post('receiver', true);
				$customer_balance_data['insert_by']             = $_SESSION['userid'];
				$customer_balance_data['insert_time']           = date('Y-m-d H:i');

				
				$add_customer_balance = $this->db->insert('tbl_customer_balance', $customer_balance_data);

				if ($add_customer_balance) {

					$this->session->set_flashdata('message','Customer Balance Added Successfully!');
					redirect('admin/customer-balance/list','refresh');

				} else {

				   $this->session->set_flashdata('message','Customer Balance Add Failed!');
					redirect('admin/customer-balance','refresh');
				}
			}

			$data['customer_list']           = $this->db->select('id, name')->get('tbl_customers')->result();
			$data['transaction_medium_list'] = $this->db->select('id, name')->get('tbl_transaction_medium')->result();

			$data['title']             = 'Customer Balance Add';
			$data['activeMenu']        = 'customer_balance_add';
			$data['page']              = 'backEnd/admin/customer_balance_add';
			
		} elseif ($param1 == 'list' ) {

			$config = array();
	        $config["base_url"] = base_url() . "admin/customer-balance/list";
	        $config["total_rows"] = $this->db->count_all('tbl_customer_balance');
	        $config["per_page"] = 10;
	        $config["uri_segment"] = 4;

	        //custom
	        $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            
            $config['first_link'] = "First";
            $config['last_link'] = "Last";
            
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
                
            $config['prev_link'] = '«';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            
            $config['next_link'] = '»';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

	        $this->pagination->initialize($config);

	        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

	        $data["links"] = $this->pagination->create_links();

			$data['customer_balance_list'] = $this->AdminModel->get_customer_balance_list($config["per_page"], $page);

			$data['title']        = 'customer Balance List';
			$data['activeMenu']   = 'customer_balance_list';
			$data['page']         = 'backEnd/admin/customer_balance_list';

		   
		} elseif($param1 == 'report'){

			$data['search'] = array();

    		$data['search']['start_date']       = '';
    		$data['search']['end_date']         = '';
    		$data['search']['customer_id']      = '';
    		$data['search']['accounts_type']    = '9';
    		$data['customer_balance_list']      = array();
    		$data['print']                      = false;

    		if($_SERVER['REQUEST_METHOD'] == 'POST'){

    			if ($this->input->post('start_date', true)){

					$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date', true)));
				}

				if ($this->input->post('end_date', true)){

					$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->post('end_date', true)));
				}

				if ($this->input->post('customer_id', true)){

					$data['search']['customer_id'] = $this->input->post('customer_id', true);
				}

				$data['search']['accounts_type'] = $this->input->post('accounts_type', true);
				
				$data['print']                 = true;
				$data['customer_balance_list'] = $this->AdminModel->get_customer_balance_report($data['search']);


    		}

    		$data['customer_list']           = $this->db->select('id, name')->get('tbl_customers')->result(); 

			$data['title']        = 'customer Balance Report';
			$data['activeMenu']   = 'customer_balance_report';
			$data['page']         = 'backEnd/admin/customer_balance_report';

		} elseif ($param1 == 'edit' && $param2 > 0) {

			$data['edit_info']   = $this->db->select('tbl_customer_balance.id, tbl_customer_balance.transaction_date, tbl_customer_balance.description, tbl_customer_balance.accounts_type, tbl_customer_balance.transaction_medium_id, tbl_customer_balance.receiver, tbl_accounts.amount, tbl_customer_balance.customer_id, tbl_customer_balance.accounts_id')->join('tbl_accounts', 'tbl_accounts.id = tbl_customer_balance.accounts_id', 'left')->get_where('tbl_customer_balance',array('tbl_customer_balance.id'=>$param2));

			if ($data['edit_info']->num_rows() > 0) {
				
				$data['edit_info']    = $data['edit_info']->row();
				
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					
					$update_accounts_data['account_head_id']  = 1;
					$update_accounts_data['accounts_type']  = $this->input->post('accounts_type', true);
					$update_accounts_data['date']             = date('Y-m-d', strtotime($this->input->post('transaction_date', true)));
					$update_accounts_data['description']      = $this->input->post('description', true);
					$update_accounts_data['amount']           = $this->input->post('amount', true);
					$update_accounts_data['status']           = 0;

					$this->db->where('id', $data['edit_info']->accounts_id)->update('tbl_accounts', $update_accounts_data);


					$customer_balance_data['customer_id']           = $this->input->post('customer_id', true);
					
					$customer_balance_data['accounts_type']         = $this->input->post('accounts_type', true);
					$customer_balance_data['transaction_date']      = date('Y-m-d', strtotime($this->input->post('transaction_date', true)));
					$customer_balance_data['description']           = $this->input->post('description', true);
					$customer_balance_data['transaction_medium_id'] = $this->input->post('transaction_medium_id', true);
					$customer_balance_data['receiver']              = $this->input->post('receiver', true);

					$this->db->where('id', $param2)->update('tbl_customer_balance', $customer_balance_data);

					if (true) {

						$this->session->set_flashdata('message','customer balance  Updated Successfully!');
						redirect('admin/customer-balance/list', 'refresh');

					} else {

					   $this->session->set_flashdata('message','customer balance Update Failed!');
						redirect('admin/customer-balance/edit/'.$param2, 'refresh');
					}
				}

			} else {
				// echo "<pre>";
				// 	print_r($param2);
				// 	exit();

				$this->session->set_flashdata('message','Wrong Attempt!');
				redirect('admin/customer-balance/list','refresh');
			}

			$data['customer_list']           = $this->db->select('id, name')->get('tbl_customers')->result();
			$data['transaction_medium_list'] = $this->db->select('id, name')->get('tbl_transaction_medium')->result();

			$data['title']      = 'customer Balance Edit';
			$data['activeMenu'] = 'customer_balance_edit';
			$data['page']       = 'backEnd/admin/customer_balance_edit';
			
		   
		} elseif($param1 == 'delete' && $param2 > 0) {

			$data['delete_info'] = $this->db->get_where('tbl_customer_balance',array('id'=>$param2))->row();
			$accounts_id = $data['delete_info']->accounts_id;

			$this->db->where('id', $accounts_id)->delete('tbl_accounts');
			$this->db->where('id', $param2)->delete('tbl_customer_balance');

			
		   	if (true) {

				$this->session->set_flashdata('message','customer balance  Deleted Successfully!');
				redirect('admin/customer-balance/list','refresh');

			} else {

			    $this->session->set_flashdata('message','customer balance Deleted Failed!');
				redirect('admin/customer-balance/list','refresh');
			}
			
		} else {

			$this->session->set_flashdata('message', 'Wrong Attempt!');
			redirect('admin/customer-balance/list','refresh');

		}

		$this->load->view('backEnd/master_page',$data);        
	}

	public function customer_balance_report_print()
	{
		$data['search'] = array();

		$data['search']['start_date']       = '';
		$data['search']['end_date']         = '';
		$data['search']['customer_id']      = '';
		$data['search']['accounts_type']    = '';
		

		if($_SERVER['REQUEST_METHOD'] == 'GET'){

			if ($this->input->get('start_date', true)){

				$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->get('start_date', true)));
			}

			if ($this->input->get('end_date', true)){

				$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->get('end_date', true)));
			}

			if ($this->input->get('customer_id', true)){

				$data['search']['customer_id'] = $this->input->get('customer_id', true);
			}

			if ($this->input->get('accounts_type', true)){

				$data['search']['accounts_type'] = $this->input->get('accounts_type', true);
			}
			

		}

		$data['customer_info'] = $this->db->select('id, name, phone')->where('id', $data['search']['customer_id'])->get('tbl_customers')->row();
		
		$data['customer_balance_list'] = $this->AdminModel->get_customer_balance_report($data['search']);

		// echo "<pre>";
		// print_r($data['customer_balance_list']);
		// exit;
		

		$this->load->view('backEnd/print_page/customer_balance_print',$data);
	}

	public function daily_summary()
	{
		$data['accounts_head_list'] = $this->AdminModel->get_daily_summary();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$rest_amount =  $this->AdminModel->get_rest_amount($value->id);
			
			$data['accounts_head_list'][$key]->rest_amount = $rest_amount; 
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;



		$data['title']      = 'Daily Summary';
		$data['activeMenu'] = 'daily_summary';
		$data['page']       = 'backEnd/admin/daily_summary';

		$this->load->view('backEnd/master_page',$data);
	}

	public function daily_summary_print()
	{
		$data['accounts_head_list'] = $this->AdminModel->get_daily_summary();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$rest_amount =  $this->AdminModel->get_rest_amount($value->id);
			
			$data['accounts_head_list'][$key]->rest_amount = $rest_amount; 
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;

		$this->load->view('backEnd/print_page/daily_summary_print',$data);
	}

	public function balance_sheet()
	{
		$data['search'] = array();
		$data['accounts_head_list'] = array();
		$data['search']['start_date']       = '';
		$data['search']['end_date']         = '';

		$data['print'] = false;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if ($this->input->post('start_date', true)){

				$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date', true)));
			}

			if ($this->input->post('end_date', true)){

				$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->post('end_date', true)));
			}

			$data['print'] = true;
		}

		$data['accounts_head_list'] = $this->db->select('id, name')->get('tbl_account_head')->result();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$data['accounts_head_list'][$key]->cash_in = $this->AdminModel->get_cash_in_amount($value->id, $data['search']);
			$data['accounts_head_list'][$key]->cash_out = $this->AdminModel->get_cash_out_amount($value->id, $data['search']);
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;



		$data['title']      = 'Balance Sheet';
		$data['activeMenu'] = 'balance_sheet';
		$data['page']       = 'backEnd/admin/balance_sheet';

		$this->load->view('backEnd/master_page',$data);
	}

	public function balance_sheet_print()
	{
		$data['search'] = array();
		$data['accounts_head_list'] = array();
		$data['search']['start_date']       = '';
		$data['search']['end_date']         = '';

		$data['print'] = false;

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {

			if ($this->input->get('start_date', true)){

				$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->get('start_date', true)));
			}

			if ($this->input->get('end_date', true)){

				$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->get('end_date', true)));
			}

			$data['print'] = true;
		}

		$data['accounts_head_list'] = $this->db->select('id, name')->get('tbl_account_head')->result();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$data['accounts_head_list'][$key]->cash_in = $this->AdminModel->get_cash_in_amount($value->id, $data['search']);
			$data['accounts_head_list'][$key]->cash_out = $this->AdminModel->get_cash_out_amount($value->id, $data['search']);
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;

		$this->load->view('backEnd/print_page/balance_sheet_print', $data);
	}

	public function trial_balance()
	{
		$data['search'] = array();
		$data['accounts_head_list'] = array();
		$data['search']['start_date']       = '';
		$data['search']['end_date']         = '';

		$data['print'] = false;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if ($this->input->post('start_date', true)){

				$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date', true)));
			}

			if ($this->input->post('end_date', true)){

				$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->post('end_date', true)));
			}

			$data['print'] = true;
		}

		$data['accounts_head_list'] = $this->db->select('id, name')->get('tbl_account_head')->result();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$data['accounts_head_list'][$key]->cash_in = $this->AdminModel->get_cash_in_amount($value->id, $data['search']);
			$data['accounts_head_list'][$key]->cash_out = $this->AdminModel->get_cash_out_amount($value->id, $data['search']);
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;

		$data['title']      = 'Trial Balance';
		$data['activeMenu'] = 'trial_balance';
		$data['page']       = 'backEnd/admin/trial_balance';

		$this->load->view('backEnd/master_page',$data);
	}

	public function trial_balance_print()
	{
		$data['search'] = array();
		$data['accounts_head_list'] = array();
		$data['search']['start_date']       = '';
		$data['search']['end_date']         = '';

		$data['print'] = false;

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {

			if ($this->input->get('start_date', true)){

				$data['search']['start_date'] = date('Y-m-d', strtotime($this->input->get('start_date', true)));
			}

			if ($this->input->get('end_date', true)){

				$data['search']['end_date'] = date('Y-m-d', strtotime($this->input->get('end_date', true)));
			}

			$data['print'] = true;
		}

		$data['accounts_head_list'] = $this->db->select('id, name')->get('tbl_account_head')->result();
		

		foreach ($data['accounts_head_list'] as $key => $value) {

			$data['accounts_head_list'][$key]->cash_in = $this->AdminModel->get_cash_in_amount($value->id, $data['search']);
			$data['accounts_head_list'][$key]->cash_out = $this->AdminModel->get_cash_out_amount($value->id, $data['search']);
		}

		// echo "<pre>";
		// print_r($data['accounts_head_list']);
		// exit;

		$this->load->view('backEnd/print_page/trial_balance_print', $data);
	}


}
