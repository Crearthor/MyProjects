<?php

namespace Users\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;


class QuestionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll()
    {
        $session = new Container('creation');
        $resultSes = new Container('result');
        if ($resultSes->offsetExists('idsurvey'))
            $idsurvey = $resultSes->offsetGet('idsurvey');
        else
            $idsurvey = $session->offsetGet('idsurvey');
        $resultSet = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        $session->offsetSet('AllQ', $resultSet->count());
        $resultSet->buffer();
        return $resultSet;
    }

    public function fetchAll2()
    {
        $session = new Container('fill');
        $idsurvey = $session->offsetGet('idsurvey');
        // create a new Select object for the table album

        $select = new Select('questions');
        // create a new result set b
        //ased on the Album entity
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Question());
        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
        // our configured select object
            $select->where(['idsurvey' => $idsurvey]),
            // the adapter to run it against
            $this->tableGateway->getAdapter(),
            // the result set to hydrate
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);

        return $paginator;


        return $resultSet;
    }

    public function getFirstQuestion()
    {
        $session = new Container('creation');
        $idsurvey = $session->offsetGet('idsurvey');
        $rowset = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        $row = $rowset->current();
        $session->offsetSet('idquestion', $row->idquestion);
        return $row;
    }

    public function getHighestQuestion()
    {
        $session = new Container('creation');
        $idsurvey = $session->offsetGet('idsurvey');
        $rowset = $this->tableGateway->select('idsurvey = ' . $idsurvey . ' ORDER BY idquestion DESC LIMIT 1');
        $row = $rowset->current();
        $session->offsetSet('idquestion', $row->idquestion + 1);
        return $row;
    }

    public function getQuestion($idquestion)
    {
        $session = new Container('creation');
        $idsurvey = $session->offsetGet('idsurvey');
        $idquestion = (int)$idquestion;
        $rowset = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        $row = $rowset->current();
        $session->offsetSet('AllQ', $rowset->count());
        if (!$row) {
            throw new \Exception("Could not find row $idquestion");
        }

        //     $session ->offsetSet('idquestion',$row->idquestion);
        return $row;
    }

    public function getPrevQuestion()
    {
        $session = new Container('creation');
        $idquestion = $session->offsetGet('idquestion');
        $idsurvey = $session->offsetGet('idsurvey');
        if ($session->offsetGet('N/P_Question') == "prev")
            $rowset = $this->tableGateway->select('idsurvey =' . $idsurvey . ' AND idquestion < ' . $idquestion . ' ORDER BY idquestion DESC LIMIT 1');
        if ($session->offsetGet('N/P_Question') == "next")
            $rowset = $this->tableGateway->select('idsurvey =' . $idsurvey . ' AND idquestion > ' . $idquestion . ' ORDER BY idquestion');
        $row = $rowset->current();
        $session->offsetSet('idquestion', $row->idquestion);
        if (!$row) {
            throw new \Exception("Could not find row $idquestion");
        }
        $session->offsetSet('idquestion', $row->idquestion);
        return $row;
    }


    public function saveQuestion(Question $question)
    {

        $session = new Container('creation');
        $idsurvey = $session->offsetGet('idsurvey'); //gettting iduser from session
        $text = $_POST['text'];
        $data = array(
            'text' => $question->text = $_POST['text'],
            'idsurvey' => $question->idsurvey = $idsurvey,
        );
        if ($session->offsetExists('idquestion')) {
            $idquestion = $session->offsetGet('idquestion');

        } else
            $idquestion = (int)$question->idquestion;
        if ($idquestion == 0) {

            $this->tableGateway->insert($data);
            $select = new Select('questions');       //CHANGE TABLE_NAME as per needs
            $select->where('idsurvey =' . $idsurvey . 'AND text = "1"');
            $select->order('idquestion DESC');
            $resultSet = $this->tableGateway->select('idsurvey =' . $idsurvey . ' AND text = "' . $text . '" ORDER BY idquestion DESC');
            $row = $resultSet->current();
            $session->offsetSet('idquestion', $row->idquestion);
            $this->fetchAll();
        } else {
            if ($this->getQuestion($idquestion)) {
                $this->tableGateway->update($data, array('idquestion' => $idquestion));

            } else {
                throw new \Exception('Question id does not exist');
            }
        }
    }

    public function deleteQuestion($idquestion)
    {
        $this->tableGateway->delete(array('idquestion' => (int)$idquestion));
        $this->fetchAll();
    }
}