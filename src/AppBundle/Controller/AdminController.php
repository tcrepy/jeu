<?php
namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    public function createNewUsersEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUsersEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUsersEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }
}