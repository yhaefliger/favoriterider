<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;

class RidersController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminRidersController';

    /**
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))") 
     *
     * @return Response
     */
    public function indexAction()
    {
        
        return $this->render('@Modules/favoriterider/views/templates/admin/riders.html.twig');
    }
}