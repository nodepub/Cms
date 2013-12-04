<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use NodePub\Cms\Model\Node;

/**
 * @Entity @Table(name="np_attributes")
 */
class NodeAttribute extends SiteAttribute
{
    /**
     * @ManyToOne(targetEntity="Node", inversedBy="attributes")
     */
    private $node;

    public function setNode(Node $node)
    {
        $this->node = $node;
        
        return $this;
    }
}
