<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Install implements InputFilterAwareInterface
{
	public $id;
	public $user_id;
	public $access_token;
	public $expired_ts;
	public $status;
	public $created_time;
	public $updated_time;
	
	protected $inputFilter;                       // <-- Add this variable

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->user_id  = (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->access_token  = (!empty($data['access_token'])) ? $data['access_token'] : null;
		$this->expired_ts  = (!empty($data['expired_ts'])) ? $data['expired_ts'] : null;
		$this->status  = (!empty($data['status'])) ? $data['status'] : null;
		$this->created_time  = (!empty($data['created_time'])) ? $data['created_time'] : null;
		$this->updated_time  = (!empty($data['updated_time'])) ? $data['updated_time'] : null;
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
					'name'     => 'access_token',
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
	
	public static function gen_random($type, $length = 10) { //$type-- 's':string, 'n':number, 'h': hex-number
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random = '';
		if ($type === 's') {
			$candicates_num = strlen($characters);
		}
		if ($type === 'n') {
			$candicates_num = 10;
		}
		if ($type === 'h') {
			$candicates_num = 16;
		}
		if ($type === 'n') {
			$pos = 0;
			if ($length > 1) {
				$random .= $characters[1 + hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % ($candicates_num - 1)]; //the first digit
				$pos++;
			}
			for ($p = $pos; $p < $length; $p++) {
				$random .= $characters[hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % $candicates_num];
			}
		} else {
			for ($p = 0; $p < $length; $p++) {
				$random .= $characters[hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % $candicates_num];
			}
		}
		return $random;
	}
	

}