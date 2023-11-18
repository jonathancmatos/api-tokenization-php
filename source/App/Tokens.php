<?php

namespace Source\App;

use Exception;
use Firebase\JWT\ExpiredException;
use Source\Core\Controller;
use Source\Core\Token;
use Source\Models\User;

/**
 *
 */
class Tokens extends Controller {

    /**
     *
     */
    public function __construct(){
        parent::__construct(["/refresh-token"]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function refresh(array $data):void{
        $arrData = filter_var_array($data,FILTER_SANITIZE_STRIPPED);
        $refreshToken = filter_var($arrData["refresh_token"], FILTER_SANITIZE_STRIPPED);

        try{
            if(empty($refreshToken))
                throw new Exception("O token de acesso não informado.");

            $token = new Token();
            $data = $token->decode($refreshToken);

            $user = User::where("email","=",$data->email)->first();
            if(!$user || empty($user->token))
                throw new Exception("O token informado não foi encontrado para esse usuário.");

            $accessToken = $token->generateAccessToken($user->email);
            $user->token = $accessToken;
            $user->save();

            $this->call(200,"refresh_token_success",$accessToken)->back();

        }catch (Exception $ex){
            $this->call(401,"unauthorized",$ex->getMessage())->back();
            exit();
        }
    }
}