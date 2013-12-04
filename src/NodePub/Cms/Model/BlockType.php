<?php

namespace NodePub\Cms\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="np_block_types")
 */
class BlockType
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
     * @Column(type="string", length=128, nullable=false)
     */
    private $namespace;

    /**
     * @Column(name="table_name", type="string", length=128, nullable=false)
     */
    private $tableName;

    /**
     * @Column(name="installed", type="boolean", nullable=false)
     */
    private $installed;

    /**
     * Specifies that BlockType is a core type
     *
     * @Column(name="core", type="boolean", nullable=false)
     */
    private $core;
    
    
    public function __construct()
    {
        $this->installed = false;
        $this->core = false;
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
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        if (is_null($this->tableName)) {
            $this->setTableName($namespace);
        }

        return $this;
    }

    /**
     * @return string 
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $tableName
     */
    protected function setTableName($namespace)
    {
        $this->tableName = sprintf('np_%s_blocks', strtolower($namespace));

        return $this;
    }

    /**
     * @return string 
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return boolean 
     */
    public function isInstalled()
    {
        return $this->installed;
    }

    /**
     * @param string $isInstalled
     */
    public function setInstalled($installed)
    {
        $this->installed = (bool) $installed;

        return $this;
    }

    /**
     * @return boolean 
     */
    public function isCore()
    {
        return $this->core;
    }

    /**
     * @param string $isCore
     */
    public function setCore($core)
    {
        $this->core = (bool) $core;

        return $this;
    }
}