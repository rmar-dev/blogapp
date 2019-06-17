<?php
/**
 * Created by PhpStorm.
 * User: rmar2
 * Date: 18/03/2019
 * Time: 17:36
 */

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user) : AuthoredEntityInterface;
}