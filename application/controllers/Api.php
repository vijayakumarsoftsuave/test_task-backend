
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Api extends CI_Controller {

	public $api_model;
	public $form_validation;
	public $input;
	public $session;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('api_model');
		$this->load->library('form_validation');
//		header('Access-Control-Allow-Origin: *');
	}

	function index()
	{
		$data = $this->api_model->fetch_all();
		echo json_encode($data);
	}
	/**
	 * Login User Method.
	 *
	 * @return Response
	 */

	public function loginUser(){
		$array = json_decode(file_get_contents("php://input"), true);
		$email = $array['email'];
		$password = sha1($array['password']);
		$data = array(
			'email' => $email,
			'password'  => $password
		);
		$array['users'] = $this->api_model->loginUser($data);
		if ($array['users']){
			$email = $array['users']['email'];
			$id = $array['users']['id'];
			$role = $array['users']['role'];
			$session_data = array(
				'email' => $email,
				'id'  => $id,
				'role'  => $role
			);
			//SESSION DATA
			$this->session->set_userdata($session_data);
			$session_email = $this->session->userdata('email');
			$session_id = $this->session->userdata('id');
			$role = $this->session->userdata('role');
			$array = array(
				'status'  => 1,
				'message'  => 'Logged successfully!!',
				'email' => $session_email,
				'id' => $session_id,
				'role' => $role,
			);
		}
		else{
			$array = array(
				'status'  => 0,
				'message' => 'No valid email or password!!'
			);
		}
//
		echo json_encode($array, true);
	}


	function getManager()
	{
		$data = $this->api_model->getManager();
		echo json_encode($data);
	}

	/**
	 * ADD Employee from CEO.
	 *
	 */

	public function addEmployee(){

		$array = json_decode(file_get_contents("php://input"), true);
		$email = $array['email'];
		$name = $array['name'];
		$role = $array['role'];
		$password = sha1($array['password']);
		$data = array(
			'email' => $email,
			'name'  => $name,
			'role'  => $role,
			'password'  => $password
		);
		if ($this->api_model->addEmployee($data)){
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true
			);
		}

		echo json_encode($array, true);

	}

	/**
	 * Create project by Role 1
	 *
	 * */

	public function addProject(){

		$array = json_decode(file_get_contents("php://input"), true);
		$title = $array['title'];
		$description = $array['description'];
		$manager = $array['manager'];
		$data = array(
			'title' => $title,
			'description'  => $description,
			'manager'  => $manager
		);
		if ($this->api_model->addProject($data)){
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true
			);
		}

		echo json_encode($array, true);

	}

	/**
	 * Assign project by Manager
	 *
	 * */

	public function assignProject(){
		$project = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("project_id", "Project", "required");
		$this->form_validation->set_rules("empID", "Employee Name", "required");
		$empID = $this->input->post('empID');
		$projectID = $this->input->post('project_id');
		$auth_user = $this->input->post('auth_user');
		$array = array();
		if($this->form_validation->run())
		{
			$data = array(
				'id' => $projectID,
				'user_id'  => $empID,
				'auth_user'  => $auth_user
			);
			$this->api_model->assignProject($data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
//				'title_error' => form_error('title'),
//				'description_error' => form_error('description'),
				'auth_user_error' => form_error('auth_user')
			);
		}
		echo json_encode($array, true);
	}


	/**
	 * Edit team project by Manager
	 *
	 * */

	public function editProject(){
		$project = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("project_id", "Project", "required");
		$this->form_validation->set_rules("empID", "Employee Name", "required");
		$empID = $this->input->post('empID');
		$projectID = $this->input->post('project_id');
		$auth_user = $this->input->post('auth_user');
		$array = array();
		if($this->form_validation->run())
		{
			$data = array(
				'id' => $projectID,
				'user_id'  => $empID,
				'auth_user'  => $auth_user
			);
			$this->api_model->editProject($data);
			$array = array(
				'success'  => true
			);
		}

		echo json_encode($array, true);
	}

	/**
	 * Edit team members by role 1
	 *
	 * */

//	public function editUser(){
//		$user = json_decode(file_get_contents('php://input'),true);
//
//		$array = array();
//		$data = array(
//			'name' => $name,
//			'id' => $id,
//			'email'  => $email
//		);
//		$this->api_model->editUser($data);
//		$array = array(
//			'success'  => true
//		);
//
//		echo json_encode($array, true);
//	}

	/**
	 * Show all users
	 *
	 * */

	public function getUsers()
	{
		$data = $this->api_model->getUsers();
		echo json_encode($data);
	}

	/**
	 * Show all users
	 *
	 * */

	public function getProject()
	{
		$data = $this->api_model->getProject();
		echo json_encode($data);
	}

	/**
	 * delete User
	 *
	 * */


	public function deleteUser()
	{
		$input = json_decode(file_get_contents('php://input'),true);
		$user_id = $input['id'];
		if($user_id)
		{
			if($this->api_model->deleteUser($user_id))
			{
				$array = array(
					'success' => true
				);
			}
			else
			{
				$array = array(
					'error' => true
				);
			}
			echo json_encode($array);
		}
	}

	/**
	 * delete User
	 *
	 * */


	public function deleteProject()
	{
		$id = $this->input->post('id');
		if($id)
		{
			if($this->api_model->deleteProject($id))
			{
				$array = array(
					'success' => true
				);
			}
			else
			{
				$array = array(
					'error' => true
				);
			}
			echo json_encode($array);
		}
	}



}
