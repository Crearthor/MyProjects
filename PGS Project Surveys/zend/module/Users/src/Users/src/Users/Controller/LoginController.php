<?php

namespace Users\Controller;
use Zend\Cache\Storage\Adapter\Session;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\User;
use Users\Model\UserTable;
use Users\Form\LoginForm;
use Zend\Db\Adapter\Driver\Mysqli\Mysqli;
use Zend\Db\Adapter\Driver\Mysqli\Statement;


class LoginController extends AbstractActionController
{


    public function getAuthService()
    {

        if(!$this->authservice)
        {

            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users','login','password', 'MD5(?)');


            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;

        }

        return $this->authservice;
    }
    public function getUserId()
    {

        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User);
        $tableGateway = new TableGateway('users', $dbAdapter, null, $resultSetPrototype);

        $login = $this->getAuthService()->getIdentity();
        $userTable = new UserTable($tableGateway);
        $iduser = $userTable->getIdUser($login);
        $session = new Container('base');
        $session->offsetSet('iduser', $iduser);;

        return true;
    }
    public function indexAction()
    {
        $session = new Container('base');
        if(!$session->offsetExists('iduser'))
        {
            $form = new LoginForm();
            $viewModel = new ViewModel(['form' => $form]);
            return $viewModel;
        }
        else
        {
            $this->getAuthService()->getStorage()->write($this->request->getPost('login'));

            return  $this->redirect()->toRoute('survey',
                array('controller'=>'survey', 'action'=>'index'));   //after successfull log in routing to profile
        }

    }

    public function processAction()
    {

        if(!$this->request->isPost())
        {
            return $this->redirect()->toRoute(null, array('controller' => 'login', 'action' => 'index'));
        }

        $this->getAuthService()->getAdapter()->setIdentity($this->request->getPost('login'))->setCredential($this->request->getPost('password'));


        $result = $this->getAuthService()->authenticate();


        if($result->isValid())
        {
            $this->getUserId();
            $this->getAuthService()->getStorage()->write($this->request->getPost('login'));
            return $this->redirect()->toRoute(Null,['cotroller' => 'login', 'action' => 'index']);
        }
        else
        {
            $post = $this->request->getPost();
            $form = new LoginForm();
            $form->setData($post);

            $model = new ViewModel(['form' => $form]);
            $form->get('login')->setMessages(['Password or Login are incorrect']);

            $model->setTemplate('users/login/index');
            return $model;
        }
        return $this->redirect()->toRoute(null,['controller' => 'login', 'action' => 'index']);
    }


}