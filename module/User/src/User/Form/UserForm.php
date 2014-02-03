<?php
 namespace User\Form;

 use Zend\Form\Form;

 class UserForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('user');

//          $this->add(array(
//              'name' => 'id',
//              'type' => 'Hidden',
//          ));
//          $this->add(array(
//              'name' => 'email',
//              'type' => 'Text',
//              'options' => array(
//                  'label' => 'Email',
//              ),
//          ));
//          $this->add(array(
//              'name' => 'password',
//              'type' => 'Text',
//              'options' => array(
//                  'label' => 'Password',
//              ),
//          ));
//          $this->add(array(
//              'name' => 'profile_photo',
//              'type' => 'Text',
//              'options' => array(
//                  'label' => 'ProfilePhoto',
//              ),
//          ));
//          $this->add(array(
//              'name' => 'last_name',
//              'type' => 'Text',
//              'options' => array(
//                  'label' => 'LastName',
//              ),
//          ));
//          $this->add(array(
//              'name' => 'first_name',
//              'type' => 'Text',
//              'options' => array(
//                  'label' => 'FirstName',
//              ),
//          ));
     }
 }