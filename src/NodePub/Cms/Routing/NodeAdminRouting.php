<?php

namespace NodePub\Cms\Routing;

use Silex\Application;
use Silex\ControllerProviderInterface;

class NodeAdminRouting implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // Convert hostname into site object
        $nodeConverter = function($nodeId) use($app) {
            if (!$node = $app['np.node_provider']->getOneById($nodeId)) {
                throw new \Exception("Page not found", 404);
            }

            return $node;
        };

        $controllers = $app['controllers_factory'];
        
        $controllers->post('/', 'np.node_admin.controller:postNodesAction')
            ->bind('admin_nodes_post');
        
        $controllers->get('/new', 'np.node_admin.controller:newNodeAction')
            ->bind('admin_nodes_new_node');

        $controllers->match('/{node}/config', 'np.node_admin.controller:configureNodeAction')
            ->convert('node', $nodeConverter)
            ->bind('admin_nodes_configure_node');

        return $controllers;
    }
}