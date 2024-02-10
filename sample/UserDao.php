<?php

use PDO;

/**
 * @alias
 *
 * @alias
 *
 * aaa
 *
 */
class UserDao
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findOneUser(int $user_id)
    {
        return [];
    }
}
