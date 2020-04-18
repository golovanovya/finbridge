<?php

namespace app\controllers;

use OutOfBoundsException;
use sizeg\jwt\Jwt;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Controller for accepting payments
 */
class PaymentController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['accept'],
                'rules' => [
                    [
                        'actions' => ['accept'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            $body = Yii::$app->getRequest()->getRawBody();
                            /* @var $jwt Jwt */
                            $jwt = Yii::$app->jwt;
                            $token = $jwt->loadToken($body);
                            return $token !== null;
                        }
                    ]
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if ($action->id === 'accept') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * Accept payments request
     * @return mixed
     */
    public function actionAccept()
    {
        $body = Yii::$app->getRequest()->getRawBody();
        /* @var $jwt Jwt */
        $jwt = Yii::$app->jwt;
        $token = $jwt->getParser()->parse($body);
        try {
            $payments = $token->getClaim('payments');
            Yii::$app->payment->pushPayments($payments);
            return [
                'status' => 'success',
                'message' => 'success',
            ];
        } catch (InvalidArgumentException $e) {
            return [
                'message' => $e->getMessage(),
                'status' => 'error',
            ];
        } catch (OutOfBoundsException $e) {
            return [
                'message' => $e->getMessage(),
                'status' => 'error',
            ];
        }
    }
}
