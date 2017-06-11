<?php
namespace Users\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;


class ResultDataTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll()
    {
        $session = new Container("result");
        $resultSet = $this->tableGateway->select(array('idresult' => $session->offsetGet('idresult')));

        $resultSet->buffer();
        return $resultSet;
    }

    public function saveResultData(ResultData $resultdate)
    {
        $data = [

            'idresult' => $resultdate->idresult,
            'idanswer' => $resultdate->idanswer,
            'text' => $resultdate->text,

        ];
        $this->tableGateway->insert($data);

    }
}