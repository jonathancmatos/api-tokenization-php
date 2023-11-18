<?php

namespace Source\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

/**
 *
 */
class Token {

    /** @var int */
    protected $issueAt;

    /** @var array  */
    protected $arrPayload = [];

    /**
     *
     */
    public function __construct(){
        $this->issueAt = time();
        $this->arrPayload = array(
            "iat" => $this->issueAt,
            "nbf" => $this->issueAt,
            "iss" => "localhost",
            "username" => "tokenization"
        );
    }

    /**
     * @param string $email
     * @return string|null
     */
    public function generateAccessToken(string $email):?string{
        $this->arrPayload["exp"] = $this->issueAt + ACCESS_TOKEN_EXPIRY;
        return $this->encode($email);
    }

    /**
     * @param string $email
     * @return string|null
     */
    public function generateRefreshToken(string $email):?string{
        $this->arrPayload["exp"] = $this->issueAt + REFRESH_TOKEN_EXPIRY;
        return $this->encode($email);
    }

    /**
     * @param string $extData
     * @return string|null
     */
    protected function encode(string $extData):?string{
        $this->arrPayload["email"] = $extData;
        return JWT::encode($this->arrPayload, KEY_SECRETED_TOKEN, "HS512");
    }

    /**
     * @param $jwt
     * @return stdClass|null
     */
    public function decode($jwt): ?Stdclass{
        return JWT::decode($jwt, new Key(KEY_SECRETED_TOKEN, "HS512"));
    }

}