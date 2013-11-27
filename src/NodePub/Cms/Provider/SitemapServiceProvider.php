<?php

namespace NodePub\Cms\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use NodePub\Cms\Model\Node;
use NodePub\Cms\Model\NodeType;
use NodePub\Navigation\Sitemap;
use NodePub\Navigation\SitemapTree;
use NodePub\Navigation\Twig\NavigationTwigExtension;

/**
 * Service Provider that registers sitemap loading and parsing
 */
class SitemapServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['np.sitemap.class'] = 'NodePub\\Navigation\\Sitemap';
        
        // Temporary example for dev, will be loaded from config file or db
        $app['np.sitemap.tree'] = $app->share(function($app) {
            $sitemapTree = new SitemapTree('root');
            $sitemapTree->setIsRoot(true);
            
            $homepage = new SitemapTree('Home');
            $homepage
                ->setSlug('home')
                ->setPath('/')
                ->setAttribute('id', 1)
                ->setAttribute('type', $app['np.node_types']['page']);
            
            $blog = new SitemapTree('Blog');
            $blog
                ->setSlug('blog')
                ->setPath('/blog')
                ->setAttribute('id', 2)
                ->setAttribute('type', $app['np.node_types']['blog']);
            
            $section = new SitemapTree('FAQ');
            $section
                ->setSlug('faq')
                ->setPath('/frequently-asked-questions')
                ->setAttribute('id', 5)
                ->setAttribute('type', $app['np.node_types']['section']);
            
                $s1 = new SitemapTree('Where are my keys?');
                $s1
                    ->setSlug('keys')
                    ->setPath('/keys')
                    ->setAttribute('id', 6)
                    ->setAttribute('type', $app['np.node_types']['page']);
            
                $section->addNode($s1);
                
                $s2 = new SitemapTree('Why are there so many songs about rainbows?');
                $s2
                    ->setSlug('rainbow-connection')
                    ->setPath('/rainbow-connection')
                    ->setAttribute('id', 7)
                    ->setAttribute('type', $app['np.node_types']['page']);
                
                $section->addNode($s2);
            
            $about = new SitemapTree('About');
            $about
                ->setSlug('about')
                ->setPath('/about')
                ->setAttribute('id', 3)
                ->setAttribute('type', $app['np.node_types']['page']);
                
                $contact = new SitemapTree('Contact');
                $contact
                    ->setSlug('contact')
                    ->setPath('/contact')
                    ->setAttribute('id', 4)
                    ->setAttribute('type', $app['np.node_types']['page']);
                
                $about->addNode($contact);
            
            $gallery = new SitemapTree('Portfolio');
            $gallery
                ->setSlug('portfolio')
                ->setPath('/portfolio')
                ->setAttribute('id', 8)
                ->setAttribute('type', $app['np.node_types']['gallery']);

            $sitemapTree
                ->addNode($homepage)
                ->addNode($blog)
                ->addNode($section)
                ->addNode($about)
                ->addNode($gallery)
                ;
            
            return $sitemapTree;
        });
        
        $app['np.sitemap'] = $app->share(function($app) {
            $sitemap = new $app['np.sitemap.class']($app['np.sitemap.tree']);
            $sitemap->setActivePath($app['request']->getPathInfo());
            
            return $sitemap;
        });
        
        if (isset($app['twig'])) {
            $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
                $twig->addExtension(new NavigationTwigExtension($app['np.sitemap']));
                return $twig;
            }));
        }
    }

    public function boot(Application $app)
    {
    }
}
