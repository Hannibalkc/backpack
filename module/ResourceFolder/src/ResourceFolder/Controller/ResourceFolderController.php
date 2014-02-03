<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ResourceFolder for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ResourceFolder\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\File\Transfer\Adapter\Http;

use ResourceFolder\Model\ResourceFolder;
use ResourceFolder\Model\ResourceFolderTable;
use ResourceFolder\Model\Resource;
use ResourceFolder\Model\ResourceTable;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\DateTime;
use Zend\Mail\Storage\Folder;


class ResourceFolderController extends AbstractActionController
{
    public function indexAction()
    {
        return new JsonModel(array("data" => "Hello Folder!"));
    }

    public function getExtension ($name)
    {
    	    $fname='';
    	    if($name)
    	    {
    	        foreach ($name as $val)
    	        {
    	            $fname=$val['name'];
    	        }
    	        $exts = @split("[/\\.]", $fname) ;
    	        $n = count($exts)-1;
    	        $exts = $exts[$n];
    	        return $exts;
    	    }
    }
    
    public function getExtensionFromName($fname){
        $exts = @split("[/\\.]", $fname) ;
        $n = count($exts)-1;
        $exts = $exts[$n];
        return $exts;
    }
    
    public function getFileName ($name)
    {
    	$fname='';
    	if($name)
    	{
    		foreach ($name as $val)
    		{
    			$fname=$val['name'];
    		}
    		return $fname;
    	}
    }
    			
    public function uploadAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id == 0)
            return new JsonModel(array("status" => "invalid", "msg" => "Invalid parameter!"));
        
        $request = $this->getRequest();
        //Get user from token
        if (!$request->isPost())
        	return new JsonModel(array("status" => "invalid", "msg" => "you should use POST!"));
         
        $access_token = $this->getRequest()->getQuery("access_token");
        if ($access_token == null)
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the parameter access_token not exist!"
        	));
        $user = $this->getUserByToken($access_token);
        if(!isset($user))
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the user does not exist!"
       	));
        
        //Check if folder exists, create folder
        $folder = $this->getResourceFolderTable()->getResourceFolder($id);
        if(!$folder)
            return new JsonModel(array("status"=>"invalid", "data"=>"Folder does not exist!"));
        if($folder->user_id != $user->id)
            return new JsonModel(array("status"=>"invalid", "data"=>"You are not the owner!"));
        
        
        //Save File
        $results = $this->saveFile($request, $id);
        if($results == null){
            return new JsonModel(array("status"=>"invalid", "data"=>"Fail to upload file!"));
        }
        if($results["status"] == "invalid")
            return new JsonModel($results);
        //Save to Resource
        $resource = new Resource();
        $resource->folder_id = $id;
        $resource->type = 1;    //Is a file
        $resource->name = $results["name"];
        $resource->file_name = $results["file_name"];
        $resource->status = 1;
        $resource->updated_time = date('Y-m-d H:i:s');
        $resource->created_time = date('Y-m-d H:i:s');
        
        $this->getResourceTable()->saveResource($resource);
        return new JsonModel(array("status"=>"valid", "data"=>"Succeed to upload file!"));
    }
    
    public function downloadAction(){
        $access_token = $this->getRequest()->getQuery("access_token");
        if ($access_token == null)
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the parameter access_token not exist!"
        	));
        $user = $this->getUserByToken($access_token);
        if(!isset($user))
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the user does not exist!"
        	));
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id == 0)
            return new JsonModel(array(
            		"status" => "invalid",
            		"msg" => "Invalid parameter!"
            ));
        $resource = $this->getResourceTable()->getResource($id);
        if(!$resource)
            return new JsonModel(array(
            		"status" => "invalid",
            		"msg" => "the resource does not exist!"
            ));
        
        //Download file
        $exts = $this->getExtensionFromName($resource->name);
        header('Content-Type: application/'.$exts);
        header('Content-Disposition: attachment; filename="'.$resource->name.'"');
        readfile('/backpack/' . $resource->file_name);
        
        // disable layout and view
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    //TODO: check permission
    public function hasPermission(){
        return true;
    }
    
    protected function saveFile($request, $folder_id){
        $adapter = new Http();
        $adapter->setDestination('/backpack');
        //         $adapter
        //         	->addValidator ( 'Extension', false, $configs['extension'])//文件格式限制
        //         	->addValidator('Size', false, array('min' =>floatval($configs['minsize']),
        //         	    'max' => floatval($configs['maxsize'])))//设置上传文件的大小在1-2M之间
        //         	->addValidator ( 'Count', false, array('min' => intval($configs['mincount']),
        //         	    'max' => intval($configs['maxcount'])) );//上传文件数量
        $fileInfo = $adapter->getFileInfo();//获取基本配置
        $extName=$this->getExtension($fileInfo);//获取扩展名
        $name = $this->getFileName($fileInfo);
        
        //Check file exiests
        $resource = $this->getResourceTable()->isExist($folder_id, $name);
        if($resource){
            return array("status" => "invalid", "msg" => "Fail to upload file, $name exists!");
        }
        
        $filename=md5(time()+$fileInfo['fFile']['name']).'.'.$extName;//重命名
        $adapter->addFilter('Rename', array('target' => $filename, 'overwrite' => true));//执行重命名
        if (!$adapter->receive())
        {
        	$messages = $adapter->getMessages ();//检测
        	$message='';
        	if(is_array($messages))
        	{
        		foreach($messages as $k=>$v)
        		{
        			$message.=$k.':'.$v.'<br>';
        		}
        	}
        	else
        	{
        		$message=$messages;
        	}
        	return null;
        }
        else
        {
        	$this->view->message='Success to upload';
        }
        return array("file_name" => $filename, "name" => $name);
    }
      
    public function getUserByToken($access_token){
    	$install = $this->getInstallTable()->loadInstallByToken($access_token);
    	if($install == null)
    		return null;
    	return $this->getUserTable()->getUser($install->user_id);
    }
    
    public function resourcesAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $access_token = $this->getRequest()->getQuery("access_token");
        if ($access_token == null)
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the parameter access_token not exist!"
        	));
        $user = $this->getUserByToken($access_token);
        if(!isset($user))
        	return new JsonModel(array(
        			"status" => "invalid",
        			"msg" => "the user does not exist!"
        	));
        
        //Check if folder exists, create folder
        $folder = $this->getResourceFolderTable()->getResourceFolder($id);
        if($folder){
            $subFolders = $this->getResourceFolderTable()->getFoldersByParentId($id);
            $sf_data = array();
            foreach($subFolders as $result) {
            	$sf_data[] = $result;
            }
            $resources = $this->getResourceTable()->loadByFolderId($id);
            $rs_data = array();
            foreach($resources as $result) {
            	$rs_data[] = $result;
            }
        }else{
            
        }
        
        return new JsonModel(array("status"=>"valid", "folders"=>$sf_data, "resources"=>$rs_data));
    }
    
    public function addAction()
    {
        $request = $this->getRequest();
         if (!$request->isPost()) 
             return new JsonModel(array("status" => "invalid", "msg" => "you should use POST!"));
         
         $access_token = $this->getRequest()->getQuery("access_token");
        if ($access_token == null)
            return new JsonModel(array(
                "status" => "invalid",
                "msg" => "the parameter access_token not exist!"
            ));
        $user = $this->getUserByToken($access_token);
        if(!isset($user))
            return new JsonModel(array(
            		"status" => "invalid",
            		"msg" => "the user does not exist!"
            ));
        
        // Check whether user exists
        $results = $this->getUserTable()->loadUser($request->getPost()->email, $request->getPost()->password);
        if (sizeof($results) > 0)
            return new JsonModel(array(
                "status" => "invalid",
                "msg" => "the user not exist!"
            ));
        //Create folder
        $resourceFolder = new ResourceFolder();
        $resourceFolder->name = $request->getPost()->name;
        $resourceFolder->parent_id = $request->getPost()->parent_id;
        if(!isset($resourceFolder->parent_id))
            $resourceFolder->parent_id = 0;
        $resourceFolder->user_id = $user->id;

        //check duplicate name
        $row = $this->getResourceFolderTable()->geByName($user->id, $resourceFolder->parent_id, $resourceFolder->name);
        if($row){
            return new JsonModel(array(
            		"status" => "invalid",
            		"msg" => "duplicated name!"
            ));
        }
        $resourceFolder->status = 1;
        $resourceFolder->created_time = date('Y-m-d H:i:s');
        $resourceFolder->updated_time = date('Y-m-d H:i:s');
        $this->getResourceFolderTable()->saveResourceFolder($resourceFolder);
        return new JsonModel(array("status"=>"valid", "data"=>$resourceFolder));
    }
    
    public function renameAction(){
        //TODO: rename folder
    }
    
    public function moveAction(){
        //TODO: move folder
    }
    
    public function delAction(){
        //TODO: del action
    }
    
    public function getUserTable()
    {
    	if (!$this->userTable) {
    		$sm = $this->getServiceLocator();
    		$this->userTable = $sm->get('User\Model\UserTable');
    	}
    	return $this->userTable;
    }
    
    protected $userTable;
    
    public function getInstallTable()
    {
    	if (!$this->installTable) {
    		$sm = $this->getServiceLocator();
    		$this->installTable = $sm->get('User\Model\InstallTable');
    	}
    	return $this->installTable;
    }
    
    protected $installTable;
    
    public function getResourceFolderTable(){
        if (!$this->resourceFolderTable) {
        	$sm = $this->getServiceLocator();
        	$this->resourceFolderTable = $sm->get('ResourceFolder\Model\ResourceFolderTable');
        }
        return $this->resourceFolderTable;
    }
    protected $resourceFolderTable;
    
    public function getResourceTable(){
    	if (!$this->resourceTable) {
    		$sm = $this->getServiceLocator();
    		$this->resourceTable = $sm->get('ResourceFolder\Model\ResourceTable');
    	}
    	return $this->resourceTable;
    }
    protected $resourceTable;
}
