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


class UserTable extends AbstractTableGateway
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

     public function saveUser(User $user)
     {
         $data = array(
             'username' => $user->namename,
             'password' => $user->password,


         );

         $id = (int) $user->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getUserById($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('User id does not exist');
             }
         }
     }

     public function getUserByUsername($username)
     {
         $rowset = $this->tableGateway->select(array('username' => $username));
         $row = $rowset->current();
         if ($row) {
             return true;
         }
         else
         return false;
     }

     public function getUserByPassword($password)
     {
         $rowset = $this->tableGateway->select(array('password' => $password));
         $row = $rowset->current();
         if ($row) {
             return true;
         }
         else
         return false;
     }
     public function getUserById($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             return '0';
         }
         return $row;
     }

     public function deleteUser($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}
