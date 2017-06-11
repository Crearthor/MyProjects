<?php

namespace  Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll()
    {
        $resultSet =  $this->tableGateway->select(array('login'=>$this->iduser=18));
        $session = new Container('base');
        $session->offsetSet('iduser', $this->login);
        return $resultSet;
    }
    public function getIdUser($login)
    {
        $resultSet =  $this->tableGateway->select(['login'=> $login]);
        $row = $resultSet->current();
        return $row->iduser;

    }
    public function saveUser(User $user)
    {
        $data = [
            'login' => $user->login,
            'password' => $user->password,
            'email' => $user->email,
            'name' => $user->name,
            'surname' => $user->surname
        ];
        $this->tableGateway->insert($data);

    }

    public function uniqueEmail(User $user)
    {
        $email = $user->email;
        if($this->tableGateway->select(['email'=> $email])->current() == 0)
        {
            return true;
        }
        {
            return false;
        }
    }
    public function uniqueLogin(User $user)
    {
        $login = $user->login;
        if($this->tableGateway->select(['login'=> $login])->current() == 0)
        {
            return true;
        }
        {
            return false;
        }
    }

    public function getUser($iduser)
    {
        $iduser = (int)$iduser;
        $rowset = $this->tableGateway->select(['iduser' => $iduser]);
        $row = $rowset->current();

        if(!row)
        {
            throw new \Exception('Nie mogę znaleść rekordu o ID =  '.$iduser);
        }
        return $row;

    }
}