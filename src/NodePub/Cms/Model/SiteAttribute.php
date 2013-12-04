<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use NodePub\Cms\Model\Site;

/**
 * @Entity @Table(name="np_attributes")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="subject", type="string")
 * @DiscriminatorMap({"site" = "SiteAttribute", "node" = "NodeAttribute"})
 */
class SiteAttribute
{
    /**
     * @Id @Column(type="integer", nullable=false) @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @Column(type="string", length=256, nullable=true)
     */
    private $value;

    /**
     * @Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ManyToOne(targetEntity="Site", inversedBy="attributes")
     */
    private $site;

    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime("now");
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        $this->value = $value;
        
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function setSite(Site $site)
    {
        $this->site = $site;
        
        return $this;
    }
    
    # ===================================================== #
    #    Lifecycle Callbacks                                #
    # ===================================================== #
    
    /**
     * @ORM\PreUpdate
     */
    public function updatedAt()
    {
        $this->updatedAt = new DateTime("now");
    }
}
