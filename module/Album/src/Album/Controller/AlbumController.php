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
     public function onBootstrap($e) {
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

    if($_POST['name']!=''){
    $album = new Album();
    $album->exchangeArray($_POST);
    if($this->getAlbumTable()->getAlbumByName($album->name)){

            $this->getAlbumTable()->saveAlbum($album);
            return new JsonModel(array('data' => '1'));

  }


     else{
       return new JsonModel(array('data' => '0'));
     }


}
}
      public function showAction()
     {
        $id = $this->params()->fromRoute('id');
        // grab the paginator from the AlbumTable
        $paginator = $this->getStudentTable()->getStudents($id);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(2);

        return new ViewModel(array(
         'paginator' => $paginator,
            'id' => $id,

        ));
      }

          public function addAction()
     {
         $form = new AlbumForm();
        // $form->get('submit')->setValue('Add');

      /*   $request = $this->getRequest();
         if ($request->isPost()) {
             $album = new Album();
             $form->setInputFilter($album->getInputFilter());
             $form->setData($this->getRequest()->getPost());

             if ($form->isValid()) {
                 $album->exchangeArray($form->getData());
                if($this->getAlbumTable()->getAlbumByName($album->name)){

                        $this->getAlbumTable()->saveAlbum($album);
                        return new JsonModel(array('data' => $this->getRequest()->getPost()));

     }


                 else{
                   return new JsonModel(array('data' => 'fail'));
                 }
                 // Redirect to list of albums
                // return $this->redirect()->toRoute('album');




             }
         }*/
        return array('form' => $form);

     }



    public function add2Action()
     {
        $id = (int) $this->params()->fromRoute('id');
        $form = new StudentForm();
         $form->get('submit')->setValue('Add');

    /*     $request = $this->getRequest();
         if ($request->isPost()) {
             $student = new Student();
             $form->setInputFilter($student->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $student->exchangeArray($form->getData());
                 $this->getStudentTable()->saveStudent($student);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
         }*/
         return array('form' => $form,
                     'id' => $id,
                     );
     }
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);

     try {
             $album = $this->getAlbumTable()->getAlbum($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('album', array(
                 'action' => 'index'
             ));
         }

         $form  = new AlbumForm();
         $form->bind($album);
      /*   $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($album->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getAlbumTable()->saveAlbum($album);


                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
         }*/

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
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('album', array(
                 'action' => 'index'
             ));
         }

         $form  = new StudentForm();
         $form->bind($student);
         $form->get('submit')->setAttribute('value', 'Edit');

    /*     $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($student->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getStudentTable()->saveStudent($student);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
            else{
                 ?><script>window.alert("Invalid Email");</script><?php
            }
         }*/

         return array(
             'id' => $id,
             'form' => $form,
         );
     }

    /* public function getListAction()
     {
         $results = $this->getAlbumTable()->fetchAll();
   $data = array();
    foreach($results as $result) {
        $data[] = $result;
    }

     return new JsonModel(array('data' => $data));
     }*/

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
         ));       }
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
