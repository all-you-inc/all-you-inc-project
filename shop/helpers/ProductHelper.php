<?php

namespace shop\helpers;

use shop\entities\Shop\Product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ProductHelper
{
    public static function statusList(): array
    {
        return [
            Product::STATUS_DRAFT => 'Draft',
            Product::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function lockList(): array
    {
        return [
            0 => 'Unlocked',
            1 => 'Locked',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Product::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Product::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
    
    public static function lockStatus($status): string
    {
        if($status == 0) {
            $class = 'label label-info';
        } else {
            $class = 'label label-danger';
        }
        return Html::tag('span', ArrayHelper::getValue(self::lockList(), $status), [
            'class' => $class,
        ]);
    }
}