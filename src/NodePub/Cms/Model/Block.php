<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="np_blocks")
 */
class Block
{
    /**
     * @Id @Column(type="integer", nullable=false) @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(name="area_name", type="string", length=128, nullable=false)
     */
    private $areaName;

    /**
     * @Column(name="display_order", type="integer", nullable=false)
     */
    private $order;

    /**
     * @Column(name="block_content_id", type="integer", nullable=false)
     */
    private $blockContentId;

    /**
     * @Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var BlockType
     * 
     * @ManyToOne(targetEntity="BlockType")
     * @JoinColumn(name="block_type_id", referencedColumnName="id")
     */
    private $blockType;

    /**
     * @var Node
     *
     * @ManyToOne(targetEntity="Node", inversedBy="blocks")
     */
    private $node;


    public function __construct()
    {
        // set initial timestamps
        $this->createdAt = $this->updatedAt = new \DateTime("now");
    }
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setAreaName($name)
    {
        $this->areaName = $name;
        
        return $this;
    }

    /**
     * @return string 
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        
        return $this;
    }

    /**
     * @return int 
     */
    public function getOrder()
    {
        return $this->order;
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
    
    /**
     * @param NodePub\Model\BlockType $blockType
     */
    public function setBlockType(\NodePub\Model\BlockType $blockType)
    {
        $this->blockType = $blockType;
        
        return $this;
    }

    /**
     * @return NodePub\Model\BlockType
     */
    public function getBlockType()
    {
        return $this->blockType;
    }

    /**
     * @param int $blockContentId
     */
    public function setBlockContentId($blockContentId)
    {
        $this->blockContentId = $blockContentId;
        
        return $this;
    }

    /**
     * @return int 
     */
    public function getBlockContentId()
    {
        return $this->blockContentId;
    }

    /**
     * @param NodePub\Model\BlockType $blockType
     */
    public function setNode(\NodePub\Model\Node $node)
    {
        $this->node = $node;
        
        return $this;
    }
}
