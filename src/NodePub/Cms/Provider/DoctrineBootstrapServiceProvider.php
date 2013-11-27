<?php

namespace NodePub\Cms\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use NodePub\Cms\Model\Node;
use NodePub\Cms\Model\NodeRepository;
use NodePub\Cms\Model\NodeType;
use NodePub\Cms\Controller\NodeController;
use NodePub\Cms\Routing\NodeAdminRouting;

/**
 * Service Provider that configures Doctrine ORM
 */
class DoctrineBootstrapServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            //'db.options' => $app['config']['database']
        
            'db.options' => array(
                'driver'   => 'pdo_mysql',
                'dbname'   =>  'nodepub_dev',
                'user'     =>  'root',
                'password' =>  '',
                'host'     =>  '127.0.0.1'
            )
        ));
        
        $app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
            //"orm.proxies_dir" => "/path/to/proxies",
            "orm.em.options" => array(
                "mappings" => array(
                    array(
                        "type" => "annotation",
                        "namespace" => "NodePub\Cms\Model",
                        "path" => __DIR__ . "/../Model",
                    ),
                    array(
                        "type" => "annotation",
                        "namespace" => "NodePub\Core\Model",
                        "path" => $app['np.app_dir'] . "/../vendor/nodebub/cms/src/NodePub/Core/Model",
                    ),
                ),
            ),
        ));

        $app['db.table_prefix'] = 'np_';
    }

    public function boot(Application $app)
    {
    }
}