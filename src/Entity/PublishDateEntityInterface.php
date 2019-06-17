<?php
/**
 * Created by PhpStorm.
 * User: rmar2
 * Date: 19/03/2019
 * Time: 14:04
 */

namespace App\Entity;


interface PublishDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishDateEntityInterface;
}