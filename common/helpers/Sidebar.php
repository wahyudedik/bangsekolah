<?php
namespace common\helpers;

use Yii;
use common\models\Menu;
use common\models\RoleMenu;

class Sidebar {

    public static function setActiveMenu($controller = 'site', $default_action = 'index', $parameter = null)
    {
        $role_id = Yii::$app->user->identity->role_id;
        $rm_id = 0;
        if ($parameter) {
            $menus = Menu::find()->where(['controller' => $controller, 'default_action' => $default_action])->all();
            foreach ($menus as $menu) {
                $roleMenu = RoleMenu::find()->where(['role_id' => $role_id, 'menu_id' => $menu->id, 'parameter' => $parameter])->one();
                if ($roleMenu) {
                    $rm_id = $roleMenu->id;
                }
            }
        } else {
            $menu = Menu::find()->where(['controller' => $controller, 'default_action' => $default_action])->one();
            if ($menu) {
                $roleMenu = RoleMenu::find()->where(['role_id' => $role_id, 'menu_id' => $menu->id])->one();
                if ($roleMenu) {
                    $rm_id = $roleMenu->id;
                }
            }
        }
        Yii::$app->session->set('active_menu', $rm_id);
    }	
}