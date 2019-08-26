<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Order\Order;
use yii\data\ActiveDataProvider;

class SalesReadRepository
{
    public function getSwm($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()
                ->leftJoin('shop_order_items' , 'shop_order_items.order_id = shop_orders.id')
                ->leftJoin('shop_products' , 'shop_products.id = shop_order_items.product_id')
                ->andWhere(['shop_products.created_by' => $userId])
                ->orderBy(['id' => SORT_DESC]),
            'sort' => false,
        ]);
    }

    public function getSalesFull($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()
                ->andWhere(['user_id' => $userId])
                ->with('items')
                ->orderBy(['id' => SORT_DESC]),
            'sort' => false,
        ]);
    }

    public function findSwn($id): ?Order
    {
        return Order::find()->andWhere(['id' => $id])->one();
    }
}