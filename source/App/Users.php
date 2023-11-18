<?php

namespace Source\App;

use Exception;
use Source\Core\Controller;
use Source\Core\Token;
use Source\Models\User;

/**
 *
 */
class Users extends Controller {

    /**
     *
     */
    public function __construct(){
        parent::__construct(["/signup", "/signin", "/google-sign-in"]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function signUp(array $data):void{
        $arrData = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $name = filter_var($arrData["name"],FILTER_SANITIZE_STRIPPED);
        $email = filter_var($arrData["email"],FILTER_VALIDATE_EMAIL);
        $passwd = filter_var($arrData["passwd"],FILTER_DEFAULT);
        $phone = filter_var($arrData["phone"],FILTER_SANITIZE_STRIPPED);

        try{
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->passwd = $passwd;
            $user->phone = $phone;

            if(!$user->save())
                throw new Exception((!empty($user->error->getMessage()))
                    ? $user->error->getMessage()
                    : "Houve um erro ao salvar os dados. Por favor, tente novamente mais tarde.", 400);

            $this->call(200,"created_success","Usuário cadastrado com sucesso.")->back();

        }catch (Exception $ex){
            $this->call($ex->getCode(), "created_error", $ex->getMessage())->back();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function signIn(array $data):void{
        try{
            $arrData = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            if(in_array("", $arrData)){
                throw new Exception("E-mail e Senha são obrigatórios.", 400);
            }

            $email = filter_var($arrData["email"],FILTER_VALIDATE_EMAIL);
            $passwd = filter_var($arrData["passwd"], FILTER_DEFAULT);

            $user = User::where("email", "=", $email)->first();

            if(!$user || !password_verify($passwd, $user->passwd)){
                throw new Exception("E-mail ou Senha não são válidos.", 400);
            }

            $token = new Token();
            $accessToken = $token->generateAccessToken($user->email);
            $refreshToken = $token->generateRefreshToken($user->email);

            $user->token = $accessToken;
            $user->save();

            $this->back([
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken
            ]);

        }catch (Exception $ex){
            $this->call($ex->getCode(), "signin_error", $ex->getMessage())->back();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function googleSignIn(array $data):void{
        try{
            $arrData = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $name = filter_var($arrData["name"],FILTER_SANITIZE_STRIPPED);
            $email = filter_var($arrData["email"],FILTER_VALIDATE_EMAIL);
            $googleId = filter_var($arrData["google_id"], FILTER_SANITIZE_STRIPPED);

            if(empty($googleId) || empty($email)){
                throw new Exception("Por favor, informe os dados obrigatórios para continuar.", 400);
            }

            $user = User::where("email","=", $email)->first();

            if(!$user){
                if(empty($name)){
                    throw new Exception("Por favor, informe os dados obrigatórios para continuar.", 400);
                }

                $user = new User();
                $user->name = $name;
                $user->email = $email;
            }

            $token = new Token();
            $accessToken = $token->generateAccessToken($user->email);
            $refreshToken = $token->generateRefreshToken($user->email);

            $user->token = $accessToken;
            $user->passwd = "";
            $user->id_google = $googleId;
            $user->save();

            $this->back([
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken
            ]);

        }catch (Exception $ex){
            $this->call($ex->getCode(), "signin_error", $ex->getMessage())->back();
        }
    }

    /**
     * @return void
     */
    public function currentUser():void{
        $currentUser = User::select("id","name","email","phone", "id_google")->find($this->user->id);
        $this->back($currentUser->toArray());
    }

    /**
     * @return void
     */
    public function logout():void{
        try{
            $currentUser = User::find($this->user->id);
            $currentUser->token = "";

            if(!$currentUser->save()){
                throw new Exception("Não foi possível fazer o logout. Por favor, tente novamente.", 400);
            }

            $this->call(200, "logout_success", "true")->back();

        }catch (Exception $ex){
            $this->call($ex->getCode(), "logout_error", $ex->getMessage())->back();
        }
    }
}