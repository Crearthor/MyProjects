<?php

namespace  Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Users\Model\Result;
class  ResultTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll()
    {
        $session = new Container('result');
        $idsurvey = $session->offsetGet('idsurvey');
        $resultSet = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        return $resultSet;
    }

    public function getResult($idresult)
    {
        $idresult  = (int) $idresult;
        $rowset = $this->tableGateway->select(array('idresult' => $idresult));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $idresult");
        }
        return $row;
    }

    public function saveResult(Result $result)
    {
        $data = [
            'idsurvey' => $result->idsurvey,
        ];
        $this->tableGateway->insert($data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
}
