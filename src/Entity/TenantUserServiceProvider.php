<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *  attributes = {
 *     "order"={"rating": "ASC"},
 *     "pagination_partial"= true
 *     },
 *   itemOperations={
 *     "get"={
 *              "normalization_context"={
 *              "groups"={"get-service-provider-with-provider"}
 *         }
 *     },
 *     "put"={
 *              "access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_WRITER') and object.getAuthor() == user)"
 *          }
 *      },
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"="is_granted('ROLE_WRITER')"
 *          }
 *      },
 *     denormalizationContext={
 *          "groups"={"post"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TenantUserServiceProviderRepository")
 */
class TenantUserServiceProvider
{
    const ROLE_ALL = 'ROLE_ALL';
    const ROLE_SIMPLE = 'ROLE_SIMPLE';
    const ROLE_LUX = 'ROLE_LUX';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const DEFAULT_ROLES = [self::ROLE_ALL];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tenant", inversedBy="connectServiceProvider")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $tenant_id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connetcServiceProvider")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating;


    public function getRating()
    {
        return $this->rating;
    }


    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenantId(): ?int
    {
        return $this->tenant_id;
    }

    public function setTenantId(int $tenant_id): self
    {
        $this->tenant_id = $tenant_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
