<?php
namespace Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

class ResultTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($idsurvey)
    {
        $resultSet = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        return $resultSet;
    }
    public function saveResult(Result $result)
    {
        $data = [
            'idsurvey' => $result->idsurvey,
        ];
        $this->tableGateway->insert($data);

        $id = $this->tableGateway->lastInsertValue;

        return  $id;
    }



}