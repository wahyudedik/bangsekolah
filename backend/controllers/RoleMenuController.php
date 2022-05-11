<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Menu;
use common\models\User;
use common\models\Role;
use common\models\RoleMenu;

/**
 * RoleMenuController implements the CRUD actions for RoleMenu model.
 */
class RoleMenuController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'up', 'down'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)  {
                            return User::can();
                        }
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all RoleMenu models.
     * @return mixed
     */
    public function actionIndex($q = null, $role = null)
    {
        \common\helpers\Sidebar::setActiveMenu('role-menu', 'index');

        if (!$role) {
            $role = Role::find()->orderBy(['id' => SORT_ASC])->limit(1)->one();
        }
        $role = Role::findOne($role);
        $query = RoleMenu::find()->where(['role_id' => $role->id])->orderBy(['position' => SORT_ASC]);
        if ($q !== null) {
            $query->andFilterWhere([
                'or',
                ['like', 'field_name', $q],
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'role' => $role,
            'q' => $q,
        ]);
    }

    /**
     * Displays a single RoleMenu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RoleMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($role = null, $menu = null)
    {
        $lastRoleMenu = RoleMenu::find()->where(['role_id' => $role])->orderBy(['position' => SORT_DESC])->limit(1)->one();
        if ($lastRoleMenu) $lastPosisi = $lastRoleMenu->position; else $lastPosisi = 0;
        $model = new RoleMenu();
        $model->role_id = $role;
        $model->position = $lastPosisi + 1;
        $arrData = [];
        if ($menu) {
            $menu = Menu::findOne($menu);
            $model->menu_id = $menu->id;
            if ($menu->actions !== '""') {
                foreach (json_decode($menu->actions) as $action) {
                    $arrData[$action] = $action;
                }
            }
        }
        // $model->json_privileges = json_decode('["create", "read", "update", "delete"]');

        $role = Role::findOne($role);

        if ($model->load(Yii::$app->request->post())) {
            /**
             * check apakah role dan menu sudah tersimpan di role_menu
             */
            $checkRoleMenu = RoleMenu::find()->where(['role_id' => $role->id, 'menu_id' => $model->menu_id])->one();
            if ($checkRoleMenu) {
                Yii::$app->session->setFlash('danger', 'Menu '.$model->menu->nama.' sudah diasign ke '.$role->nama);
                return $this->redirect(['create', 'role' => $role->id]);
            }

            $model->privileges = json_encode($model->json_privileges);
            if (!$model->save()) {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }
            return $this->redirect(['create', 'role' => $role->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'role' => $role,
                'arrData' => $arrData,
            ]);
        }
    }

    /**
     * Updates an existing RoleMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id = null, $menu = null)
    {
        $model = $this->findModel($id);
        $model->json_privileges = json_decode($model->privileges);
        if ($menu) {
            $model->menu_id = $menu;
        }
        $arrData = [];
        if ($model->menu->actions !== '""') {
            foreach(json_decode($model->menu->actions) as $data) {
                $arrData[$data] =  $data;
            }
        }
        $privileges = '';

        if ($model->load(Yii::$app->request->post())) {
            $model->privileges = json_encode($model->json_privileges);
            $privileges = $model->privileges;
            if (!$model->save()) {
                foreach ($model->getErrors() as $error) { $message = $error[0]; }
                Yii::$app->session->setFlash('danger', $message);
            }

            /**
             * ada pengecualian di update role_menu ini
             * semua privileges untuk controller yang sama akan disetarakan
             * karena tidak memungkinkan pemisahan hak akses di controller yang sama
             */
            $menuOnSameControllers = Menu::find()->where(['controller' => $model->menu->controller, 'default_action' => $model->menu->default_action])->all();
            foreach ($menuOnSameControllers as $menuOnSameController) {
                foreach ($menuOnSameController->roleMenus as $roleMenu) {
                    if ($roleMenu->role_id == $model->role_id) {
                        $roleMenu->privileges = $privileges;
                        $roleMenu->save();
                    }
                }
            }

            return $this->redirect(['index', 'role' => $model->role->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'arrData' => $arrData,
                'menu' => $menu,
            ]);
        }
    }

    /**
     * Deletes an existing RoleMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $role = $model->role->id;
        $position = $model->position;
        $model->delete();
        
        foreach (RoleMenu::find()->where(['role_id' => $role])->andWhere(['>=', 'position', $position])->orderBy(['position' => SORT_ASC])->all() as $roleMenuAfter) {
            $roleMenuAfter->position = $roleMenuAfter->position - 1;
            $roleMenuAfter->save();
        }

        return $this->redirect(['index', 'role' => $role]);
    }

    /**
     * Finds the RoleMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RoleMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoleMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUp($id)
    {
        $roleMenu = RoleMenu::findOne($id);
        $userRoleMenus = RoleMenu::find()->where(['role_id' => $roleMenu->role_id])->orderBy(['position' => SORT_ASC])->all();
        foreach ($userRoleMenus as $rm) {
            if (($rm->position + 1) == $roleMenu->position) {
                $rm->position = $rm->position + 1;
                $rm->save();
            }
        }
        $roleMenu->position = $roleMenu->position - 1;
        $roleMenu->save();

        // return grid menu
        $role = $roleMenu->role;
        $query = RoleMenu::find()->where(['role_id' => $role->id])->orderBy(['position' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);
        return $this->renderPartial('_grid_role_menu', [
            'dataProvider' => $dataProvider,
            'role' => $role,
        ]);
    }

    public function actionDown($id)
    {
        $roleMenu = RoleMenu::findOne($id);
        $userRoleMenus = RoleMenu::find()->where(['role_id' => $roleMenu->role_id])->orderBy(['position' => SORT_ASC])->all();
        foreach ($userRoleMenus as $rm) {
            if (($rm->position - 1) == $roleMenu->position) {
                $rm->position = $rm->position - 1;
                $rm->save();
            }
        }
        $roleMenu->position = $roleMenu->position + 1;
        $roleMenu->save();
        
        // return grid menu
        $role = $roleMenu->role;
        $query = RoleMenu::find()->where(['role_id' => $role->id])->orderBy(['position' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);
        return $this->renderPartial('_grid_role_menu', [
            'dataProvider' => $dataProvider,
            'role' => $role,
        ]);
    }
}
