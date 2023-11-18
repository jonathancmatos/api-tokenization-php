<?php

namespace Source\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class User extends Model {

    /** @var Exception */
    public Exception $error;

    /**
     * @var string
     */
    protected $table = "users";

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = []){
        if(!$this->validateName()
            || !$this->validateEmail()
            || !$this->validadePasswd()
            || !$this->validatePhone()
            || !parent::save($options)){
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function validateName():bool{
        if(empty($this->name)){
            $this->fail(new Exception("O nome do usuário e obrigatório."));
        }
        return true;
    }

    /**
     * @return bool
     */
    private function validateEmail():bool{
        if(empty($this->email) || !filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            $this->fail(new Exception("O e-mail informado não é válido."));
            return false;
        }

        $userEmail = null;
        if($this->id){
            $userEmail = User::where("email","=",trim($this->email))
                ->where("id", "!=", $this->id)
                ->count();
        }else{
            $userEmail = User::where("email","=",trim($this->email))->count();
        }

        if($userEmail){
            $this->fail(new Exception("O e-mail informado já existe."));
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function validadePasswd():bool{
        if(!empty($this->id_google)) return true;

        if(empty($this->passwd) || !filter_var($this->passwd, FILTER_DEFAULT)){
           $this->fail(new Exception("A senha informada é invalida."));
           return false;
        }

        if(strlen($this->passwd) < CONF_PASSWD_MIN_LEN){
            $this->fail(new Exception("A senha deve ter no mínimo ".CONF_PASSWD_MIN_LEN." dígitos."));
            return false;
        }

        if(password_get_info($this->passwd)["algo"]){
            return true;
        }

        $this->passwd = password_hash($this->passwd, PASSWORD_DEFAULT);
        return true;
    }

    /**
     * @return bool
     */
    private function validatePhone():bool{
        if(!empty($this->phone) && strlen($this->phone) != 11){
            $this->fail(new Exception("O número de telefone não é válido."));
            return false;
        }

        return true;
    }

    /**
     * @param Exception $ex
     * @return void
     */
    private function fail(Exception $ex):void{
        $this->error = $ex;
    }
}