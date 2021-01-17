<?php
class Api_model extends CI_Model
{
	/**
	 * Get User Login Method.
	 *
	 * If Role is 1, 2, 3, 4
	 */

	public function loginUser($data)
	{
		$this->db->where("email", $data['email']);
		$this->db->where("password", $data['password']);
		$query = $this->db->get('users');
		if($this->db->affected_rows() > 0)
		{
			$res = $query->result_array();
			foreach ($res as $row){
				$user_email = $row['email'];
				$user_id = $row['id'];
				$role = $row['role'];
			}
			$data = array(
				'email' => $user_email,
				'id' => $user_id,
				'role' => $role,
			);
			return $data;
		}
		else
		{
			return false;
		}

	}


	function getManager()
	{
		$this->db->where("role", 2);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('users')->result_array();
	}

	/**
	 * Add Employee.
	 *
	 */


	function addEmployee($data)
	{
		$this->db->insert('users', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Add Project.
	 *
	 */


	function addProject($data)
	{
		return $this->db->insert('project', $data);
	}

	/**
	 * Add Assign Project.
	 *
	 */


	function assignProject($data)
	{
		$this->db->where("id", $data['id']);
		return $this->db->update("project", $data);
	}


	/**
	 * Edit Assign Project.
	 *
	 */


	function editProject($data)
	{
		$this->db->where("id", $data['id']);
		return $this->db->update("project", $data);
	}


	/**
	 * Edit User.
	 *
	 */


	function editUser($data)
	{
		$this->db->where("id", $data['id']);
		return $this->db->update("users", $data);
	}

	/**
	 * Show all users.
	 *
	 */

	function getUsers()
	{
		$this->db->order_by('id', 'DESC');
		return $this->db->get('users')->result_array();
	}

	/**
	 * Show all Projects.
	 *
	 */

	function getProject()
	{
		$this->db->order_by('id', 'DESC');
		$this->db->select('project.id,project.title,project.description,project.status,users.name');
		$this->db->from('users');
		$this->db->join('project','users.id=project.manager','Right');
		return $this->db->get()->result_array();
	}


	/**
	 * Delete user.
	 *
	 */


	function deleteUser($user_id)
	{
		$this->db->where("id", $user_id);
		$this->db->delete("users");
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete Project.
	 *
	 */


	function deleteProject($id)
	{
		$this->db->where("id", $id);
		$this->db->delete("project");
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function fetch_all()
	{
		$this->db->order_by('id', 'DESC');
		return $this->db->get('users')->result_array();
	}

	function insert_api($data)
	{
		$this->db->insert('users', $data);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function fetch_single_user($user_id)
	{
		$this->db->where("id", $user_id);
		$query = $this->db->get('users');
		return $query->result_array();
	}
	function update_api($user_id, $data)
	{
		$this->db->where("id", $user_id);
		$this->db->update("users", $data);
	}

	function delete_single_user($user_id)
	{
		$this->db->where("id", $user_id);
		$this->db->delete("users");
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
