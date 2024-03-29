<?php
 namespace UploadFile\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload')
             ->setAttribute('id', 'image-file');
        $this->add($file);
    }
    
    public function addInputFilter()
    {
    	$inputFilter = new InputFilter\InputFilter();
    
    	// File Input
    	$fileInput = new InputFilter\FileInput('image-file');
    	$fileInput->setRequired(true);
    	$fileInput->getFilterChain()->attachByName(
    			'filerenameupload',
    			array(
    					'target'    => '/MSTR/user_image/avatar.png',
    					'randomize' => true,
    			)
    	);
    	$inputFilter->add($fileInput);
    
    	$this->setInputFilter($inputFilter);
    }
}