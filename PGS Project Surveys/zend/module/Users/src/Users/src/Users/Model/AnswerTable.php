<?php
namespace Users\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;


class AnswerTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll()
    {
        $session = new Container('creation');
        $idquestion = $session->offsetGet('idquestion');
        $resultSet = $this->tableGateway->select(array('idquestion' => $idquestion));
        $row = $resultSet->current();
        $session->offsetSet('idanswer',$row->idanswer);
        $session->offsetSet('anText',$row->text1);
        $session->offsetSet('Type',$row->type1);
        $session->offsetSet('AllA',$resultSet->count());
        return $resultSet;
    }
    public function fetchAll2()
    {

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    public function getIdQuestion($idanswer)
    {
        $rowset = $this->tableGateway->select(['idanswer' => $idanswer]);
        $row = $rowset->current();
        return $row->idquestion;
    }
    public function getAnswer1($idanswer)
    {
        $idanswer = (int)$idanswer;
        $rowset = $this->tableGateway->select(array('idanswer' => $idanswer));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $idanswer");
        }
        return $row;
    }
    public function getAnswer(){
        $session = new Container('creation');
        $idquestion = $session->offsetGet('idquestion');
        $idanswer = $session->offsetGet('idanswer');
        try{
            $resultSet = $this->tableGateway->select('idquestion = ' . $idquestion . ' AND idanswer > '.$idanswer);
            $row = $resultSet->current();

            $session->offsetSet('idanswer',$row->idanswer);

            // $session->offsetSet('nanswer',$session->offsetGet('nanswer')+1);
            $session->offsetSet('anText',$row->text1);
            $session->offsetSet('Type',$row->type1);
        }catch(\Exception $e)
        {
            $session->offsetSet('idanswer',0);
        }

        return $resultSet;

    }

    public function saveAnswer(Answer $answer)
    {

        $session = new Container('creation');

        $session->offsetSet('idanswer',0);
        $idquestion = $session->offsetGet('idquestion'); //gettting iduser from session
      //  $text = $_POST['texta'];

        for($i=1;$i<=$session->offsetGet('nanswer');$i++) {
            $data = array(
                'text' => $answer->text = $_POST['text'.$i],
                'idquestion' => $answer->idquestion = $idquestion,
                'type' => $answer-> type = $_POST['type'.$i],
            );
                $this->getAnswer();
                $idanswer= $session->offsetGet('idanswer');
                 $session->offsetSet('idanswer',$idanswer);


            if ($idanswer == 0) {
                $this->tableGateway->insert($data);
                $resultSet = $this->tableGateway->select(['text' => $text]);
                $row = $resultSet->current();
                $session->offsetSet('idanswer', $row->idanswer);
            } else {
                if ($this->getAnswer1($idanswer)) {
                    $this->tableGateway->update($data, array('idanswer' => $idanswer));

                } else {
                    throw new \Exception('Answer id does not exist');
                }
            }
            $session->offsetUnset('idanswer'.$i);
        }
    }

    public function deleteAnswer($idanswer)
    {
        $this->tableGateway->delete(array('idanswer' => (int) $idanswer));
        $this->fetchAll();
    }
}