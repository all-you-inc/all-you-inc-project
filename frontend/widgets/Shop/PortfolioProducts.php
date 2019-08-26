<?php

namespace frontend\widgets\Shop;

use shop\readModels\Shop\ProductReadRepository;
use yii\base\Widget;
use shop\entities\Shop\Product\Product;

class PortfolioProducts extends Widget {

    public $limit;
    private $repository;

    public function __construct(ProductReadRepository $repository, $config = []) {
        parent::__construct($config);
        $this->repository = $repository;
    }

    public function run() {
        $dataProvider = Product::find()->all();
        return $this->render('portfolio_products', [
//                    'products' => $this->repository->getFeatured($this->limit)
                    'products' => $dataProvider
        ]);
    }

}
