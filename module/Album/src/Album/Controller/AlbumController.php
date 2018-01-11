<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Model\Student;
use Album\Form\AlbumForm;
use Album\Form\StudentForm;

class AlbumController extends AbstractRestfulController
{
    protected $albumTable;
    protected $studentTable;
    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $app->getEventManager()
           ->getSharedManager()
           ->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array($this, 'dispatchControllerStrategy'));
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        if ($_POST['name']!='') {
            $album = new Album();
            $album->exchangeArray($_POST);
            if ($this->getAlbumTable()->getAlbumByName($album->name)) {
                $this->getAlbumTable()->saveAlbum($album);
                return new JsonModel(array('data' => '1'));
            } else {
                return new JsonModel(array('data' => '0'));
            }
        }
    }
    public function showAction()
    {
        $id = $this->params()->fromRoute('id');
        return new ViewModel(array(
            'id' => $id,
          ));
    }

    public function addAction()
    {
        $form = new AlbumForm();
        return array('form' => $form);
    }

    public function add2Action()
    {
        $id = (int) $this->params()->fromRoute('id');
        $form = new StudentForm();
        $form->get('submit')->setValue('Add');
        return array('form' => $form,
                     'id' => $id,
                     );
    }
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        try {
            $album = $this->getAlbumTable()->getAlbum($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                 'action' => 'index'
             ));
        }
        $form  = new AlbumForm();
        $form->bind($album);
        return array(
             'id' => $id,
             'form' => $form,
         );
    }
    public function edit2Action()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        try {
            $student = $this->getStudentTable()->getStudentById($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                 'action' => 'index'
             ));
        }

        $form  = new StudentForm();
        $form->bind($student);
        $form->get('submit')->setAttribute('value', 'Edit');
        return array(
             'id' => $id,
             'form' => $form,
         );
    }

    public function delete2Action()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return new JsonModel(array(
               'data' => 'Record Not Found',
           ));
        }
        $this->getStudentTable()->deleteStudent($id);
        return new JsonModel(array(
                     'data' => 'deleted',
                   ));
    }
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return new JsonModel(array(
             'data' => 'Record Not Found',
         ));
        }
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

    public function getStudentTable()
    {
        if (!$this->studentTable) {
            $sm = $this->getServiceLocator();
            $this->studentTable = $sm->get('Album\Model\StudentTable');
        }
        return $this->studentTable;
    }
}
