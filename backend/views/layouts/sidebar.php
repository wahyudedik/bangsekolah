<?php
use yii\helpers\Url;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$baseUrl = Yii::$app->request->baseUrl;

if (!$user) {
    echo '<meta http-equiv="refresh" content="time; URL='.Url::to(['site/login']).'" />';
} 
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="<?= $baseUrl ?>/images/banksekolah-logo.png" alt="BANKSEKOLAH" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
        <span class="brand-text font-weight-light">BANK SEKOLAH</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $baseUrl ?>/images/default-user.png" class="img-circle elevation-2" alt="User Image" />
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user->name ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                $activeMenu = Yii::$app->session->get('active_menu');
                if ($user) {
                    foreach ($user->role->roleMenus as $roleMenu) {
                        $menu = $roleMenu->menu;
                        if ($menu->parent_id == null) {
                            if ($menu->childs) {
                                $parentClass = '';
                                $parentStyle = 'display:none;';
                                $subMenu = '';
                                foreach ($menu->childs as $child) {
                                    if ($user->hasMenu($child->id) && $child->visibility == 1) {
                                        foreach ($user->role->roleMenus as $rm) {
                                            if($child->id == $rm->menu_id) {
                                                if ($child->visibility == 1) {
                                                    if ($rm->id == $activeMenu) {
                                                        $parentClass = 'menu-open';
                                                        $parentStyle = 'display:block;';
                                                        $childClass = 'active';
                                                    } else {
                                                        $childClass = '';
                                                    }
                                                    $subMenu.= '<li class="nav-item">
                                                        <a href="'.Url::to([$child->controller.'/'.$child->default_action]).$rm->parameter.'" class="nav-link '.$childClass.'"><i class="'.$child->icon.'"></i><p> '.($rm->label ? $rm->label : $child->name).'</p></a>
                                                    </li>';
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($menu->visibility == 1) {
                                    echo '
                                    <li class="nav-item '.$parentClass.'">
                                        <a class="nav-link" href="#">
                                            <i class="'.$menu->icon.'"></i>
                                            <p>'.($roleMenu->label ? $roleMenu->label : $menu->name).'<i class="fas fa-angle-left right"></i></p>
                                        </a>';
                                        echo '
                                        <ul class="nav nav-treeview" style="'.@$parentStyle.'">'.$subMenu.'</ul>
                                    </li>';
                                }
                            } else {
                                if ($menu->visibility == 1) {
                                    echo '<li class="nav-item">
                                        <a href="'.Url::to([$menu->controller.'/'.$menu->default_action]).$roleMenu->parameter.'" class="nav-link '.($roleMenu->id == $activeMenu ? 'active' : '').'"><i class="'.$menu->icon.'"></i><p> '.($roleMenu->label ? $roleMenu->label : $menu->name).' </p></a>
                                    </li>';
                                }
                            }
                        }
                    }
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
