<?php
namespace ResourceFolder\Model;

use Zend\Db\TableGateway\TableGateway;

class ResourceFolderTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function loadResourceFolders($user_id, $parent_id){
	    $resultSet = $this->tableGateway->select(array("user_id"=>$user_id, "parent_id"=>$parent_id));
	    return $resultSet;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function geByName($user_id, $parent_id, $name)
	{
		$user_id  = (int) $user_id;
		$parent_id = (int) $parent_id;
		$rowset = $this->tableGateway->select(array('user_id' => $user_id, "parent_id" => $parent_id, "name" => $name));
		$row = $rowset->current();
// 		if (!$row) {
// 			throw new \Exception("Could not find row $id");
// 		}
		return $row;
	}
	
	public function getFoldersByParentId($parent_id)
	{
		$parent_id  = (int) $parent_id;
		$rowset = $this->tableGateway->select(array('parent_id' => $parent_id));
		return $rowset;
	}
	
	public function getResourceFolder($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveResourceFolder(ResourceFolder $resourceFolder)
	{
		$data = array(
				'name' => $resourceFolder->name,
				'parent_id'  => $resourceFolder->parent_id,
		        'status'  => 1,
		        'user_id'  => $resourceFolder->user_id,
		        'created_time'  => $resourceFolder->created_time,
		        'updated_time'  => $resourceFolder->updated_time,
		);

		$id = (int) $resourceFolder->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$resourceFolder->id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->getResourceFolder($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Folder does not exist');
			}
		}
	}

	public function deleteFolder($id)
	{
	    //TODO: Set folder to -1
	    //TODO: Set children to -1
	}
}