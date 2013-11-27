<?php

namespace NodePub\Cms\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use NodePub\Cms\Model\NodeRepository;
use NodePub\Cms\Model\NodeType;
use NodePub\Cms\Controller\NodeController;
use NodePub\Cms\Routing\NodeAdminRouting;

/**
 * Service Provider that registers sitemap loading and parsing
 */
class CmsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['np.node_types'] = $app->share(function($app) {
            $page = new NodeType();
            $page->setName('Page');
            
            $section = new NodeType();
            $section->setName('Section');
            
            $blog = new NodeType();
            $blog->setName('Blog');
            
            $gallery = new NodeType();
            $gallery->setName('Gallery');
            
            return array(
                'page'    => $page,
                'section' => $section,
                'blog'    => $blog,
                'gallery' => $gallery
            );
        });
        
        $app['np.node_provider'] = $app->share(function($app) {
            $repo = new NodeRepository();
            
            return $repo;
        });
        
        $app['np.node_admin.mount_point'] = '/nodes';
        
        if (isset($app['np.admin']) && true === $app['np.admin']) {
            
            $app['np.node_admin.controller'] = $app->share(function($app) {
                return new NodeController($app);
            });
            
            $app['np.admin.controllers'] = $app->share($app->extend('np.admin.controllers', function($controllers, $app) {
                
                $nodeControllers = new NodeAdminRouting();
                $nodeControllers = $nodeControllers->connect($app);
                
                $controllers->mount($app['np.node_admin.mount_point'], $nodeControllers);
                return $controllers;
            }));
        }
    }

    public function boot(Application $app)
    {
    }
}
