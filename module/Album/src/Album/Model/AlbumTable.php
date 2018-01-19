<?php
namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class AlbumTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    // public function getById($id)
    // {
    //     try {
    //         $whereArray = array('id' => $id);
    //         $result = $this->tableGateway->select($whereArray);
    //         $returnArray = array();
    //         foreach ($result as $projectRow) {
    //             $returnArray[] = $this->formatDBRecord($projectRow->getArrayCopy());
    //         }
    //         if (count($returnArray) == 1) {
    //             return $returnArray[0];
    //         }
    //     } catch (\Exception $e) {
    //         $this->logger->ERR($e->getMessage() . "\n" . $e->getTraceAsString());
    //     }
    //     return false;
    // }
    // protected function getSql() {
    //   try {
    //     $dbAdapter = $this->tableGateway->adapter;
    //     $sql = new Sql($dbAdapter); return $sql;
    //    }
    //     catch (Exception $e) {
    //       $this->logger->ERR($e->getMessage() . "\n" . $e->getTraceAsString());
    //     }
    //     return false;
    //   }
    public function fetchAll($page)
    {
      $dbAdapter = $this->tableGateway->adapter;
      $sql = new Sql($dbAdapter);
      $select = $sql->select();
      $select->from('class');
      $select->limit(5)->offset((int)$page);
      $statement = $sql->prepareStatementForSqlObject($select);
      $results = $statement->execute();

      $select2 = $sql->select();
      $select2->from('class');
      $select2->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));
      $statement = $sql->prepareStatementForSqlObject($select2);
      $results2 = $statement->execute();

              $data = array();
              $data2=array();
        foreach ($results as $result) {
            $data[] = $result;
        }
        foreach ($results2 as $result) {
            $data2[] = $result;
        }
        $data3=array();
        $data3[0]=$data;
        $data3[1]=$data2;
        return $data3;
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
