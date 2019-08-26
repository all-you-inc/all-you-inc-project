<?php

namespace common\models\userprofileimage;

use Yii;


/**
 * This is the model class for table "user_profile_image".
 *
 * @property integer $id
 * @property string $image_extension
 * @property string $image_name
 * @property string $folder_name
 * @property string $show_on
 * @property integer $is_show
 * @property integer $user_id
 *
 * @property Users $user
 */
class UserProfileImage extends \yii\db\ActiveRecord
{

    private static $folderPath = 'profileImage';
    private static $basePath = 'static';
    private static $profileGallery = false;
    private static $show_on_banner = 'banner';
    private static $show_on_profile = 'profile';


    public function getfullPath()
    {
        return realpath(dirname(__FILE__).'/../../../') . '\\' . static::$basePath . '\\' . static::$folderPath . '\\' ;
    }

    public function createImage($extension,$name,$show_on)
    {
        if(!static::$profileGallery)
        {
            $image = UserProfileImage::find()
                    ->where(['user_id' => Yii::$app->user->id, 'show_on' => $show_on])
                    ->one();

            if($image == NULL)
            {
                $image = new UserProfileImage;
                $image->extension = $extension;
                $image->image_name = $name;
                $image->folder_name = static::$folderPath;
                $image->show_on = $show_on;
                $image->is_show = 1;
                $image->user_id = \Yii::$app->user->id;
                return $image;
            }
            else
            {
                $image->extension = $extension;
                $image->image_name = $name;
                $image->folder_name = static::$folderPath;
                return $image;
            }
        }
        else
        {
            $this->updateIsShowByType($show_on);

            $image = new UserProfileImage;
            $image->extension = $extension;
            $image->image_name = $name;
            $image->folder_name = static::$folderPath;
            $image->show_on = $show_on;
            $image->is_show = 1;
            $image->user_id = \Yii::$app->user->id;
            return $image;
        }
    }

    public function updateIsShowByType($show_on) : void
    {
        $images = UserProfileImage::find()
                ->where(['user_id' => Yii::$app->user->id, 'show_on' => $show_on])
                ->all();
        
        foreach($images as $image)
        {
            $image->is_show = 0;
            $image->save();
        }
    }

    public function haveImage($show_on)
    {
        if($this != null)
        {
            foreach($this as $image)
            {
                if($image->show_on == $show_on)
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            return false;
        }
    }

    public static function getProfileImage()
    {
        // $images = UserProfileImage::find()
        // ->where(['user_id' => Yii::$app->user->id, 'show_on' => static::$show_on_profile, 'is_show' => 1])
        // ->one();
    
    }

    public static function getBannerImage()
    {
        
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_extension', 'image_name', 'folder_name', 'show_on', 'is_show', 'user_id'], 'required'],
            [['is_show', 'user_id'], 'integer'],
            [['image_extension', 'image_name', 'folder_name', 'show_on'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_extension' => 'Image Extension',
            'image_name' => 'Image Name',
            'folder_name' => 'Folder Name',
            'show_on' => 'Show On',
            'is_show' => 'Is Show',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
