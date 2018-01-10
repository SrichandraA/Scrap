<?php
namespace StudentRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Album\Model\Student;
use Album\Form\StudentForm;
use Album\Model\StudentTable;

class StudentRestController extends AbstractRestfulController
{
protected $studentTable;

  public function getStudentTable()
 {
     if (!$this->studentTable) {
         $sm = $this->getServiceLocator();
         $this->studentTable = $sm->get('Album\Model\StudentTable');
     }
     return $this->studentTable;
 }
 public function getList()
{
   $results = $this->getStudentTable()->fetchAll();
   $data = array();
   foreach($results as $result) {
       $data[] = $result;
   }


   return new JsonModel(array('data' => $data));
}

public function get($id)
{
  $student = $this->getStudentTable()->getStudent($id);

  return array("data" => $student);
}

    public function create($data)
    {
       $student = new Student();
       $student->exchangeArray($data);

              $this->getStudentTable()->saveStudent($student);
              // print_r($_POST);
              // exit;
              return new JsonModel(array('data' => 'success'));






    }


    public function update($id, $data)
{
  //  $data['id'] = $id;
print_r($_PUT);
  /*  $student = $this->getStudentTable()->getStudent($id);
    $form  = new StudentForm();
    $form->bind($student);
    $form->setInputFilter($student->getInputFilter());
    $form->setData($data);

    if ($form->isValid()) {
        $id = $this->getStudentTable()->saveStudent($form->getData());
    }

    return new JsonModel(array(
        'data' =>   'ss',
    ));*/
}

    public function delete($id)
  {
      $this->getStudentTable()->deleteStudent($id);

      return new JsonModel(array(
          'data' => 'deleted',
      ));
  }
}
