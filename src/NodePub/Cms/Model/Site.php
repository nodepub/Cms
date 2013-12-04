<?php

namespace NodePub\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use NodePub\Cms\Model\SiteAttribute;
use NodePub\Cms\Model\BlockType;
use NodePub\Core\Model\Site as CoreSite;

/**
 * @Entity @Table(name="np_sites")
 * @HasLifecycleCallbacks()
 */
class Site extends CoreSite
{
    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="Node", mappedBy="node")
     */
    private $nodes;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="SiteAttribute", mappedBy="site")
     */
    private $attributes;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="BlockType")
     * @JoinTable(name="np_block_types_sites",
     *      joinColumns={@JoinColumn(name="site_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="block_type_id", referencedColumnName="id")}
     *      )
     */
    private $enabledBlockTypes;
    
    
    public function __construct()
    {
        $this->nodes = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->enabledBlockTypes = new ArrayCollection();
    }
    
    
    # ===================================================== #
    #    Blocks & Attributes                                #
    # ===================================================== #
    
    /**
     * Get Attributes relations
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * Add Attribute relation
     * 
     * @return NodePub\Model\Site
     */
    public function addAttribute(SiteAttribute $attribute)
    {
        $this->attributes[] = $attribute;
        
        return $this;
    }

    /**
     * Get BlockType relations
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getEnabledBlockTypes()
    {
        return $this->enabledBlockTypes;
    }
    
    /**
     * Adds a BlockType relation
     * 
     * @param NodePub\Model\BlockType
     * @return NodePub\Model\Site
     */
    public function enableBlockType(BlockType $blockType)
    {
        $this->enabledBlockTypes[] = $blockType;
        
        return $this;
    }
    
    # ===================================================== #
    #    Lifecycle Callbacks                                #
    # ===================================================== #
    
    /**
     * @PreUpdate
     */
    public function updatedAt()
    {
        $this->updatedAt = new \DateTime("now");
    }
}
