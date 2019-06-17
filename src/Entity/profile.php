<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UploadProfileAction;
/**
 * Class profile
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *     attributes={
 *          "order"={"id": "ASC"}
 *     },
 *     collectionOperations={
 *           "get",
 *          "post"={
 *              "method"="POST",
 *              "path"="/profiles",
 *              "controller"=UploadProfileAction::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *     }
 * )
 *
*/

class profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="avatar", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(nullable=true)
     * @Groups({"get-blog-post-with-author"})
     */
    private $url;

    public function getId()
    {
        return $this->id;
    }


    public function getFile()
    {
        return $this->file;
    }


    public function setFile($file): void
    {
        $this->file = $file;
    }


    public function getUrl()
    {
        return '/avatar/' . $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->id . ':' . $this->url;
    }


}
