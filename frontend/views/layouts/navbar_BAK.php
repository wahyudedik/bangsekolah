<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;
use common\models\Navbar;

// get choosen localization
$cookies = Yii::$app->request->cookies;
$lang = $cookies->getValue('language', 'id');

// get navbar tree
$navbarTree = '';
$parents = Navbar::find()->where(['level' => 1, 'status' => 1])->orderBy(['position' => SORT_ASC])->all();
foreach ($parents as $parent) {
    // menentukan selected menu
    $selected = false;
    switch ($parent->name) {
        case 'Home':
            if (Yii::$app->controller->id == 'site') $selected = true;
            break;
    }
    if (count($parent->childs) == 0) {
        // check if there any localhost things
        $cleanUrl = str_replace('http://localhost/ptpus', Yii::getAlias('@fronturl'), $parent->url);

        if ($selected) {
            $navbarTree .= '<li><a href="'.$cleanUrl.'" style="color:red;">'.$parent['name'].'</a></li>';
        } else {
            $navbarTree .= '<li><a href="'.$cleanUrl.'">'.$parent['name'].'</a></li>';
        }
    } else {
        $navbarTree .= '<li class="submenu">';
        // $navbarTree .= '<a href="javascript:void(0);" class="show-submenu">'.$parent['name'].' <i class="icon-down-open-mini"></i></a>';
        if ($selected) {
            $navbarTree .= '<a href="'.$parent->url.'" class="show-submenu" style="color:red;">'.$parent['name'].' <i class="icon-down-open-mini hidden-mobile" style="margin-left: -7px;"></i></a>';
        } else {
            $navbarTree .= '<a href="'.$parent->url.'" class="show-submenu">'.$parent['name'].' <i class="icon-down-open-mini hidden-mobile" style="margin-left: -7px;"></i></a>';
        }
        $navbarTree .= '<ul>';
        foreach ($parent->childs as $child) {
            // check if there any localhost things
            $cleanUrl = str_replace('http://localhost/ptpus', Yii::getAlias('@fronturl'), $child->url);

            if (count($child->childs) == 0) {
                $navbarTree .= '<li><a href="'.$cleanUrl.'">'.$child['name'].'</a></li>';
            } else {

                $navbarTree .= '<li class="submenu nested-submenu">
                <a href="'.$cleanUrl.'">'.$child['name'].' <i class="icon-angle-right"></i></a><ul>';
                foreach ($child->childs as $sub) {
                    $cleanUrl = str_replace('http://localhost/ptpus', Yii::getAlias('@fronturl'), $sub->url);
                    $navbarTree .= '<li><a href="'.$cleanUrl.'">'.$sub['name'].'</a></li>';
                }
                $navbarTree .= '</ul></li>';
            }
        }
        $navbarTree .= '</ul>';
        $navbarTree .= '</li>';
    }
}

// cek apakah ada user yang login
$id = Yii::$app->session->get('id');
$user = User::findOne($id);
if ($user) {
    $navbarTree .= '<li>'.Html::a('Member Page', ['user/profile']).'</li>';
}

$baseUrl = Yii::$app->request->baseUrl;
?>
<header class="sticky" style="box-shadow: 0 0 15px rgb(0 0 0 / 10%);">
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-1">
                <div id="logo">
                    <a href="<?= Url::to(['site/index']) ?>">
                        <img src="<?= $baseUrl ?>/images/pus-logo.png" alt="PT. PUS" data-retina="complete">
                    </a>
                </div>
            </div>
            <nav class="col-md-11 col-sm-11 col-xs-11">
            <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
            <div class="main-menu">
                <div id="header_menu" style="background: white; box-shadow: 0 0 15px rgb(0 0 0 / 10%);">
                    <img src="<?= $baseUrl ?>/images/ptpus-s-logo.png" width="100px" alt="PT. PUS" data-retina="complete">
                </div>
                <a href="#" class="open_close" id="close_in"><i class="icon_close"></i></a>
                <ul>
                    <?= $navbarTree ?>
                    <li class="list-search">
                        <a href="#search" id="search_bt"><i class=" icon-search"></i><span>Search</span></a>
                    </li>
                </ul>
            </div>
            </nav>
        </div>
    </div>
</header>

<style type="text/css">
    #header_menu {
        background-color: #ddd;
    }
    .navbar-top {
        height: 40px;
        background-color: #2668a9;
        padding: 8px;
        padding-left: 30px;
        padding-right: 30px;
    }

    .nested-submenu ul {
        position: relative;
        /*margin-left: 100px !important;*/
        margin-top: -40px !important;
        /*margin-left: parent(attr(value)) !important;*/
        left: 100% !important;
    }
    .header-button {
        margin-right: 10px;
        /*background: #e43234;*/
        color: white;
        font-weight: 500;
        /*padding: 5px;*/
        /*border-radius: 8px;*/
        padding: 6px 10px 6px 10px;
        border-radius: 2px;
    }
    .header-button:hover {
        color: white;
    }
    .main-menu > ul > li > a:hover {
        color: red;
    }
    .main-menu ul ul li a:hover {
        color: red;
    }

    /* Mobile */
    /*Screen mobile kecil: 575px*/
    /*Screen mobile medium: 767px*/
    @media only screen and (max-width: 991px) {
        #header_menu {
            display: none;
        }
        .main-menu ul {
            margin-top: 100px;
        }
        .submenu a {
            font-size: 35px !important;
            color: black !important;
        }
        .main-menu ul li {
            border-bottom: none;
        }
        #search_bt {
            margin-top:  50px;
            color: black !important;
            font-size: 25px;
        }
        #close_in {
            margin-right: 20px;
        }
        #close_in i {
            font-size: 50px;
            color: black !important;
        }
        .list-search {
            border-bottom: 1px solid black !important;
        }
        #search input[type="search"] {
            font-size: 40px;
        }
        .open_close {
            margin-top: 10px;
        }
    }
</style>