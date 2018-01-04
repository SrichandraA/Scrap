<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;        
use Album\Model\Student;          
use Album\Form\AlbumForm;  
use Album\Form\StudentForm;  


 class AlbumController extends AbstractActionController
 {
     protected $albumTable;
     protected $studentTable;

     public function indexAction()
     {
         // grab the paginator from the AlbumTable
         $paginator = $this->getAlbumTable()->fetchAll(true);
         // set the current page to what has been passed in query string, or to 1 if none set
         $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
         // set the number of items per page to 10
         $paginator->setItemCountPerPage(2);

         return new ViewModel(array(
         'paginator' => $paginator
         ));
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
         'paginator' => $paginator
        ));
      }
     
     public function addAction()
     {
         $form = new AlbumForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $album = new Album();
             $form->setInputFilter($album->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $album->exchangeArray($form->getData());
                 $this->getAlbumTable()->saveAlbum($album);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
         }
         return array('form' => $form);
     }
    public function add2Action()
     {
         $form = new StudentForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
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
         }
         return array('form' => $form);
     }
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('album', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
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
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($album->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getAlbumTable()->saveAlbum($album);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }
     public function edit2Action()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('album', array(
                 'action' => 'add2'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
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

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($student->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getStudentTable()->saveStudent($student);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('album');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }

     public function deleteAction()
     {
          $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('album');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getAlbumTable()->deleteAlbum($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('album');
         }

         return array(
             'id'    => $id,
             'album' => $this->getAlbumTable()->getAlbum($id)
         );
     }
     
       public function delete2Action()
     {
          $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('album');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getStudentTable()->deleteStudent($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('album');
         }

         return array(
             'id'    => $id,
             'student' => $this->getStudentTable()->getStudentById($id)
         );
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