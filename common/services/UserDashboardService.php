<?php

namespace common\services;

use Yii;
use DateTime;
use DateInterval;
use DatePeriod;

class UserDashboardService {
    
    public static function getTotalSales($start, $end, $noProduct, $talentId = NULL, $productId = NULL) {
        $userId = Yii::$app->user->id;
        $startTimestamp = $start->getTimestamp();
        $connection = Yii::$app->getDb();
        
        if (!$noProduct) {
            $command = $connection->createCommand(
                    "SELECT SUM(shop_order_items.price * shop_order_items.quantity) as sales 
                    FROM shop_order_items 
                    INNER JOIN shop_products ON shop_order_items.product_id = shop_products.id"
                    . ($productId ? " AND shop_products.id = {$productId}" : "")
                    . ($talentId ? " AND shop_products.talent_id = {$talentId}" : "")
                    . " AND shop_products.created_by = {$userId} 
                    INNER JOIN shop_orders ON shop_orders.id = shop_order_items.order_id AND shop_orders.created_at >= {$startTimestamp}");
            
            $result = $command->queryAll();
        } else {
            $result[0]['sales'] = 0;
        }
        
        return $result[0]['sales'];
    }
    
    public static function getActiveChart($start, $end, $noProduct, $talentId = NULL, $productId = NULL) {
        $userId = Yii::$app->user->id;
        $activeChart = [
            'data' => [],
            'labels' => ['Monthly Sales', 'Total Fans'],
            'xLabels' => 'month',
            'lineColors' => ['#008000', '#2B61E7'],
        ];
        
        $current = new DateTime();
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($start, $interval, $end);
        $connection = Yii::$app->getDb();

        foreach ($period as $datetime) {
            $monthYear = $datetime->format('Y-m');
            
            if ($datetime > $current || $noProduct) {
                $activeChart['data'][$monthYear] = NULL;
                continue;
            }
            
            $startTimestamp = $datetime->getTimestamp();
            $endTimestamp = $datetime->modify('last day of this month')->setTime(23,59,59)->getTimestamp();

            $command = $connection->createCommand(
                    "SELECT SUM(shop_order_items.price * shop_order_items.quantity) as sales 
                    FROM shop_order_items 
                    INNER JOIN shop_products ON shop_order_items.product_id = shop_products.id"
                    . ($productId ? " AND shop_products.id = {$productId}" : "")
                    . ($talentId ? " AND shop_products.talent_id = {$talentId}" : "")
                    . " AND shop_products.created_by = {$userId} 
                    INNER JOIN shop_orders ON shop_orders.id = shop_order_items.order_id AND shop_orders.created_at BETWEEN {$startTimestamp} AND {$endTimestamp}");
            
            $result = $command->queryAll();

            $activeChart['data'][$monthYear]['sales'] = $result[0]['sales'] ?? 0;
            $activeChart['data'][$monthYear]['fans'] = 0;
        }
        
        return $activeChart;
    }
    
    public static function getActiveMap($start, $end, $noProduct, $talentId = NULL, $productId = NULL) {
        $userId = Yii::$app->user->id;
        $startTimestamp = $start->getTimestamp();
        $connection = Yii::$app->getDb();
        
        if (!$noProduct) {
            $command = $connection->createCommand(
                    "SELECT DISTINCT users.name, user_address.latitude, user_address.longitude
                    FROM shop_order_items 
                    INNER JOIN shop_products ON shop_order_items.product_id = shop_products.id"
                    . ($productId ? " AND shop_products.id = {$productId}" : "")
                    . ($talentId ? " AND shop_products.talent_id = {$talentId}" : "")
                    . " AND shop_products.created_by = {$userId} 
                    INNER JOIN shop_orders ON shop_orders.id = shop_order_items.order_id AND shop_orders.created_at >= {$startTimestamp} 
                    INNER JOIN user_address ON user_address.id = shop_orders.delivery_address 
                    INNER JOIN users ON users.id = user_address.user_id");
            
            $salesData = $command->queryAll();
        } else {
            $salesData = NULL;
        }
        
        $activeMap = [
            'sales' => [
                'color' => '#008000',
                'data' => $salesData
            ],
            'fans' => [
                'color' => '#2B61E7',
                'data' => NULL
            ]
        ];
        
        return $activeMap;
    }
}