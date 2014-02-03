<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class InstallTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function loadInstallByToken($access_token){
		$rowset = $this->tableGateway->select(array("access_token"=>$access_token));
		if(sizeof($rowset) == 0){
			return null;
		}
	    $row = $rowset->current();
	    return $row;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getByUser($user_id){
	    $user_id  = (int) $user_id;
	    $rowset = $this->tableGateway->select(array('user_id' => $user_id));
	    $row = $rowset->current();
	    return $row;
	}

	public function getInstall($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveInstall(Install $insall)
	{
		$data = array(
				'user_id' => $insall->user_id,
				'access_token'  => $insall->access_token,
		        'expired_ts'  => $insall->expired_ts,
		        'status'  => 1,
		        'updated_time'  => $insall->updated_time,
		);

		$id = (int) $insall->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getInstall($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Install id does not exist');
			}
		}
	}

	public function deleteUInstall($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}