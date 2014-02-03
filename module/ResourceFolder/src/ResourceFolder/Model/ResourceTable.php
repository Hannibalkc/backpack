<?php
namespace ResourceFolder\Model;

use Zend\Db\TableGateway\TableGateway;

class ResourceTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function loadByFolderId($folder_id){
	    $resultSet = $this->tableGateway->select(array("folder_id"=>$folder_id));
	    return $resultSet;
	}
	
	public function isExist($folder_id, $name){
	    $folder_id  = (int) $folder_id;
	    $rowset = $this->tableGateway->select(array('folder_id' => $folder_id, "name"=>$name));
	    $row = $rowset->current();
	    return $row;
	}
	
// 	public function getResources($folder_id){
// 		$folder_id  = (int) $folder_id;
// 		$rowset = $this->tableGateway->select(array('folder_id' => $folder_id));
// 		$row = $rowset->current();
// 		$resultSet = $this->tableGateway->select();
// 		echo json_encode($resultSet);
// 		return $rowset;
// 	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getResource($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveResource(Resource $resource)
	{
		$data = array(
				'name' => $resource->name,
		        'file_name' => $resource->file_name,
				'description'  => $resource->description,
		        'status'  => 1,
		        'type'  => 1,
		        'json'  => $resource->json,
		        'folder_id'  => $resource->folder_id,
		        'created_time'  => $resource->created_time,
		        'updated_time'  => $resource->updated_time,
		);

		$id = (int) $resource->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$resource->id = $this->tableGateway->getLastInsertValue();
		} else {
			if ($this->getResource($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Resource does not exist');
			}
		}
	}

	public function deleteResource($id)
	{
	    //TODO: Set folder to -1
	}
}