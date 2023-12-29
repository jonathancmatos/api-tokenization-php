<?php

namespace Source\Core;

use Exception;
use Firebase\JWT\ExpiredException;
use Source\Models\User;

/**
 *
 */
abstract class Controller{

    /** @var User|null */
    protected $user;

    /** @var array|null */
    protected $response;


    /**
     *
     */
    public function __construct(array $ignoredRoutes = []){
        header('Content-Type: application/json; charset=UTF-8');
        $currentRouter = basename($_SERVER["REQUEST_URI"]);

        if(!in_array("/{$currentRouter}", $ignoredRoutes)){
            if(!isset($_SERVER["HTTP_AUTHORIZATION"])){
                $this->call(401,"unauthorized","Você não tem autorizacão para continuar.")->back();
                exit();
            }

            $authorization = $_SERVER["HTTP_AUTHORIZATION"];
            list($accessToken) = sscanf($authorization, 'Bearer %s');
            if(empty($accessToken)){
                $this->call(401,"unauthorized","Você não tem autorizacão para continuar.")->back();
                exit();
            }

            $this->auth($accessToken);
        }
    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return $this
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "response"): Controller{
        http_response_code($code);
        if(!empty($type)){
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array|null $response
     * @return $this
     */
    protected function back(array $response = null):Controller{
        if(!empty($response))
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }


    /**
     * @param string $accessToken
     * @return void
     */
    protected function auth(string $accessToken):void{
        try {
            $token = new Token();
            $extraData = $token->decode($accessToken);

            $this->user = User::select("id")
                ->where("token", "=", $accessToken)
                ->where("email","=",$extraData->email)
                ->first();

            if(!$this->user)
                throw(new Exception("O token informado não existe ou não é válido."));

        }catch (ExpiredException $ex) {
            $this->call(401, "token_expired", $ex->getMessage())->back();
            exit();
        }catch (Exception $ex){
            $this->call(401,"unauthorized",$ex->getMessage())->back();
            exit();
        }
    }
}