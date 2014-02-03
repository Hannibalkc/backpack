<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Model\User;
use User\Form\UserForm;
use User\Model\UserTable;
use User\Model\Install;
use User\Model\InstallTable;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\DateTime;

class UserController extends AbstractActionController
{
    public function indexAction(){
        return new JsonModel(array("data" => "Hello world!"));
    }
    
    public function loadUserByTokenAction(){
        $access_token = $this->getRequest()->getQuery("access_token");
        $results = $this->getUserByToken($access_token);
    	return new JsonModel(array("data" => $results));
    }
    
    public function getUserByToken($access_token){
    	$install = $this->getInstallTable()->loadInstallByToken($access_token);
    	if($install == null)
    	    return null;
    	return $this->getUserTable()->getUser($install->user_id);
    }
    
    public function loginAction(){
        $username = $this->params()->fromPost("username");
        $password = $this->params()->fromPost("password");
        
        $results = $this->getUserTable()->loadUser($username, $password);
        
        if(sizeof($results) == 0)
            return new JsonModel(array("status" => "invalid"));
        $data = array();
        foreach($results as $result) {
        	$data[] = $result;
        }
        $install = $this->getInstallTable()->getByUser($data[0]->id);
        if(sizeof($install) == 0)
        	return new JsonModel(array("status" => "invalid"));
//         $installs = array();
//         foreach($install_results as $install_result) {
//         	$installs[] = $install_result;
//         }
        return new JsonModel(array("status"=>"valid", "user"=>$data[0], "install"=>$install));
    }
     
    public function createAction($data)
    {
         $form = new UserForm();
         $request = $this->getRequest();
         if ($request->isPost()) {
             //Check whether user exists
             $results = $this->getUserTable()->loadUser($request->getPost()->email, $request->getPost()->password);
             if(sizeof($results) > 0)
                return new JsonModel(array("status" => "invalid", "msg" => "the email exists!"));
             $user = new User();
             $user->first_name = $request->getPost()->first_name;
             $user->email = $request->getPost()->email;
             $user->last_name = $request->getPost()->last_name;
             $user->password = $request->getPost()->password;
             $user->status = 1;
             $user->created_time = date('Y-m-d H:i:s');
             $user->updated_time = date('Y-m-d H:i:s');
             $this->getUserTable()->saveUser($user);

             //create install
             $install = new Install();
             $install->user_id = $user->id;
             $install->status = 1;
             $install->access_token = Install::gen_random('s', 20);
             $install->expired_ts = date('Y-m-d H:i:s');
             $install->created_time = date('Y-m-d H:i:s');
             $install->updated_time = date('Y-m-d H:i:s');
             $this->getInstallTable()->saveInstall($install);
             return new JsonModel(array("status"=>"valid", "user"=>$user, "install"=>$install));
         }
         return new JsonModel(array("status" => "invalid", "msg" => "you should use POST!"));
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
}
