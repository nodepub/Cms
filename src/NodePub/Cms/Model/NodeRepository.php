<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\EntityRepository;

/**
 * Temporarily stubbed out for now, will eventually be db
 */
class NodeRepository extends EntityRepository
{
    public function findOneById($id)
    {
        if (isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }
    }

    /**
     * @param string $path
     * @return Node or null
     */
    public function findOneByPath($path)
    {
        foreach ($this->nodes as $node) {
            if ($node->getPath() === $path) {
                return $node;
            }
        }
    }
    
    public function addNodes($nodes)
    {
        if (is_array($nodes) || $nodes instanceof \Traversable) {
            foreach ($nodes as $node) {
                if ($node instanceof Node) {
                    $this->nodes[$node->id] = $node;
                } else {
                    $n = new Node();
                    $n
                        ->setTitle($node['title'])
                        ->setSlug($node['slug'])
                        ->setPath($node['path'])
                        ->setNodeType($node['type'])
                        ;
                }
            }
        }
        
        return $this;
    }
}