<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;
 use Zend\Paginator\Adapter\DbSelect;
 use Zend\Paginator\Paginator;
 use Zend\Db\Sql\Select;
 use Zend\Db\ResultSet\ResultSet;

 class AlbumTable
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

     public function getAlbum($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             //throw new \Exception("Could not find row $id");
             return 0;
         }
         return $row;
     }
     public function getAlbumByName($name)
     {
         $rowset = $this->tableGateway->select(array('name' => $name));
         $row = $rowset->current();
         if (!$row) {
             return true;
         } else {
             return false;
         }
     }

     public function saveAlbum(Album $album)
     {
         $data = array(
             'name' => $album->name,
         );

         $id = (int) $album->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getAlbum($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Album id does not exist');
             }
         }
     }

     public function deleteAlbum($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }
