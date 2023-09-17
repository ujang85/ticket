<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\User;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actionLogin()
    {
        $data = Yii::$app->getRequest()->getBodyParams();

        $username = $data['username'];
        $password = $data['password'];

        $user = User::findByUsername($username);

        if (!$user || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 401;
            return ['message' => 'Username or password is incorrect.'];
        }

        $user->generateAuthKey();
        if (!$user->save()) {
            Yii::$app->response->statusCode = 500;
            return ['message' => 'Unable to generate authentication token.'];
        }

        return ['auth_key' => $user->auth_key];
    }
}
