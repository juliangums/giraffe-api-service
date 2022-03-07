<?php

namespace App\Services;

use Exception;

class HashAndSalt
{
    /**
     * Generate 32 char salt
     *
     * @throws Exception
     */
    public function salting(): string
    {
        return random_bytes(32);
    }

    public function checkHash($salt, $password, $storedPassword): bool
    {
        return self::hashing($salt, $password) == $storedPassword;
    }

    public function hashing($salt, $password): string
    {
        $hashed = hash('sha512', $salt . $password, true);

        for ($i = 1; $i < 200000; $i++) {
            $hashed = hash('sha512', $hashed, true);
        }

        return bin2hex($hashed);
    }
}
