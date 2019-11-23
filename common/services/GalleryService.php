<?php

namespace common\services;

use Yii;
use common\models\userprofileimage\UserProfileImage;
use yii\data\Pagination;
use yii\helpers\BaseFileHelper;

class GalleryService {

    public static function upload($model, $showon) {
        $result = [];
        $allErrors = '';
        if ($model->validate()) {
            $path = UserProfileImage::getfullPath($showon);
            $name = \Yii::$app->security->generateRandomString();
            if (!is_dir($path)) {
                BaseFileHelper::createDirectory($path);
            }
            if ($model->files[0]) {
                if ($model->files[0]->saveAs($path . $name . '.' . $model->files[0]->extension)) {
                    $image = UserProfileImage::createUserProfileImage($name, $model->files[0]->extension, $showon);
                    if ($image->save()) {
                        $result['status'] = 200;
                        $result['message'] = 'Successfully uploaded';
                        Yii::$app->session->setFlash('success', $result['message']);
                        return $result;
                    }
                    $error = 'Ooopss!!! some thing went wrong on upload.';
                    $allErrors .= $error . '  ';
                    $result['status'] = 500;
                    $result['message'] = $allErrors;
                    Yii::$app->session->setFlash('error', $allErrors);
                    return $result;
                }
                $error = 'File not found.';
                $allErrors .= $error . '  ';
                $result['status'] = 400;
                $result['message'] = $allErrors;
                Yii::$app->session->setFlash('error', $allErrors);
                return $result;
            }
        } else {
            foreach ($model->getErrors() as $file) {
                foreach ($file as $error) {
                    $allErrors .= $error . '  ';
                }
            }
            Yii::$app->session->setFlash('error', $allErrors);
        }
        $result['status'] = 400;
        $result['message'] = $allErrors;
        Yii::$app->session->setFlash('error', $allErrors);
        return $result;
    }

    public static function gallery($type, $user_id = null, $showon = null, $pagesize = 8, $model_id = null, $locked = false) {
        switch ($type) {
            case 'get':
                $result = [];
                if ($user_id != null) {

                    $dataProvider = UserProfileImage::find();
                    $params = ['user_id' => $user_id];

                    $dataProvider = $dataProvider->where($params);

                    if ($showon != null) {
                        $dataProvider->andWhere(['show_on' => $showon]);
                    } else {
                        $dataProvider->andWhere(['<>', 'show_on', ['profile']]);
                    }
                    if ($locked == true) {
                        $dataProvider->andWhere(['is_locked' => 0]);
                    }
                    $countQuery = clone $dataProvider;
                    $pages = new Pagination(['totalCount' => $countQuery->count()]);
                    $pages->defaultPageSize = $pagesize;
                    $dataProvider = $dataProvider->offset($pages->offset)->limit($pages->limit)->all();
                    $result['dataProvider'] = $dataProvider;
                    $result['pages'] = $pages;
                }
                return $result;
            case 'post':
            case 'put':
            case 'delete':
                if ($model_id) {
                    UserProfileImage::deleteAll(['id' => $model_id]);
                    return TRUE;
                }
                return FALSE;

            default:
                return 'Invalid W/S method';
        }
    }

}
