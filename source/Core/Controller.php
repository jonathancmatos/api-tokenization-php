<?php

namespace source\Core;

use Source\Models\User;

/**
 *
 */
abstract class Controller{

    /** @var User|null */
    protected $user;

    /** @var array|false */
    protected $headers;

    /** @var array|null */
    protected $response;

    /**
     *
     */
    public function __construct(){
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();
    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return $this
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): Controller{
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
        if(!empty($response)){
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }

}