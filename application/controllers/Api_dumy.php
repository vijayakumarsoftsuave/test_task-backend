
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Api_dumy extends CI_Controller {

	public $api_model;
	public $form_validation;
	public $input;

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

	function insert()
	{
		$postdata = json_decode(file_get_contents("php://input"));
		if(isset($postdata) && !empty($postdata)){
			echo 'true';
		}
		else{
			echo 'false';
		}

		json_decode(file_get_contents("php://input"));
//		$data = json_decode($form_data);
//		print_r($form_data->email);
		echo $this->input->post('email');
		echo $this->input->post('password');
		$this->form_validation->set_rules("email", "email", "required");
		$this->form_validation->set_rules("password", "password", "required");
		$array = array();
		$data = array();
		if($this->form_validation->run())
		{
			$data = array(
				'email' => $this->input->post('email'),
				'password'  => $this->input->post('password'),
				'role' => '1',
				'name' => 'vijay'
			);
			$this->api_model->insert_api($data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
				'email_error' => form_error('email'),
				'password_error' => form_error('password')
			);
		}
		echo json_encode($array, true);
	}





	function fetch_single()
	{
		if($this->input->post('id'))
		{
			$data = $this->api_model->fetch_single_user($this->input->post('id'));
			foreach($data as $row)
			{
				$output['first_name'] = $row["first_name"];
				$output['last_name'] = $row["last_name"];
			}
			echo json_encode($output);
		}
	}

	function update()
	{
		$this->form_validation->set_rules("first_name", "First Name", "required");
		$this->form_validation->set_rules("last_name", "Last Name", "required");
		$array = array();
		if($this->form_validation->run())
		{
			$data = array(
				'first_name' => trim($this->input->post('first_name')),
				'last_name'  => trim($this->input->post('last_name'))
			);
			$this->api_model->update_api($this->input->post('id'), $data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
				'first_name_error' => form_error('first_name'),
				'last_name_error' => form_error('last_name')
			);
		}
		echo json_encode($array, true);
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->api_model->delete_single_user($this->input->post('id')))
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
	 * Login User Method.
	 *
	 * @return Response
	 */

	public function loginUser(){
		$user = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("email", "email", "required");
		$this->form_validation->set_rules("password", "password", "required");
		$array = array();

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if($this->form_validation->run())
		{
			$data = array(
				'email' => $email,
				'password'  => $password
			);
			$this->api_model->insert_api($data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
				'email_error' => form_error('email'),
				'password_error' => form_error('password')
			);
		}

		echo json_encode($array, true);
	}

	/**
	 * ADD Employee from CEO.
	 *
	 */

	public function addEmp(){
		$user = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("email", "Email", "required");
		$this->form_validation->set_rules("password", "Pasword", "required");
		$this->form_validation->set_rules("name", "Name", "required");
		$array = array();

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$name = $this->input->post('name');
		if($this->form_validation->run())
		{
			$data = array(
				'email' => $email,
				'password'  => $password,
				'name'  => $name
			);
			$this->api_model->addEmployee($data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
				'email_error' => form_error('email'),
				'password_error' => form_error('password'),
				'name_error' => form_error('name')
			);
		}

	}

	/**
	 * Create project by Role 1
	 *
	 * */

	public function addProject(){
		$project = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("title", "Title", "required");
		$this->form_validation->set_rules("description", "Description", "required");
		$this->form_validation->set_rules("auth_user", "Manager", "required");
		$title = $this->input->post('title');
		$description = $this->input->post('description');
		$auth_user = $this->input->post('auth_user');
		$array = array();
		if($this->form_validation->run())
		{
			$data = array(
				'title' => $title,
				'description'  => $description,
				'authuser'  => $auth_user,
				'status'  => 1
			);
			$this->api_model->addProject($data);
			$array = array(
				'success'  => true
			);
		}
		else
		{
			$array = array(
				'error'    => true,
				'title_error' => form_error('title'),
				'description_error' => form_error('description'),
				'auth_user_error' => form_error('auth_user')
			);
		}
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
				'empID'  => $empID,
				'authuser'  => $auth_user
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
				'title_error' => form_error('title'),
				'description_error' => form_error('description'),
				'auth_user_error' => form_error('auth_user')
			);
		}
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
				'empID'  => $empID,
				'authuser'  => $auth_user
			);
			$this->api_model->editProject($data);
			$array = array(
				'success'  => true
			);
		}
	}

	/**
	 * Edit team members by role 1
	 *
	 * */

	public function editUser(){
		$user = json_decode(file_get_contents('php://input'));
		$this->form_validation->set_rules("name", "Name", "required");
		$this->form_validation->set_rules("email", "Email", "required");
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$id = $this->input->post('id');
		$array = array();
		if($this->form_validation->run())
		{
			$data = array(
				'name' => $name,
				'id' => $id,
				'email'  => $email
			);
			$this->api_model->editUser($data);
			$array = array(
				'success'  => true
			);
		}
	}

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
		$user_id = $this->input->post('id');
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
