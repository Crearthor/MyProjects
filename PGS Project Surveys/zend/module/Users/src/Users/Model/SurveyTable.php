<?php
namespace Users\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

use Zend\Db\TableGateway\Feature;

class SurveyTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll()
    {
        $session = new Container('base');
        $iduser = $session->offsetGet('iduser');
        $resultSet = $this->tableGateway->select(array('iduser' => $iduser));
        return $resultSet;
    }

    public function getSurvey($idsurvey)
    {
        $idsurvey  = (int) $idsurvey;
        $rowset = $this->tableGateway->select(array('idsurvey' => $idsurvey));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $idsurvey");
        }

        return $row;
    }

    public function saveSurvey(Survey $survey)
    {
        $status = "complete";
        $session = new Container('base');
        $sessionc = new Container('creation');
        $iduser = $session->offsetGet('iduser'); //gettting iduser from session
        $title = $_POST['title'];
        $data = array(
            'status' => $status,
            'iduser'=> $survey->iduser=$iduser,
            'description' => $survey->description=$_POST['description'],
            'title'  => $survey->title=$_POST['title'],  //cos wczesniej nie dzialalo sprawdzic
        );

        if($sessionc->offsetExists('idsurvey'))
        {
            $idsurvey=$sessionc->offsetGet('idsurvey');

        }else {
            $idsurvey = (int)$survey->idsurvey;
        }
        if ($idsurvey == 0) {
            $this->tableGateway->insert($data);
            $resultSet =  $this->tableGateway->select('title = "'.$title.'" ORDER BY idsurvey DESC'); //Zablokowanie gdy wie
            $row = $resultSet->current();
            $sessionc->offsetSet('idsurvey',$row->idsurvey);
        } else {
            if ($this->getSurvey($idsurvey)) {
                $this->tableGateway->update($data, array('idsurvey' => $idsurvey));


            } else {
                throw new \Exception('Survey id does not exist');
            }
        }
    }

    public function deleteSurvey($idsurvey)
    {
        $this->tableGateway->delete(array('idsurvey' => (int) $idsurvey));
    }
}