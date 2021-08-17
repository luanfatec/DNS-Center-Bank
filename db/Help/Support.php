<?php

namespace db\Support;

class Support
{
    /**
     * @param $password
     * @return false|string|null
     */
    public function bcrypt_hash_encode($password)
    {
        $options = [
            'cost' => 11,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function bcrypt_hash_verify($password, $hash)
    {
        if (password_verify($password, $hash)) {
            return true;
        } else {
            return false;
        }
    }

}