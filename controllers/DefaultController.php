<?php

namespace thefx\blocks\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
//        $user = User::findOne(31866);
//        var_dump($user);
//        die;

//        $slider = (new \yii\db\Query())
//            ->select('text,text_en,position')
//            ->from('{{%slider}}')
//            ->where(['public' => "1"])
//            ->orderBy('position')
//            ->all();
//
//        $doctors_array = (new \yii\db\Query())
//            ->select('photo,fio,fio_en,post,post_en,degree,degree_en,slug,staff')
//            ->from('{{%doc}}')
//            ->where(['public' => "1"])
//            ->orderBy('position')
//            ->all();
//
//        $news = (new \yii\db\Query())
//            ->select('name,name_en,photo,slug,anons,anons_en,date')
//            ->from('{{%news}}')
//            ->where(['public' => "1"])
//            ->orderBy('date DESC')
//            ->all();
//
//        foreach ($doctors_array as $item) {
//            if ($item['staff']) $personal[] = $item;
//            else $doctors[] = $item;
//        }

        return $this->redirect(['/admin/blocks-manage/block-category/index?parent_id=1']);

//        return $this->render('index', [
//            'slider' => $slider,
//            'doctors' => $doctors,
//            'personal' => $personal,
//            'news' => $news,
//        ]);
    }
}
