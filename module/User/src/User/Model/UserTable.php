<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function loadUser($username, $password){
	    $resultSet = $this->tableGateway->select(array("email"=>$username, "password"=>$password));
	    return $resultSet;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getUser($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array("id" => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveUser(User $user)
	{
		$data = array(
				'email' => $user->email,
				'password'  => $user->password,
		        'status'  => 1,
		        'first_name'  => $user->first_name,
		        'last_name'  => $user->last_name,
		        'created_time'  => $user->created_time,
		        'updated_time'  => $user->updated_time,
		);

		$id = (int) $user->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$user->id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->getUser($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('user id does not exist');
			}
		}
	}

	public function deleteUser($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}