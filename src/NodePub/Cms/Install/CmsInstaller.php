<?php

namespace NodePub\Cms\Install;

use NodePub\Core\Install\InstallerInterface;

use NodePub\Core\Model\Site;

use NodePub\Cms\Model\SiteAttribute;
use NodePub\Cms\Model\Node;
use NodePub\Cms\Model\Block;
use NodePub\Cms\Model\PublishState;

/**
 * Installs the core db tables, populated with the params
 * entered in installation ui steps
 */
class CmsInstaller implements InstallerInterface
{
    protected $app,
        $entities;
    
    function __construct(Application $app)
    {
        $this->app = $app;
        $this->entities = array();
    }
    
    public function getEntityClasses()
    {
        return array(
            'NodePub\Cms\Model\Site',
            'NodePub\Cms\Model\SiteAttribute',
            'NodePub\Cms\Model\Node',
            'NodePub\Cms\Model\NodeAttribute',
            'NodePub\Cms\Model\NodeType',
            'NodePub\Cms\Model\PublishState',
            'NodePub\Cms\Model\Block',
            'NodePub\Cms\Model\BlockType'
        );
    }

    public function install()
    {
        $this->createDefaultSite();
        $this->createPublishStates();
        $this->createExampleNodes();
        $this->createCoreBlockTypes();
        $this->createBlocks();

        return true;
    }
    
    protected function createDefaultSite()
    {
        $site = new Site();
        $site->setName('NodePub')
            ->setHostName('nodepub.com')
            ->setTemplate('@default/layout.twig');

        $this->app['orm.em']->persist($site);
        
        $siteDescription = new SiteAttribute();
        $siteDescription
            ->setSite($site)
            ->setName('description')
            ->setValue('NodePub: A CMS that WON\'T kidnap and kill you');
            
        $this->app['orm.em']->persist($siteDescription);
        
        $this->entities['defaultSite'] = $site;
    }
    
    public function createExampleNodes()
    {
        $node = new Node();
        $node->setTitle('Welcome')
            ->setSlug('home')
            ->setPath('home')
            ->setTemplate('@default/layout.twig')
            ->setSite($this->entities['defaultSite'])
            ->setPublishState($this->entities['status.published'])
            ;
        $this->app['orm.em']->persist($node);

        $nodeDescription = new NodeAttribute();
        $nodeDescription
            ->setNode($node)
            ->setName('description')
            ->setValue('This description overrides the Site description');
        $this->app['orm.em']->persist($nodeDescription);

        $nodeTitle = new NodeAttribute();
        $nodeTitle
            ->setNode($node)
            ->setName('title')
            ->setValue('This title overrides the Site title');
        $this->app['orm.em']->persist($nodeTitle);
        
        $this->app['db']->insert('np_html_blocks', array('content' => '<p>Welcome to NodePub</p>'));
        $this->app['db']->insert('np_html_blocks', array('content' => '<p>This is an example of an editable block.</p>'));
    }
    
    protected function createPublishStates()
    {
        $stateDefinitions = array(
            'status.draft' => 'status.draft.desc',
            'status.published' => 'status.published.desc',
            'status.archived' => 'status.archived.desc');

        foreach ($stateDefinitions as $name => $desc) {
            $state = new PublishState();
            $state->setName($name)
                ->setDescription($desc);
            $this->app['orm.em']->persist($state);

            $this->entities[$name] = $state;
        }
    }
    
    protected function createCoreBlockTypes()
    {
        $htmlBlockType = $this->app['block_manager']->installBlock('HTML');
        $htmlBlockType->setCore(true);

        $markdownBlockType = $this->app['block_manager']->installBlock('Markdown');
        $markdownBlockType->setCore(true);
        
        $this->entities['htmlBlocktype'] = $htmlBlockType;
        $this->entities['markdownBlockType'] = $markdownBlockType;

        $this->entities['defaultSite']->enableBlockType($htmlType);
        $this->entities['defaultSite']->enableBlockType($markdownBlockType);
    }
    
    protected function createBlocks()
    {
        $mainBlock = new Block();
        $mainBlock
            ->setAreaName('Main')
            ->setOrder(1)
            ->setBlockContentId(1)
            ->setBlockType($this->entities['htmlBlocktype'])
            ->setNode($node);
        
        $this->app['orm.em']->persist($mainBlock);

        $sidebarBlock = new \NodePub\Model\Block();
        $sidebarBlock
            ->setAreaName('Sidebar')
            ->setOrder(1)
            ->setBlockContentId(2)
            ->setBlockType($this->entities['htmlBlocktype'])
            ->setNode($node);
        
        $this->app['orm.em']->persist($sidebarBlock);
    }
}