<?php
/**
 * Created by PhpStorm.
 * User: rmar2
 * Date: 02/04/2019
 * Time: 11:37
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *        "post"={
 *             "path"="/user/confirm"
 *         }
 *     },
 *     itemOperations={}
 * )
 *
 */
class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;

}