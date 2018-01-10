<?php
namespace Album\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class StudentTable extends AbstractTableGateway
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll()
    {

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    public function getStudents($id)
    {
                     //$resultSet = $this->tableGateway->select(array('classs' => 'class1'));
                    /*  $sql = new Sql( $this->tableGateway->getAdapter());
                    $select = $sql->select();
                    $select->from('students');
                    $select->where(array('classs' => $id));
                    $statement = $sql->prepareStatementForSqlObject($select);
                    $results = $statement->execute();*/
                    // create a new Select object for the table album
                    //  $select2 = new Select('students');
        $sql = new Sql( $this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from('students');
        $select->where(array('pid' => $id));
             // create a new result set based on the Album entity
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Student());
             // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
                 // our configured select object
            $select,
                 // the adapter to run it against
            $this->tableGateway->getAdapter(),
                 // the result set to hydrate
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;

         //return $results;

     }

     public function saveStudent(Student $student)
     {
         $data = array(
             'name' => $student->name,
             'classs' => $student->classs,
             'email' => $student->email,
             'pid' => $student->pid,

         );

         $id = (int) $student->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getStudentById($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Student id does not exist');
             }
         }
     }

     public function getStudentById($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function deleteStudent($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}
