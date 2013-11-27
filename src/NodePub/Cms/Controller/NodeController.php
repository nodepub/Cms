<?php

namespace NodePub\Cms\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use NodePub\Core\Model\Node;
use NodePub\Core\Form\Type\NodeConfigType;

class NodeController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    /**
     * Default action for Node content
     */
    public function nodeAction(Request $request, Node $node)
    {
        // Use the Node's template or default to the Site's
        $templateName = $node->getTemplate();
        $templateName = (empty($templateName)) ? $app['site']->getTemplate() : $templateName;
    
        $template = $app['twig']->loadTemplate($templateName);
        //$templateAreas = array('Main', 'Sidebar'); //$template->getAreaNames();
        
        // TODO
        // * Get template areas, only render blocks set for those areas
        // * Check if we're in edit mode, if so, add additional markup to display template areas and editable blocks
        
        return new Response($template->render(array(
            'node' => $this->prepareViewNode($node),
            'areas' => $this->renderAreas($app, $node->getBlocks()),
            'edit_mode' => true,
        )));
    }

    // public function newNodeAction(Request $request, Application $app)
    // {
    //     $node = new Node();
    //     $form = $app['form.factory']
    //         ->createBuilder(new NodeForm(), $node)
    //         ->getForm();
    // 
    //     return $app['twig']->render('@core/admin/node/new.twig', array('form' => $form->createView()));
    // }
    
    // public function postNodesAction(Request $request, Application $app)
    // {
    //     $node = new Node();
    //     $form = $app['form.factory']
    //         ->createBuilder(new NodeForm(), $node)
    //         ->getForm();
    // 
    //     $form->bindRequest($request);
    //     
    //     if ($form->isValid()) {
    //         $app['db.orm.em']->persist($node);
    //         $app['db.orm.em']->flush();
    //         
    //         if ($this->isApiRequest($request)) {
    //             // send the new node as json
    //             // $response = new Response($this->jsonEncode($node));
    //             // return $app->json($this->jsonEncode($node));
    //         }
    //         
    //         $app['session']->setFlash('success', $app['translator']->trans('created.success', array('%name%' => 'Node')));
    // 
    //         return $app->redirect($app['url_generator']->generate('admin_sitemap'));
    //         
    //     } else {
    //     
    //         $app['session']->setFlash('error', $app['translator']->trans('created.error', array('%name%' => 'Node')));
    //         
    //         if ($this->isApiRequest($request)) {
    //             $response = new Response();
    //             
    //             return $response;
    //         }
    //         
    //         // template with errors
    //         $this->getSession()->setFlash($flash);
    //     }
    // }

    // public function editNodeAction(Request $request, Application $app, $id)
    // {
    //     $node = $app['db.orm.em']
    //         ->getRepository('NodePub\Model\Node')
    //         ->find($id);
    //     
    //     if (!$node) {
    //         throw new \Exception("Page not found.", 404);
    //     }
    //     
    //     $form = $app['form.factory']
    //         ->createBuilder(new NodeForm(), $node)
    //         ->getForm();
    //     
    //     if ($request->getMethod() === 'POST') {
    //         $form->bindRequest($request);
    // 
    //         if ($form->isValid()) {
    //             $app['db.orm.em']->persist($node);
    //             $app['db.orm.em']->flush();
    // 
    //             $app['session']->setFlash('success', $app['translator']->trans('saved.success', array('%name%' => 'Node')));
    //             
    //             return $app->redirect($app['url_generator']->generate('admin_sitemap'));
    //         } else {
    //             $app['session']->setFlash('error', $app['translator']->trans('form.error', array('%name%' => 'Node')));
    //         }
    //     }
    //     
    //     return $app['twig']->render('@core/admin/node/edit.twig', array(
    //         'node' => $node,
    //         'form' => $form->createView()
    //     ));
    // }
    
    public function configureNodeAction(Request $request, Node $node)
    {
        $form = $this->app['form.factory']
            ->createBuilder(new NodeType(), $node)
            ->getForm();
        
        if ($request->getMethod() === 'POST') {
            $form->bindRequest($request);
    
            // if ($form->isValid()) {
            //     $app['db.orm.em']->persist($node);
            //     $app['db.orm.em']->flush();
            //     
            //     $app['session']->setFlash('success', $app['translator']->trans('saved.success', array('%name%' => 'Node')));
            //     
            //     return $app->redirect($app['url_generator']->generate('admin_sitemap'));
            // } else {
            //     $app['session']->setFlash('error', $app['translator']->trans('form.error', array('%name%' => 'Node')));
            // }
        }
        
        return new Response($this->app['twig']->render('@np-admin/controls/_node_config.twig',
            array(
                'node' => $node,
                'form' => $form->createView()
            )
        ));
    }

    protected function findNode($nodepath)
    {   
        $node = $this->app['np.node_provider']->findOneByPath($nodepath);
        
        if (!$node) {
            throw new \Exception("Page not found.", 404);
        }
        
        return $node;
    }

    /**
     * Append Node attributes directly to the object
     * to provide a cleaner interface in the view.
     */
    protected function prepareViewNode(Node $node)
    {
        foreach ($node->getAttributes() as $key => $value) {
            $node->$key = $value;
        }

        return $node;
    }

    // protected function renderAreas(Application $app, $blocks)
    // {
    //     $areas = array();
    // 
    //     foreach ($blocks as $block) {
    //         $area = $block->getAreaName();
    //         if (!isset($areas[$area])) {
    //             $areas[$area] = '';
    //         }
    //         
    //         $areas[$area].= $this->app['block_manager']->render($block);
    //     }
    //     
    //     return $areas;
    // }

    /**
     * Checks if request is ajax or expecting json returned
     */
    protected function isApiRequest(Request $request)
    {
        return ($request->isXmlHttpRequest() || $request->getRequestFormat() == 'json');
    }
}