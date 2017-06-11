<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;


use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Users\Model\User;
use Users\Model\UserTable;

use Users\Model\Survey;
use Users\Model\SurveyTable;

use Users\Model\Question;
use Users\Model\QuestionTable;

use Users\Model\Answer;
use Users\Model\AnswerTable;

use Users\Model\Result;
use Users\Model\ResultTable;

use Users\Model\ResultData;
use Users\Model\ResultDataTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;



use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
			'aliases' => array(),
			'factories' => array(
                 // SERWISY

                'Users\Model\SurveyTable' =>  function($sm) {
                    $tableGateway = $sm->get('SurveyTableGateway');
                    $table = new SurveyTable($tableGateway);
                    return $table;
                },
                'SurveyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Survey());
                    return new TableGateway('surveys', $dbAdapter, null, $resultSetPrototype);
                },
                'Users\Model\QuestionTable' =>  function($sm) {
                    $tableGateway = $sm->get('QuestionTableGateway');
                    $table = new QuestionTable($tableGateway);
                    return $table;
                },
                'QuestionTableGateway' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Question());
                    return new TableGateway('questions', $dbAdapter, null, $resultSetPrototype);
                },
                'Users\Model\AnswerTable' =>  function($sm) {
                    $tableGateway = $sm->get('AnswerTableGateway');
                    $table = new AnswerTable($tableGateway);
                    return $table;
                },
                'AnswerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Answer());
                    return new TableGateway('answeres', $dbAdapter, null, $resultSetPrototype);
                },
                'Users\Model\ResultTable' =>  function($sm) {
                    $tableGateway = $sm->get('ResultTableGateway');
                    $table = new ResultTable($tableGateway);
                    return $table;
                },
                'ResultTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Result());
                    return new TableGateway('results', $dbAdapter, null, $resultSetPrototype);
                },
                'Users\Model\ResultDataTable' =>  function($sm) {
                    $tableGateway = $sm->get('ResultDataTableGateway');
                    $table = new ResultDataTable($tableGateway);
                    return $table;
                },
                'ResultDataTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ResultData());
                    return new TableGateway('resultsdata', $dbAdapter, null, $resultSetPrototype);
                },
                 'AuthService' => function($sm)
                 {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','email','password', 'MD5(?)');
						
					$authService = new AuthenticationService();
					$authService->setAdapter($dbTableAuthAdapter);
					return $authService;
                 },
                 'UserTable' =>  function($sm) 
                 {
					$tableGateway = $sm->get('UserTableGateway');
					$table = new UserTable($tableGateway);
					return $table;
    			},
                'UserTableGateway' => function ($sm) 
                {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
				},
                'UserEditForm' => function ($sm)
                {
					$form = new \Users\Form\UserEditForm();
					$form->setInputFilter($sm->get('UserEditFilter'));
					return $form;
				},
                'UserEditFilter' => function ($sm) 
                {
					return new \Users\Form\UserEditFilter();
				},

            )
       );
    }
}


































