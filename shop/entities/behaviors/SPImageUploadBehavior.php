<?php

namespace shop\entities\behaviors;

use League\Flysystem\Filesystem;
use PHPThumb\GD;
use shop\forms\manage\Shop\Product\Base64EncodedFile;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use yiidreamteam\upload\ImageUploadBehavior;

class SPImageUploadBehavior extends ImageUploadBehavior
{
    // private $filesystem;

    public function __construct( $config = [])
    {
        parent::__construct($config);
        // $this->filesystem = $filesystem;
    }

    public function allowedTypes($object){
        return ( $object instanceof UploadedFile || $object instanceof Base64EncodedFile ); 
    }

   /**
     * Before validate event.
     */
    public function beforeValidate()
    {
        if ($this->allowedTypes($this->owner->{$this->attribute})) {
            $this->file = $this->owner->{$this->attribute};
            return;
        }
        // $this->file = UploadedFile::getInstance($this->owner, $this->attribute);
        
        // if (empty($this->file)) {
        //     $this->file = UploadedFile::getInstanceByName($this->attribute);
        // }

        if ($this->allowedTypes($this->file) ) {
            $this->owner->{$this->attribute} = $this->file;
        }
    }

     /**
     * Before save event.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSave()
    {
        if ($this->allowedTypes($this->file)) {
            if (!$this->owner->isNewRecord) {
                /** @var ActiveRecord $oldModel */
                $oldModel = $this->owner->findOne($this->owner->primaryKey);
                $behavior = static::getInstance($oldModel, $this->attribute);
                $behavior->cleanFiles();
            }
            $this->owner->{$this->attribute} = $this->file->baseName . '.' . $this->file->extension;
        } else { // Fix html forms bug, when we have empty file field
            if (!$this->owner->isNewRecord && empty($this->owner->{$this->attribute}))
                $this->owner->{$this->attribute} = ArrayHelper::getValue($this->owner->oldAttributes, $this->attribute, null);
        }
    }

  /**
     * After save event.
     */
    public function afterSave()
    {
        // dd(var_dump($this->allowedTypes($this->file)));
        if ($this->allowedTypes($this->file)) {
            $path = $this->getUploadedFilePath($this->attribute);
            FileHelper::createDirectory(pathinfo($path, PATHINFO_DIRNAME), 0775, true);
          
            if (!$this->file->saveAs($path)) {
                throw new Exception('File saving error.');
            }
            $this->owner->trigger(static::EVENT_AFTER_FILE_SAVE);
        }
    }




    // public function createThumbs(): void
    // {
    //     $path = $this->getUploadedFilePath($this->attribute);
    //     foreach ($this->thumbs as $profile => $config) {
    //         $thumbPath = static::getThumbFilePath($this->attribute, $profile);
    //         if ($this->filesystem->has($path) && !$this->filesystem->has($thumbPath)) {

    //             // setup image processor function
    //             if (isset($config['processor']) && is_callable($config['processor'])) {
    //                 $processor = $config['processor'];
    //                 unset($config['processor']);
    //             } else {
    //                 $processor = function (GD $thumb) use ($config) {
    //                     $thumb->adaptiveResize($config['width'], $config['height']);
    //                 };
    //             }

    //             $tmpPath = $this->getTempPath($thumbPath);
    //             FileHelper::createDirectory(pathinfo($tmpPath, PATHINFO_DIRNAME), 0775, true);
    //             file_put_contents($tmpPath, $this->filesystem->get($path));
    //             $thumb = new GD($tmpPath, $config);
    //             call_user_func($processor, $thumb, $this->attribute);
    //             FileHelper::createDirectory(pathinfo($thumbPath, PATHINFO_DIRNAME), 0775, true);
    //             $thumb->save($tmpPath);
    //             $this->filesystem->createDir(pathinfo($thumbPath, PATHINFO_DIRNAME));
    //             $this->filesystem->put($thumbPath, file_get_contents($tmpPath));
    //         }
    //     }
    // }

    /**
     * @param $path
     * @return string
     */
    private function getTempPath($path): string
    {
        return sys_get_temp_dir() . $path;
    }
}