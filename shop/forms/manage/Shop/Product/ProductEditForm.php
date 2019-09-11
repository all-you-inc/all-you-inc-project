<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;
    public $weight;

    private $_product;
    private $setupModel;

    const API_SETUP = 'api';
    const FRONTEND_SETUP = 'frontend';
    const BACKEND_SETUP = 'backend';

    public function __construct(Product $product, $setupModel = null, $config = [])
    {
        $this->setSetupModel($setupModel);
        $this->initializeModel($product);
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'code', 'name', 'weight'], 'required'],
            [['brandId'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            ['description', 'string'],
            ['weight', 'integer', 'min' => 0],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    protected function internalForms(): array
    {
        if($this->setupModel == self::API_SETUP)
        {
            return ['meta', 'categories'];
        }
        return ['meta', 'categories', 'tags', 'values'];
    }

    public function setSetupModel($setup)
    {
        switch($setup)
        {
            case 'api':
            {
                $this->setupModel = self::API_SETUP;
                break;
            }
            case 'frontend':
            {
                $this->setupModel = self::FRONTEND_SETUP;
                break;
            }
            default :
            {
                $this->setupModel = self::BACKEND_SETUP;
                break;
            }
        }
    }

    public function initializeModel($product)
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->weight = $product->weight;
        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
       if($this->setupModel != self::API_SETUP)
        {
            $this->tags = new TagsForm($product);
        }
        if($this->setupModel != self::API_SETUP)
        {
            $this->values = array_map(function (Characteristic $characteristic) use ($product) {
                return new ValueForm($characteristic, $product->getValue($characteristic->id));
            }, Characteristic::find()->orderBy('sort')->all());
        }
        $this->_product = $product;   
    }
}