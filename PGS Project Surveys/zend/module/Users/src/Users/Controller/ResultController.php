<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ResultController extends AbstractActionController
{
    protected $questionTable;
    protected $answerTable;
    protected $resultTable;
    protected $resultDataTable;

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

    public function getResultTable()
    {
        if (!$this->resultTable) {
            $sm = $this->getServiceLocator();
            $this->resultTable = $sm->get('Users\Model\ResultTable');
        }
        return $this->resultTable;
    }

    public function getResultDataTable()
    {

        if (!$this->resultDataTable) {
            $sm = $this->getServiceLocator();
            $this->resultDataTable = $sm->get('Users\Model\ResultDataTable');
        }
        return $this->resultDataTable;
    }

    public function indexAction()
    {

        $session = new Container('base');
        $resultSes = new Container('result');
        $idsurvey = (int)$this->params()->fromRoute('idsurvey', 0);
        $resultSes->offsetSet('idsurvey', $idsurvey);
        if (!$session->offsetExists('iduser')) {
            return $this->redirect()->toRoute('users', array(
                'controller' => 'login',
                'action' => 'index'
            ));
        }

        return new ViewModel(array(
            'results' => $this->getResultTable()->fetchAll(),
        ));
    }

    public function viewAction()
    {
        $resultSes = new Container('result');
        $idresult = (int)$this->params()->fromRoute('idresult', 0);
        $resultSes->offsetSet('idresult', $idresult);
        $answers = $this->getAnswerTable()->fetchAll2();
        return new ViewModel(array(
            'resultsdata' => $this->getResultDataTable()->fetchAll(),
            'answers' => $answers,
            'questions' => $this->getQuestionTable()->fetchAll(),
        ));

    }

    public function deleteAction()
    {

        $idsurvey = (int)$this->params()->fromRoute('idsurvey', 0);
        if (!$idsurvey) {
            return $this->redirect()->toRoute('survey');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $idsurvey = (int)$request->getPost('idsurvey');
                $this->getSurveyTable()->deleteSurvey($idsurvey);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('survey');
        }

        $session = new Container('creation');
        $session->getManager()->getStorage()->clear('creation');

        return array(
            'idsurvey' => $idsurvey,
            'survey' => $this->getSurveyTable()->getSurvey($idsurvey)
        );
    }
}