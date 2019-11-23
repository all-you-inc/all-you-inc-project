<?php

namespace shop\forms\manage\Shop\Product;

use common\models\usertalent\UserTalent;
use shop\entities\Shop\Brand;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * @property PriceForm $price
 * @property QuantityForm $quantity
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property PhotosForm $photos
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $talent_id;
    public $code;
    public $name;
    public $description;
    public $weight;
    
    private $setupModel;

    const API_SETUP = 'api';
    const FRONTEND_SETUP = 'frontend';
    const BACKEND_SETUP = 'backend';

    public function __construct($setupModel = null,$config = [])
    {
        $this->setSetupModel($setupModel);
        $this->initializeModel();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ($this->setupModel == self::FRONTEND_SETUP 
            || $this->setupModel == self::API_SETUP ?
            [['talent_id','code', 'name', 'weight'], 'required'] :
            [['code', 'name', 'weight'], 'required']),

            [['code', 'name'], 'string', 'max' => 255],
            [['talent_id'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class],
            ['description', 'string'],
            ['description', 'string'],
            ['weight', 'integer', 'min' => 0],
        ];
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function talentList(): array
    {
        $query = new Query;
        $query->select(['user_talent.id','talent_master.name'])  
        ->from('user_talent')
        ->join(	'INNER JOIN','talent_master','talent_master.id = user_talent.talent_id')
        ->where(['user_id' => \Yii::$app->user->id]);

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ArrayHelper::map($data, 'id', 'name');
    }

    protected function internalForms(): array
    {
        if($this->setupModel == self::API_SETUP){
            return ['price', 'quantity', 'meta', 'photos', 'categories'];
        }
        return ['price', 'quantity', 'meta', 'photos', 'categories', 'tags', 'values'];
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

    public function initializeModel()
    {
        $this->price = new PriceForm();
        $this->quantity = new QuantityForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();

        if($this->setupModel != self::API_SETUP)
        {
            $this->tags = new TagsForm();
        }

        if($this->setupModel != self::API_SETUP)
        {
            $this->values = array_map(function (Characteristic $characteristic) {
                return new ValueForm($characteristic);
            }, Characteristic::find()->orderBy('sort')->all());
        }
            
    }
}