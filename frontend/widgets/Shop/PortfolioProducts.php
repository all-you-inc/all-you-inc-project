<?php

namespace frontend\widgets\Shop;

use shop\readModels\Shop\ProductReadRepository;
use yii\base\Widget;
use shop\entities\Shop\Product\Product;

class PortfolioProducts extends Widget {

    public $limit;
    public $createdBy;
    public $talent_id;
    private $repository;

    public function __construct(ProductReadRepository $repository, $config = []) {
        parent::__construct($config);
        $this->repository = $repository;
    }

    public function run() {
        $dataProvider = Product::find();
        $dataProvider = $dataProvider->where(['status' => 1, 'is_locked'=>0]);

        if (isset($this->createdBy) && $this->createdBy !== null)
            $dataProvider = $dataProvider->andWhere(['created_by' => $this->createdBy]);

        if (isset($this->talent_id) && $this->talent_id !== null)
            $dataProvider = $dataProvider->andWhere(['talent_id' => $this->talent_id]);

//        dd($dataProvider);
        $dataProvider = $dataProvider->all();

        return $this->render('portfolio_products', [
//                    'products' => $this->repository->getFeatured($this->limit)
                    'products' => $dataProvider
        ]);
    }

}
