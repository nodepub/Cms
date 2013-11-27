<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

//use NodePub\Model\NodeAttribute;
//use NodePub\Model\Block;

use NodePub\Core\Model\NodeType;
use NodePub\Core\Model\Site;

/**
 * @Entity(repositoryClass="NodePub\Core\Model\NodeRepository") @Table(name="np_nodes")
 * @HasLifecycleCallbacks()
 */
class Node
{
    /**
     * @Id @Column(type="integer", nullable=false) @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @Column(type="string", length=128, nullable=false)
     */
    private $slug;

    /**
     * @Column(type="string", length=512, nullable=true)
     */
    private $path;

    /**
     * @Column(type="string", length=128, nullable=false)
     */
    //private $template;

    /**
     * @Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var datetime $publishedAt
     *
     * @Column(name="published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var PublishState
     *
     * @ManyToOne(targetEntity="PublishState")
     * @JoinColumns({
     *   @JoinColumn(name="publish_state_id", referencedColumnName="id")
     * })
     */
    private $publishState;

    /**
     * @var Site
     *
     * @ManyToOne(targetEntity="Site")
     * @JoinColumns({
     *   @JoinColumn(name="site_id", referencedColumnName="id")
     * })
     */
    private $site;

    /**
     * @var NodeType
     *
     * @xManyToOne(targetEntity="NodeType")
     * @xJoinColumns({
     *   @xJoinColumn(name="node_type_id", referencedColumnName="id")
     * })
     */
    private $nodeType;
    
    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="NodeAttribute", mappedBy="node")
     */
    private $attributes;
    

    private $inflector;
    
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        
        // set initial timestamps
        $this->createdAt = $this->updatedAt = new \DateTime("now");
    }
    
    # ===================================================== #
    #    Getters/Setters                                    #
    # ===================================================== #

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {   
        $this->title = $title;

        // set the slug unless it's already been set
        if (empty($this->slug) && $this->inflector !== null) {
            $this->setSlug($this->inflector->slugify($title));
        }
        
        return $this;
    }

    /**
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }

    /**
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }

    /**
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    // /**
    //  * @param string $template
    //  */
    // public function setTemplate($template)
    // {
    //     $this->template = $template;
    //     
    //     return $this;
    // }

    // /**
    //  * @return string 
    //  */
    // public function getTemplate()
    // {
    //     return $this->template;
    // }

    public function setSite(Site $site)
    {
        $this->site = $site;
        
        return $this;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function setNodeType(NodeType $nodeType)
    {
        $this->nodeType = $nodeType;
        
        return $this;
    }

    /**
     * @return NodePub\Core\Model\NodeType
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    // /**
    //  * Set publishState
    //  *
    //  * @param NodePub\Model\PublishState $publishState
    //  */
    // public function setPublishState(\NodePub\Model\PublishState $publishState)
    // {
    //     $this->publishState = $publishState;
    //     
    //     return $this;
    // }
    // 
    // /**
    //  * Get publishState
    //  *
    //  * @return NodePub\Model\PublishState 
    //  */
    // public function getPublishState()
    // {
    //     return $this->publishState;
    // }
    
    # ===================================================== #
    #    Auditing/Versioning                                #
    # ===================================================== #
    
    /**
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    // /**
    //  * @param NodePub\Model\User $createdBy
    //  */
    // public function setCreatedBy(\NodePub\Model\User $createdBy)
    // {
    //     $this->createdBy = $createdBy;
        
    //     return $this;
    // }

    // /**
    //  * @return NodePub\Model\User 
    //  */
    // public function getCreatedBy()
    // {
    //     return $this->createdBy;
    // }

    /**
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    // /**
    //  * @param \NodePub\Model\User $updatedBy
    //  */
    // public function setUpdatedBy(\NodePub\Model\User $updatedBy)
    // {
    //     $this->updatedBy = $updatedBy;
        
    //     return $this;
    // }

    // /**
    //  * @return integer 
    //  */
    // public function getUpdatedBy()
    // {
    //     return $this->updatedBy;
    // }

    /**
     * @param datetime $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
        
        return $this;
    }

    /**
     * @return datetime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
    
    # ===================================================== #
    #    Blocks & Attributes                                #
    # ===================================================== #
    
    /**
     * Get content blocks
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }
    
    /**
     * Add NodeAttribute block relation
     * 
     * @param NodePub\CoreBundle\Entity\NodeAttribute
     * @return NodePub\CoreBundle\Entity\Node
     */
    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;
        
        return $this;
    }
    
    /**
     * Get Attribute relations.
     * 
     * @param boolean
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    /**
     * Add Attribute relation
     * 
     * @param NodePub\Model\Attribute
     * @return NodePub\Model\Node
     */
    public function addAttribute(NodeAttribute $attribute)
    {
        $this->attributes[] = $attribute;
        
        return $this;
    }
    
    /**
     * Add an array of Attribute relations
     * 
     * @param array
     * @return NodePub\Model\Node
     */
    public function addAttributes(array $attributes)
    {
        foreach($attributes as $key => $attribute) {
            if (is_string($key) && is_string($attribute)) {
                $value = $attribute;
                $attribute = new Attribute();
                $attribute->setName($key);
                $attribute->setValue($value);
            }

            $this->addAttribute($attribute);
        }
        
        return $this;
    }
    
    # ===================================================== #
    #    Dependency Injection                               #
    # ===================================================== #
    
    public function setInflector($inflector)
    {
        $this->inflector = $inflector;
        
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
