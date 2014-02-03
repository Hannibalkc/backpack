<?php
namespace ResourceFolder\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ResourceFolder implements InputFilterAwareInterface
{
	public $id;
	public $name;
	public $parent_id;
	public $user_id;
	public $status;
	public $created_time;
	public $updated_time;
	
	protected $inputFilter;                       // <-- Add this variable

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->name  = (!empty($data['name'])) ? $data['name'] : null;
		$this->parent_id  = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
		$this->user_id  = (!empty($data['user_id'])) ? $data['user_id'] : null;
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
					'name'     => 'parent_id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			));
			
			$inputFilter->add(array(
					'name'     => 'user_id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			));
			 
			$inputFilter->add(array(
					'name'     => 'name',
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
											'max'      => 128,
									),
							),
					),
			));
			
			$this->inputFilter = $inputFilter;
		}
		 
		return $this->inputFilter;
	}

}