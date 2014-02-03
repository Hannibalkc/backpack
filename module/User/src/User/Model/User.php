<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
	public $id;
	public $email;
	public $password;
	public $profile_photo;
	public $last_name;
	public $first_name;
	public $status;
	public $created_time;
	public $updated_time;
	
	protected $inputFilter;                       // <-- Add this variable

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->email  = (!empty($data['email'])) ? $data['email'] : null;
		$this->password  = (!empty($data['password'])) ? $data['password'] : null;
		$this->profile_photo  = (!empty($data['profile_photo'])) ? $data['profile_photo'] : null;
		$this->last_name  = (!empty($data['last_name'])) ? $data['last_name'] : null;
		$this->first_name  = (!empty($data['first_name'])) ? $data['first_name'] : null;
		$this->status  = (!empty($data['status'])) ? $data['status'] : null;
		$this->created_time  = (!empty($data['created_time'])) ? $data['created_time'] : date('Y-m-d H:i:s');
		$this->updated_time  = (!empty($data['updated_time'])) ? $data['updated_time'] : date('Y-m-d H:i:s');
	}
	 
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	 
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	 
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			 
			$inputFilter->add(array(
					'name'     => 'id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			));
			 
			$inputFilter->add(array(
					'name'     => 'email',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
									),
							),
					),
			));
			 
			$inputFilter->add(array(
					'name'     => 'password',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
									),
							),
					),
			));
			 
			$this->inputFilter = $inputFilter;
		}
		 
		return $this->inputFilter;
	}

}