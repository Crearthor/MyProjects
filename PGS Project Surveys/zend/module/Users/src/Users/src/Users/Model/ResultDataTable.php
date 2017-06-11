<?php

namespace  Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Users\Model\ResultData;

class ResultDataTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveResultData(ResultData $resultdate)
    {
        $data = [
            'idquestion' => $resultdate->idquestion,
            'idresult' => $resultdate->idresult,
            'idanswer' => $resultdate->idanswer,
            'text' => $resultdate->text,

        ];
        $this->tableGateway->insert($data);

    }

}