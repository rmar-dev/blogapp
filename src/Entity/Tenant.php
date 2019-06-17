<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TenantRepository")
 */
class Tenant
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;


    /**
     * @ORM\OneToMany(targetEntity="TenantUserServiceProvider", mappedBy="tenant_id", fetch="EXTRA_LAZY")
     */
    private $connectServiceProvider;

    public function __construct()
    {
        $this->connectServiceProvider = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|TenantUserServiceProvider[]
     */
    public function getConnectServiceProvider()
    {
        return $this->connectServiceProvider;
    }

    /**
     * @param mixed $connectServiceProvider
     */
    public function setConnectServiceProvider($connectServiceProvider): void
    {
        $this->connectServiceProvider = $connectServiceProvider;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
