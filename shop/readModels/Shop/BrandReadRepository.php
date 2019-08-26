<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Brand;

class BrandReadRepository
{
    public function getAll(): array
    {
        return Brand::find()->all();
    } 

    public function find($id): ?Brand
    {
        return Brand::findOne($id);
    }
}