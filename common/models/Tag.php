<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 * @property int $frequency
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'frequency' => 'Frequency',
        ];
    }

    //数组转字符串
    public static function array2string($tags)
    {
        return implode(',',$tags);
    }

    //字符串转数组
    public static function string2array($tags)
    {
        return preg_split("/\s*,\s*/",trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    //标签次数添加
    public static function addTags($tags)
    {
        if(empty($tags)) return;
        foreach($tags as $name)
        {
            $tag = self::findOne(['name'=>$name]);

            if($tag)
            {
                $tag->frequency += 1;
            }
            else
            {
                $tag = new self();
                $tag->name = $name;
                $tag->frequency = 1;
            }
            $tag->save();
        }
    }

    //标签次数减少
    public static function removeTags($tags)
    {
        if(empty($tags)) return;
        foreach($tags as $name)
        {
            $tag = self::findOne(['name'=>$name]);

            if($tag && $tag->frequency<=1)
            {
                $tag->delete();
            }
            else
            {
                $tag->frequency -=1;
                $tag->save();
            }

        }
    }

    //处理标签次数
    public static function frequency($oldTags,$newTags)
    {
        if(!empty($oldTags) || !empty($newTags))
        {
            $oldTags = self::string2array($oldTags);
            $newTags = self::string2array($newTags);
            self::addTags(array_diff($newTags,$oldTags));
            self::removeTags(array_diff($oldTags,$newTags));
        }
    }

    //前台标签云
    public static function findTagWidget($limit = 20)
    {
        $tag_size_level = 5;
        $models = self::find()->orderBy('frequency desc')->limit($limit)->all();
        $totalCount = self::find()->limit($limit)->count();
        $stepper = ceil($totalCount/$tag_size_level);
        $tags = [];
        $counter = 1;
        if($totalCount > 0)
        {
            foreach($models as $model)
            {
                $tags[$model->name] = ceil($counter/$stepper)+1;
                $counter++;
            }
            ksort($tags);
        }
        return $tags;
    }
}
