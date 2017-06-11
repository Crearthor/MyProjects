<?php
namespace Users\Controller;
use Users\Model\Result;
use Users\Model\ResultData;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\FillTable;
use Users\Model\ResultTable;
use Zend\Session\Container;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Users\Model\ResultDataTable;
class FillController extends  AbstractActionController
{
    protected $questionTable;

    public function indexAction()
    {
        $idsurvey = (int) $this->params()->fromRoute('idsurvey', 0);
        // grab the paginator from the AlbumTable
        $session = new Container('fill');
        if($idsurvey)  $session->offsetSet('idsurvey', $idsurvey);
        // grab the paginator from the AlbumTable
        $paginator = $this->getQuestionTable()->fetchAll2(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(1);

        $answers = $this->getAnswerTable()->fetchAll2();

        return new ViewModel(array(
            'paginator' => $paginator,
            'answers' => $answers,
            'idsurvey' => $idsurvey
        ));
    }
    public function getQuestionTable()
    {
        if (!$this->questionTable) {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('Users\Model\QuestionTable');
        }
        return $this->questionTable;
    }
    public function getAnswerTable()
    {
        if (!$this->answerTable) {
            $sm = $this->getServiceLocator();
            $this->answerTable = $sm->get('Users\Model\AnswerTable');
        }
        return $this->answerTable;
    }
    public function saveAction()
    {
        if(!$post = $this->getRequest()->getPost("data"))
        {
            return $this->redirect()->toRoute('survey', array('controller' => 'survey', 'action' => 'index'));
        }
        $post = json_decode($post);
        $idsurvey = $post[0][0];
        $idresult = $this->saveResult($idsurvey);
        for($i = 1; $i<count($post);$i++)
        {
            $idanswer = $post[$i][0];

            $idquestion = $this->getAnswerTable()->getIdQuestion($idanswer);
            $data=[
                'idresult' => $idresult,
                'idquestion'=>$idquestion,
                'idanswer' => $idanswer,
                'text' => $post[$i][1],
            ];
            $this->saveResultData($data);
        }





        return true;
    }
    public function saveResult($idsurvey)
    {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Result());

        $tableGateway = new TableGateway('results', $dbAdapter, null, $resultSetPrototype);

        $result = new Result();
        $result->exchangeArray($idsurvey);
        $resultTable = new ResultTable($tableGateway);

        return $resultTable->saveResult($result);
    }
    public function saveResultData($data)
    {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ResultData());
        $tableGateway = new TableGateway('resultsdata', $dbAdapter, null, $resultSetPrototype);

        $resultdata = new ResultData();
        $resultdata->exchangeArray($data);
        $resultDataTable = new ResultDataTable($tableGateway);

        $resultDataTable->saveResultData($resultdata);
    }

}
