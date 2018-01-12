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
        //print_r($_GET['a']);
        $data = array();
        foreach ($results as $result) {
            $data[] = $result;
        }
        return new JsonModel(array('data' => $data));
    }

    public function get($id)
    {
        $student = $this->getStudentTable()->getStudentByPid($id);
        if($student=='0')
        return $this->redirect()->toRoute('album', array('action' => 'error', 'page' => 'No_Student_Found'));
        else {
          $data = array();
          foreach ($student as $result) {
              $data[] = $result;
          }
          return new JsonModel(array('data' => $data));
        }
    }

    public function create($data)
    {
        if ($_POST['name']!='' && $_POST['email']!='') {
            $email = ($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return new JsonModel(array('data' => '0'));
            } else {
                $student = new Student();
                $student->exchangeArray($data);
                if (($this->getStudentTable()->getStudentByName($data['name']))&&($this->getStudentTable()->getStudentByEmail($data['email']))) {
                    $this->getStudentTable()->saveStudent($student);
                    // print_r($_POST);
                    // exit;
                    return new JsonModel(array('data' => 'Saved Successfully !'));
                } else {
                    return new JsonModel(array('data' => 'Student Already Registered !'));
                }
            }
        } else {
            return new JsonModel(array('data' => 'Please Enter Student Details !'));
        }
    }


    public function update($id, $data)
    {
        if ($data['name']!='' && $data['email']!='') {
            $email = ($data["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return new JsonModel(array('data' => '0'));
            } else {
                $student = new Student();
                $student->exchangeArray($data);
                if (($this->getStudentTable()->getStudentByName($data['name']))||($this->getStudentTable()->getStudentByEmail($data['email']))) {
                    $this->getStudentTable()->saveStudent($student);
                    // print_r($_POST);
                    // exit;
                    return new JsonModel(array('data' => 'Updated Successfully !'));
                } else {
                    return new JsonModel(array('data' => 'Student Already Registered !'));
                }
            }
        } else {
            return new JsonModel(array('data' => 'Please Enter Student Details !'));
        }
    }

    public function delete($id)
    {
        $this->getStudentTable()->deleteStudent($id);
        return new JsonModel(array(
          'data' => 'deleted',
      ));
    }
}
