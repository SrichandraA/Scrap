<?php
namespace AlbumRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;
use Album\Model\AlbumTable;
use Zend\View\Model\JsonModel;

class AlbumRestController extends AbstractRestfulController
{
    protected $albumTable;
    public function indexAction()
    {
        return new ViewModel();
    }

    public function getAlbumTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
    public function getList()
    {
        $results = $this->getAlbumTable()->fetchAll();
        $data = array();
        foreach ($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(array('data' => $data));
    }
    public function get($id)
    {
        $album = $this->getAlbumTable()->getAlbum($id);
        if($album!=0)
          return new JsonModel(array("data" => $album));
        else {
          return $this->redirect()->toRoute('album', array('action' => 'error', 'page' => 'No_Class_Found'));
        }
    }

    public function create($data)
    {
        if ($_POST['name']!='') {
            $album = new Album();
            $album->exchangeArray($_POST);
            if ($this->getAlbumTable()->getAlbumByName($album->name)) {
                $this->getAlbumTable()->saveAlbum($album);
                return new JsonModel(array('data' => 'Saved Successfully !'));
            } else {
                return new JsonModel(array('data' => 'User Exists !'));
            }
        } else {
            return new JsonModel(array('data' => 'Please Enter Your Name'));
        }
    }

    public function update($id, $data)
    {

        $data['id'] = $id;
        $album = $this->getAlbumTable()->getAlbum($id);
        $form  = new AlbumForm();
        $form->bind($album);
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            if ($this->getAlbumTable()->getAlbumByName($album->name)) {
                $this->getAlbumTable()->saveAlbum($album);
                return new JsonModel(array('data' => 'Updated Successfully !'));
            } else {
                return new JsonModel(array('data' => 'User Exists !'));
            }
        }

        return new JsonModel(array(
      'data' => 'Updated Successfully !',
    ));

    }

    public function delete($id)
    {
        $this->getAlbumTable()->deleteAlbum($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
}
