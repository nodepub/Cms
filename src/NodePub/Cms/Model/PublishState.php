<?php

namespace NodePub\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="np_publish_states")
 */
class PublishState
{
    /**
     * @Id @Column(type="integer", nullable=false) @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @Column(name="description", type="string", length=256, nullable=true)
     */
    private $description;

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
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}