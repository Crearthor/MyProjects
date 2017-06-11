<?php

namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Form\RegisterForm;
use Zend\Db\TableGateway\TableGateway;
use Users\Model\User;
use Users\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Session\Container;

class RegisterController extends AbstractActionController
{
    private $message;
    public function indexAction()
    {
        $session = new Container('base');
        if($session->offsetExists('iduser')) {

            return $this->redirect()->toRoute('survey',
                array('controller' => 'survey', 'action' => 'index'));   //after successfull log in routing to profile
        }
        $form = new RegisterForm();
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function confirmAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    protected function createUser(array $data)
    {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User);

        $tableGateway = new TableGateway('users', $dbAdapter, null, $resultSetPrototype);

        $user = new User();
        $user->exchangeArray($data);
        $userTable = new UserTable($tableGateway);
        if(!$userTable->uniqueEmail($user))
        {
            $this->message = 'email';
            return false;

        }
        else
        {
            if(!$userTable->uniqueLogin($user))
            {
                $this->message = 'login';
                return false;
            }
            $userTable->saveUser($user);
        }

        return true;
    }
    public function emailMessege()
    {
        $post = $this->request->getPost();

        $form = new RegisterForm();
        $form->setData($post);
        $model = new ViewModel(array(
            'error' => true,
            'form' => $form,
        ));

        $form->get($this->message)->setMessages([$this->message.' is already taken']); //ustaw wiadomosc

        $model->setTemplate('users/register/index');  //wroc do formularza
        return $model;
    }

    public function processAction()
    {

        if(!$this->request->isPost())
        {
            return $this->redirect()->toRoute(null, array('controller' => 'register', 'action' => 'index'));
        }

        $post = $this->request->getPost();
        $form = new RegisterForm();
        $inputFilter = new \Users\Validator\RegisterFilter();
        $form->setInputFilter($inputFilter);
        $form->setData($post);

        if(!$form->isValid())
        {
            $model = new ViewModel(array(
                'error' => true,
                'form' => $form,
                ));

            $model->setTemplate('users/register/index');
            return $model;
        }

        if($form->get('password')->getValue() != $form->get('confirm_password')->getValue())  // Jesli hasło nie jest rowne potwierdzonemu haslu to
        {
            $model = new ViewModel(array(
                'error' => true,
                'form' => $form,
            ));

            $form->get('password')->setMessages(['Podane hasła nie są identyczne']); //ustaw wiadomosc

            $model->setTemplate('users/register/index');  //wroc do formularza
            return $model;
        }

        if(!$this->createUser($form->getData()))
        {
            return $this->emailMessege();
        }

        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'register',
            'action' => 'confirm'
            ));
    }
}





















?>