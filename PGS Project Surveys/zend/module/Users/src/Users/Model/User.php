<?php

namespace Users\Model;

class User
{
    public $iduser;
    public $name;
    public $email;
    public $password;
    public $surname;
    public $login;

    public function exchangeArray($data)
    {
        $this->iduser =(isset($data['iduser'])) ? $data['iduser'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->login = (isset($data['login'])) ? $data['login'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        if(isset($data['password']))
        {
            $this->password = md5($data['password']);
        }
    }
}
