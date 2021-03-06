<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property int $status
 * @property int $create_time
 * @property int $update_time
 * @property int $author_id
 *
 * @property Comment[] $comments
 * @property Adminuser $author
 * @property Poststatus $status0
 */
class Post extends \yii\db\ActiveRecord
{
    private $_oldTags = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Adminuser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Poststatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'tags' => '标签',
            'status' => '状态',
            'create_time' => '添加时间',
            'update_time' => '修改时间',
            'author_id' => '作者',
        ];
    }

    //日期自动写入
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
            {
                $this->create_time = time();
                $this->update_time = time();
            }
            else
            {
                $this->update_time = time();
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    //标签次数增加
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::frequency($this->_oldTags,$this->tags);
    }

    //标签次数减少
    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
        Tag::frequency($this->tags,'');
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->_oldTags = $this->tags;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Adminuser::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Poststatus::className(), ['id' => 'status']);
    }


    public function getUrl()
    {
        return Yii::$app->urlManager->createUrl(['post/detail','id'=>$this->id,'title'=>$this->title]);
    }

    //截取字符串
    public function getSubstring()
    {
        $temStr = strip_tags($this->content);
        $len = mb_strlen($temStr);
        return mb_substr($temStr,0,280,'utf-8').(($len>280)?"...":"");
    }

    //处理标签
    public function getTagLinks()
    {
        $links = [];
        foreach(Tag::string2array($this->tags) as $tag)
        {
            $links[] = Html::a(Html::encode($tag),['post/index','PostSearch[tags]'=>$tag]);
        }
        return $links;
    }

    public function getCommentCount()
    {
        return Comment::find()->where(['status'=>2,'post_id'=>$this->id])->count();
    }

    public function getActiveComments()
    {
        return Comment::findAll(['status'=>2,'post_id'=>$this->id]);
    }
}
