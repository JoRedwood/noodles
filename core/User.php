<?php

class User extends Core{
	
	protected $userID;
	
	var $userTable;
	
	var $group;
	
	var $authed = false;
	
	var $userData = array();

	
	public function __construct($table, $userID)
	{
		$this->userTable = $table;
		$this->userID = $userID;
	}
	
	/**
	 * If generated by the Login class this will be called
	 * This will allow actions to be taken by the user
	 */
	public function authed()
	{
		$this->authed = true;
	}
	
	/**
	 * Fetches user data
	 * @param int $idColumn name of the id field
	 * @param array $columns names of colomns to load out of the users table
	 */
	public function load($idColumn, $columns)
	{
		// Build query
		$columnString = '';
		foreach($columns as $index => $column){
			$columnString .= $column;
			if($index < count($columns)-1){
				$columnString .= ', ';
			}
		}
	
		// Run query
		$this->db->prepare("SELECT ".$columnString." FROM ".$this->userTable." WHERE ".$idColumn." = :userId");
		$this->db->bind_value(':userId', $this->userId, 'int');
		$this->db->execute();
		return $result = $this->db->prepQueryFirst();
	}
	
	/**
	 * Loads data into the userData property of the class
	 * @param int $idColumn name of the id field
	 * @param array $columns names of colomns to load out of the users table
	 */
	public function loadUser($idColumn, $columns)
	{
		$this->$userData = $this->load($idColumn, $columns);
	}
	
	/**
	 * Loads user data into sessions
	 * @param int $idColumn name of the id field
	 * @param array $columns names of colomns to load out of the users table
	 */
	public function loadSessions($idColumn, $columns)
	{
		$data = $this->load($idColumn, $columns);
		
		// Set sessions
		foreach($data as $field => $value){
			if(in_array($field, $columns)){
				$_SESSION[$field] = $value;
			}
		}
	}
}

?>