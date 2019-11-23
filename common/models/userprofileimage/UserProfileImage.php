<?php

namespace common\models\userprofileimage;

use Yii;
use shop\entities\User\User;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "user_profile_image".
 *
 * @property integer $id
 * @property string $image_extension
 * @property string $image_name
 * @property string $folder_name
 * @property string $show_on
 * @property integer $is_show
 * @property integer $is_locked
 * @property integer $user_id
 *
 * @property Users $user
 */
class UserProfileImage extends \yii\db\ActiveRecord {

    private static $folderPath = 'profileImage';
    private static $basePath = 'static';
    public static $show_on_banner = 'banner';
    public static $show_on_profile = 'profile';
    public static $show_on_image_gallery = 'image-gallery';
    public static $show_on_video_gallery = 'video-gallery';

    public static function getfullPath($show_on, $uid = null) {
        if ($uid == null) {
            if ($show_on == static::$show_on_banner) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . \Yii::$app->user->id . '/'
                        . static::$show_on_banner . '/';
            } elseif ($show_on == static::$show_on_image_gallery) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . \Yii::$app->user->id . '/'
                        . static::$show_on_image_gallery . '/';
            } elseif ($show_on == static::$show_on_video_gallery) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . \Yii::$app->user->id . '/'
                        . static::$show_on_video_gallery . '/';
            } else {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . \Yii::$app->user->id . '/'
                        . static::$show_on_profile . '/';
            }
        } else {
            if ($show_on == static::$show_on_banner) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . $uid . '/'
                        . static::$show_on_banner . '/';
            } elseif ($show_on == static::$show_on_image_gallery) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . $uid . '/'
                        . static::$show_on_image_gallery . '/';
            } elseif ($show_on == static::$show_on_video_gallery) {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . \Yii::$app->user->id . '/'
                        . static::$show_on_video_gallery . '/';
            } else {
                return realpath(dirname(__FILE__) . '/../../../') . '/'
                        . static::$basePath . '/' . static::$folderPath . '/'
                        . $uid . '/'
                        . static::$show_on_profile . '/';
            }
        }
    }

    public static function createUserProfileImage($name, $extension, $show_on) {
        $image = new UserProfileImage;
        $image->image_extension = $extension;
        $image->image_name = $name;
        $image->folder_name = static::$folderPath;
        $image->show_on = $show_on;
        $image->is_show = 1;
        $image->user_id = \Yii::$app->user->id;
        return $image;
    }

    public static function createImage($name, $extension, $show_on) {
        $image = UserProfileImage::find()
                ->where(['user_id' => Yii::$app->user->id, 'show_on' => $show_on])
                ->one();

        if ($image == NULL) {
            $image = self::createUserProfileImage($name, $extension, $show_on);
            return $image;
        } else {
            $image->image_extension = $extension;
            $image->image_name = $name;
            $image->folder_name = static::$folderPath;
            return $image;
        }
    }

    public static function updateIsShowByType($show_on) : void
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

public function haveImage($show_on) {
if ($this != null) {
    foreach ($this as $image) {
        if ($image->show_on == $show_on) {
            return true;
        }
    }
    return false;
} else {
    return false;
}
}

public static function getGalleryVideoPath($image) {
return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' . $image->user_id . '/' . static::$show_on_video_gallery . '/' . $image->image_name . '.' . $image->image_extension;
}

public static function getGalleryImagePath($image) {
return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' . $image->user_id. '/' . static::$show_on_image_gallery . '/' . $image->image_name . '.' . $image->image_extension;
}

public static function getProfileImage($uid = null) {
if ($uid == NULL) {
    $image = UserProfileImage::find()->where(['user_id' => \Yii::$app->user->id, 'show_on' => static::$show_on_profile])->one();
    if ($image != NULL) {
        return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' . $image->user_id. '/' . static::$show_on_profile . '/' . $image->image_name . '.' . $image->image_extension;
    }
} else {
    $user = User::find()->where(['id' => $uid])->one();
    if ($user == NULL) {
        return yii\base\Exception('User Not Found');
    }
    $image = UserProfileImage::find()->where(['user_id' => $uid, 'show_on' => static::$show_on_profile])->one();
    if ($image != NULL) {
        return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' . $uid . '/' . static::$show_on_profile . '/' . $image->image_name . '.' . $image->image_extension;
    }
}
return null;
}

public static function getBannerImage($uid = null) {
if ($uid == NULL) {
    $image = UserProfileImage::find()->where(['user_id' => \Yii::$app->user->id, 'show_on' => static::$show_on_banner])->one();
    if ($image != NULL) {
        return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' . $image->user_id . '/' . static::$show_on_banner . '/' . $image->image_name . '.' . $image->image_extension;
    }
} else {
    $user = User::find()->where(['id' => $uid])->one();
    if ($user == NULL) {
        return yii\base\Exception('User Not Found');
    }
    $image = UserProfileImage::find()->where(['user_id' => $uid, 'show_on' => static::$show_on_banner])->one();
    if ($image != NULL) {
        return \Yii::$app->params['staticHostInfo'] . '/' . $image->folder_name . '/' .$image->user_id. '/' . static::$show_on_banner . '/' . $image->image_name . '.' . $image->image_extension;
    }
}
return null;
}

public static function deletePreviousFile($path) {
FileHelper::removeDirectory($path);
return true;
}

/**
 * @inheritdoc
 */
public static function tableName() {
return 'user_profile_image';
}

/**
 * @inheritdoc
 */
public function rules() {
return [
    [['image_name', 'folder_name', 'show_on', 'is_show', 'user_id'], 'required'],
    [['is_show','is_locked', 'user_id'], 'integer'],
    [[ 'image_name', 'folder_name', 'show_on'], 'string', 'max' => 255],
    [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
];
}

/**
 * @inheritdoc
 */
public function attributeLabels() {
return [
    'id' => 'ID',
    'image_extension' => 'Image Extension',
    'image_name' => 'Image Name',
    'folder_name' => 'Folder Name',
    'show_on' => 'Show On',
    'is_show' => 'Is Show',
    'is_locked' => 'Is Locked',
    'user_id' => 'User ID',
];
}

/**
 * @return \yii\db\ActiveQuery
 */
public function getUser() {
return $this->hasOne(User::className(), ['id' => 'user_id']);
}

}
