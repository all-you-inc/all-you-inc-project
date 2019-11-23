<?php

namespace api\controllers;

use DateTime;
use Yii;
use yii\rest\Controller;
use api\helpers\DataHelper;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Order\Order;
use shop\entities\User\User;
use common\models\userdevice\UserDevice;
use common\services\UserDashboardService;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     host="api.allyouinc.siliconplex",
 *     schemes={"http"},
 *     produces={"application/json","application/xml"},
 *     consumes={"application/json","application/xml","application/x-www-form-urlencoded"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="ALL YOU INC API",
 *         description="HTTP JSON API",
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="OAuth2",
 *         type="oauth2",
 *         flow="password",
 *         tokenUrl="http://api.allyouinc.siliconplex/oauth2/token"
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 *     @SWG\Definition(
 *         definition="ErrorModel",
 *         type="object",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */
class SiteController extends Controller {

    /**
     * @SWG\Get(
     *     path="/",
     *     tags={"Info"},
     *     @SWG\Response(
     *         response="200",
     *         description="API version",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="version", type="string")
     *         ),
     *     )
     * )
     */
    public function actionIndex(): array {
        return [
            'version' => '1.0.0',
        ];
    }

    public function actionDashboard() {
        $params = Yii::$app->request->get();
        $user = Yii::$app->user->identity->getUser();
        $talentId = isset($params['talentId']) ? $params['talentId'] : NULL;
        $productId = isset($params['productId']) ? $params['productId'] : NULL;
        
        $conditions = "created_by = {$user->id}";
        if ($talentId) { $conditions .= " AND talent_id = {$params['talentId']}"; }
        $products = Product::find()->where($conditions)->orderBy(['name' => SORT_ASC])->all();
        $productsResponse = [];
        
        foreach ($products as $product) {
            $productsResponse[] = DataHelper::serializeProductShort($product);
        }

        $current = new DateTime();
        $start = (new DateTime())->setDate($current->format('Y'), 1, 1)->setTime(0,0,0);
        $end = (new DateTime())->setDate($current->format('Y'), 12, 31)->setTime(23,59,59);
        
        $totalSales = UserDashboardService::getTotalSales($start, $end, $products ? FALSE : TRUE, $talentId, $productId);
        $activeChart = UserDashboardService::getActiveChart($start, $end, $products ? FALSE : TRUE, $talentId, $productId);
        $activeMap = UserDashboardService::getActiveMap($start, $end, $products ? FALSE : TRUE, $talentId, $productId);

        $data =  [
            'user' => DataHelper::serializeUser($user),
            'membership_id' => $user->getMembershipId(),
            'products' => $productsResponse,
            'total_orders' => $user->getTotalOrders(),
            'total_sales' => $totalSales,
            'activeChart' => $activeChart,
            'activeMap' => $activeMap
        ];
        
        return [
            'status' => 200,
            'data' => $data
        ];
    }

    public function actionDeviceToken() {

        $postData = \Yii::$app->getRequest()->post();

        if (isset($postData) && isset($postData['token']) && isset($postData['type'])) {
            $device = UserDevice::find()
                    ->where(
                            [
                                'token' => $postData['token'],
                                'type' => $postData['type']
                            ]
                    )
                    ->one();

            if ($device == null) {
                $device = new UserDevice();
                $device->created_at = time();
            }
            $device->attributes = $postData;
            $device->modified_at = time();

            if ($device->save()) {
                return ["status" => 200];
            } else {
                return ["status" => 400, 'message' => "Data not saved"];
            }
        }

        return [
            'status' => 400,
            'message' => "Bad Request"
        ];
    }

}
