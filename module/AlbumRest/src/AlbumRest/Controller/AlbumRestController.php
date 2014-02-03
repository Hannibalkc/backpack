<?php
  namespace AlbumRest\Controller;
     
  use Zend\Mvc\Controller\AbstractRestfulController;
  use Album\Model\Album;
  use Album\Form\AlbumForm;
  use Album\Model\AlbumTable;
  use Zend\View\Model\JsonModel;
     
class AlbumRestController extends AbstractRestfulController
{
    public function getList()
    {
        $results = $this->getAlbumTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
        	$data[] = $result;
        }
        $result = new JsonModel($data);
        return $result;
    }
     
    public function get($id)
    {
        $album = $this->getAlbumTable()->getAlbum($id);
        $data = array();
        $data[] = $album;
        $result = new JsonModel($data);
        return new JsonModel(array("data" => $album));
    }
     
    public function create($data)
    {
        $form = new AlbumForm();
        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
        	$album->exchangeArray($form->getData());
        	$id = $this->getAlbumTable()->saveAlbum($album);
        }
        
        return new JsonModel(array(
        		'data' => $this->get($id),
        ));
    }
    
    public function saveAlbum(Album $album)
    {
    	$data = array(
    			'artist' => $album->artist,
    			'title' => $album->title,
    	);
    	 
    	$id = (int)$album->id;
    	if ($id == 0) {
    		$this->tableGateway->insert($data);
    		$id = $this->tableGateway->getLastInsertValue(); //Add this line
    	} else {
    		if ($this->getAlbum($id)) {
    			$this->tableGateway->update($data, array('id' => $id));
    		} else {
    			throw new \Exception('Form id does not exist');
    		}
    	}
    	 
    	return $id; // Add Return
    }
     
    public function update($id, $data)
    {
        $data['id'] = $id;
        $album = $this->getAlbumTable()->getAlbum($id);
        $form = new AlbumForm();
        $form->bind($album);
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
        	$id = $this->getAlbumTable()->saveAlbum($form->getData());
        }
        
        return new JsonModel(array(
        		'data' => $this->get($id),
        ));
    }
     
    public function delete($id)
    {
        $this->getAlbumTable()->deleteAlbum($id);
        
        return new JsonModel(array(
        		'data' => 'deleted',
        ));
    }
    
    public function getAlbumTable()
    {
    	if (!$this->albumTable) {
    		$sm = $this->getServiceLocator();
    		$this->albumTable = $sm->get('Album\Model\AlbumTable');
    	}
    	return $this->albumTable;
    }
    
    protected $albumTable;
}