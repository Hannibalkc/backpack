<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/UploadFile for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace UploadFile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use UploadFile\Form\UploadForm;

class UploadFileController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new UploadForm();
//         $form->get('Submit')->setValue('Add');
        
        $request = $this->getRequest();
        return array('form' => $form);
    }

//     public function fooAction()
//     {
//         // This shows the :controller and :action parameters in default route
//         // are working when you browse to /uploadFile/upload-file/foo
//         return array();
//     }
    // File: MyController.php
    
//     public function uploadFormAction()
//     {
//         $form = new UploadForm();
        
//         $request = $this->getRequest();
//         if ($request->isPost()) {
//         	// Make certain to merge the files info!
//         	$post = array_merge_recursive(
//         			$request->getPost()->toArray(),
//         			$request->getFiles()->toArray()
//         	);
        
//         	$form->setData($post);
//         	if ($form->isValid()) {
//         		$data = $form->getData();
//         		// Form is valid, save the form!
//         		return $this->redirect()->toRoute('upload-file');
//         	}
//         }
        
//         return array('form' => $form);
//     }

    public function uploadFormAction()
    {
    	$form     = new UploadForm('upload-form');
    	$tempFile = null;
    
    	$prg = $this->fileprg($form);
    	if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
    		return $prg; // Return PRG redirect response
    	} elseif (is_array($prg)) {
    		if ($form->isValid()) {
    			$data = $form->getData();
    			// Form is valid, save the form!
    			return $this->redirect()->toRoute('upload-file');
    		} else {
    			// Form not valid, but file uploads might be valid...
    			// Get the temporary file information to show the user in the view
    			$fileErrors = $form->get('image-file')->getMessages();
    			if (empty($fileErrors)) {
    				$tempFile = $form->get('image-file')->getValue();
    			}
    		}
    	}
    
    	return array(
    			'form'     => $form,
    			'tempFile' => $tempFile,
    	);
    }
}
