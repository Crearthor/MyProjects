<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\Survey;          // <-- Add this import
use Users\Form\SurveyForm;

use Users\Model\Question;          // <-- Add this import
use Users\Model\Answer;

use Zend\Session\Container;

class SurveyController extends AbstractActionController
{
    protected $surveyTable;
    protected $questionTable;
    protected $answerTable;

    public function getAuthService()
    {
        if(!$this->authservice)
        {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'surveys','title','description'); //sprawdz

            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }

        return $this->authservice;
    }

    public function getSurveyTable()
    {
        if (!$this->surveyTable) {
            $sm = $this->getServiceLocator();
            $this->surveyTable = $sm->get('Users\Model\SurveyTable');
        }
        return $this->surveyTable;
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

    public function logoutAction()
    {
        $name = 'base' ;
        $container = new Container($name);
        $container = new Container('creation');
        $container->getManager()->getStorage()->clear($name);
        $container->getManager()->getStorage()->clear('creation');

        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'login',
            'action' => 'index'
        ));
    }

    public function indexAction()
    {

        $session = new Container('base');
        $sessionc = new Container('creation');

        if (!$session->offsetExists('iduser')) {
            return $this->redirect()->toRoute('users' , array(
                'controller' => 'login',
                'action' => 'index'
            ));
        }
//        if ($sessionc->offsetExists('idsurvey')) {
//            return $this->redirect()->toRoute(null, ['controller' => 'survey', 'action' => 'create']);
//
//        }


        return new ViewModel(array(
            'surveys' => $this->getSurveyTable()->fetchAll(),
        ));
    }

    public function createAction()
    {

        $session = new Container('creation');
        if($session->offsetExists('status')) {
            $idsurvey = $session->offsetGet('idsurvey');
        }

        /*$session->offsetSet('idsurvey',0);
        $session->offsetSet('idanswer',0);
        $session->offsetSet('idquestion',0);*/

        if (!$session->offsetGet('nanswer')) {
            $session->offsetSet('nanswer', 1);
        }
        if (!$session->offsetGet('nquestion')) {
            $this->getQuestionTable()->fetchAll();
            $session->offsetSet('nquestion', 1);
        }
        $form  = new SurveyForm();
        if($idsurvey!=0 && $session->offsetExists('status')) {
            $survey = $this->getSurveyTable()->getSurvey($idsurvey);
            $form->bind($survey);
            $question = $this->getQuestionTable()->getFirstQuestion();
            $this->getAnswerTable()->fetchAll();
            for ($i = 1; $i <= $session->offsetGet('AllA'); $i++) {
                $session->offsetSet('idanswer' . $i, $session->offsetGet('idanswer'));
                $session->offsetSet('nanswer', $session->offsetGet('AllA'));
                $post = $this->request->getPost()->set('text' . $i, $session->offsetGet('anText'))->set('type' . $i, $session->offsetGet('Type'));
                $this->getAnswerTable()->getAnswer();
            }
            $form  = new SurveyForm();
            $form->bind($survey);
            $form->bind($question);
            $form->setData($post);
            $session->offsetUnset('status');
        }

        $request = $this->getRequest();
        if ((isset($_POST['submita']) || isset($_POST['submitua']))) {
            $session->offsetSet('nanswer', $session->offsetGet('nanswer') + 1);
            $form = new SurveyForm();
        }

        //Deleting
        if (isset($_POST['submitdq'])) {
            if($session->offsetGet('nquestion') > ($session->offsetGet('AllQ'))&&$session->offsetGet('AllQ') > 0) {  //if u dont want save new created question
                $this->getQuestionTable()->getHighestQuestion();
            }
            if($session->offsetGet('nquestion') <= ($session->offsetGet('AllQ')))
                 $this->getQuestionTable()->deleteQuestion($session->offsetGet('idquestion'));

            if ($session->offsetGet('nquestion') > 0 && $session->offsetGet('AllQ')>0) {
                $session->offsetSet('nquestion', $session->offsetGet('nquestion') - 1);
            }
            else{   //if other record doesnt exist
                $form->setData($this->request->getPost()->set('text',"")->set('text1',"")->set('type1',0));
                $session->offsetSet('nanswer',1);
            }

        }


        $post = $this->request->getPost();
        $form->setData($post);
        //Adding Survey
        if (isset($_POST['submit']) || isset($_POST['submitq']) || isset($_POST['submitpq']) || isset($_POST['submitnq'])) {

            $survey = new Survey();
            $form->setInputFilter($survey->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $survey->exchangeArray($form->getData());
                $this->getSurveyTable()->saveSurvey($survey);
            }
            $question = new Question();
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $question->exchangeArray($form->getData());
                $this->getQuestionTable()->saveQuestion($question);
            }

            $answer = new Answer();
            $form->setInputFilter($answer->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $answer->exchangeArray($form->getData());
                $this->getAnswerTable()->saveAnswer($answer);
                if (isset($_POST['submitq'])) {
                    $post = $this->request->getPost()->set('text', "")->set('text1', "")->set('type1', 0);
                    $form->setData($post);
                    $session->offsetSet('nquestion', $session->offsetGet('AllQ') + 1);
                    $session->offsetSet('nanswer', 1);
                    $session->offsetUnset('idquestion');  //if we want add new question to DB
                }
                $valid = 1;

                if (isset($_POST['submit'])) {
                    $session->getManager()->getStorage()->clear('creation');
                    return $this->redirect()->toRoute(null, ['controller' => 'survey', 'action' => 'index']);
                }
            }

        }
        if (((isset($_POST['submitdq'])) || isset($_POST['submitpq'])&& $valid == 1) && $session->offsetGet('AllQ') >=1 && $session->offsetGet('nquestion')>0 ) {
            $session->offsetSet('N/P_Question', 'prev');
            $question = $this->getQuestionTable()->getPrevQuestion();// getting idquestion previous question
            if (!isset($_POST['submitdq']))    //if question wasnt deleted set number previous question
                $session->offsetSet('nquestion', $session->offsetGet('nquestion') - 1);
            $this->getAnswerTable()->fetchAll();
            $session->offsetSet('nanswer', 1);

            for ($i = 1; $i <= $session->offsetGet('AllA'); $i++) {

                $session->offsetSet('idanswer' . $i, $session->offsetGet('idanswer'));  //for deleting question
                $session->offsetSet('nanswer', $session->offsetGet('AllA'));
                $post = $this->request->getPost()->set('text' . $i, $session->offsetGet('anText'))->set('type' . $i, $session->offsetGet('Type'));
                $this->getAnswerTable()->getAnswer();

            }
            $form->bind($question);
            $form = new SurveyForm();
            $form->setData($post);
            $form->bind($question);

        }
        if ((isset($_POST['submitnq']) && $session->offsetGet('nquestion') < ($session->offsetGet('AllQ')) && $valid==1) || (isset($_POST['submitdq']) && $session->offsetGet('nquestion') == 0 && $session->offsetGet('AllQ') != 0)) {
            $session->offsetSet('N/P_Question', 'next');
            $question = $this->getQuestionTable()->getPrevQuestion();
            $session->offsetSet('nquestion', $session->offsetGet('nquestion') + 1);
            $this->getAnswerTable()->fetchAll();
            $session->offsetSet('nanswer', 1);
            for ($i = 1; $i <= $session->offsetGet('AllA'); $i++) {
                $session->offsetSet('idanswer' . $i, $session->offsetGet('idanswer'));
                $session->offsetSet('nanswer', $session->offsetGet('AllA'));
                $post = $this->request->getPost()->set('text' . $i, $session->offsetGet('anText'))->set('type' . $i, $session->offsetGet('Type'));
                $this->getAnswerTable()->getAnswer();
            }

            $form = new SurveyForm();
            $form->setData($post);
            $form->bind($question);

        }

        $form->get('submita')->setValue('Add Answer');
        $form->get('submit')->setValue('Save Survey');  //dodane
        $form->get('submitq')->setValue('Add Next Question');
        $form->get('submitpq')->setValue('Previous question');
        $form->get('submitnq')->setValue('Next question');
       // $form->get('submitd')->setValue('Delete Survey');
        $form->get('submitdq')->setValue('Delete question');
        for ($i = 1; $i <= $session->offsetGet('nanswer'); $i++) {
            $form->get('submitda' . $i)->setValue('Delete Answer');
            if (isset($_POST['submitda' . $i])) {
                $this->getAnswerTable()->deleteAnswer($session->offsetGet('idanswer' . $i));
                $this->getAnswerTable()->fetchAll();
                for ($j = $i; $j < $session->offsetGet('nanswer'); $j++) {
                    $post = $this->request->getPost()->set('text' . $j, $_POST['text'.($j+1)])->set('type' . $j, $_POST['type'.($j+1)]);
                }
                $form->setData($post);
                if ($session->offsetGet('nanswer') > 1) {
                    $session->offsetSet('nanswer', $session->offsetGet('nanswer') - 1);
                } else {
                    $post = $this->request->getPost()->set('text1', "")->set('type1', 0);
                    $form->setData($post);
                }
            }
        }
        if ($session->offsetGet('AllQ') == 0) {
            $session->offsetUnset('idquestion');
        }

        return array('form' => $form);

    }

    public function editAction()
    {
        $form = new SurveyForm();
        $session = new Container('creation');
        $session->offsetSet('status', "edit");
        $idsurvey = (int)$this->params()->fromRoute('idsurvey', 0);
        $session->offsetSet('idsurvey', $idsurvey);
        return $this->redirect()->toRoute(null, ['controller' => 'survey', 'action' => 'Create']);
    }
    public function deleteAction()
    {

        $idsurvey = (int) $this->params()->fromRoute('idsurvey', 0);
        if (!$idsurvey) {
            return $this->redirect()->toRoute('survey');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $idsurvey = (int) $request->getPost('idsurvey');
                $this->getSurveyTable()->deleteSurvey($idsurvey);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('survey');
        }

        $session = new Container('creation');
        $session ->getManager()->getStorage()->clear('creation');

        return array(
            'idsurvey'    => $idsurvey,
            'survey' => $this->getSurveyTable()->getSurvey($idsurvey)
        );
    }
}