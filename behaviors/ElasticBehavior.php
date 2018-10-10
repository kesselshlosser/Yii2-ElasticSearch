<?php

namespace common\modules\elasticSearch\behaviors;
use Yii;
use \yii\base\Behavior;
use common\modules\core\models\Setting;
class ElasticBehavior extends Behavior
{

    public $host;
    public $index;
    public $prefix;

    function __construct()
    {
        $settings = Setting::find()->one();
        $this->host = $settings->elasticSearchHost;
        $this->prefix = $settings->elasticSearchPrefix;
        $this->index = "product";
    }

    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_AFTER_INSERT => 'indexDocument',
            \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => 'updateDocument',
            \yii\db\ActiveRecord::EVENT_AFTER_DELETE => 'deleteDocument'
        ];
    }

    public function indexDocument($event)
    {
        switch($this->owner->className()) {
            case "common\modules\product\models\Product":$this->index = "product";break;
            case "common\modules\product\models\Category":$this->index = "category";break;
            default:$this->index = "product";break;
        }
        $fields = "{ ";
        foreach($this->owner->attributes as $key => $value) {
            $fields = $fields."\"".$key."\" : \"".$value."\",";
        }
        $fields = $fields."\"table\" : \"".$this->index."\",".
        "\"suggest\":{\"input\": \"".$this->owner->attributes['name']."\"}}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host."/".$this->prefix.'_'.$this->index."/doc/".$this->owner->attributes["id"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }

    public function updateDocument()
    {
        switch($this->owner->className()) {
            case "common\modules\product\models\Product":$this->index = "product";break;
            case "common\modules\product\models\Category":$this->index = "category";break;
            default:$this->index = "product";break;
        }
        $fields = "{ \"doc\":{ ";
       
        foreach($this->owner->attributes as $key => $value) {
            $fields = $fields."\"".$key."\" : \"".$value."\",";
        }
        $fields = $fields."\"table\" : \"".$this->index."\",".
        "\"suggest\":{\"input\": \"".$this->owner->attributes['name']."\"}}}";
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host."/".$this->prefix.'_'.$this->index."/doc/".$this->owner->attributes["id"]."/_update");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }

    public function deleteDocument()
    {
         switch($this->owner->className()) {
            case "common\modules\product\models\Product":$this->index = "product";break;
            case "common\modules\product\models\Category":$this->index = "category";break;
            default:$this->index = "product";break;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host."/".$this->prefix.'_'.$this->index."/doc/".$this->owner->attributes["id"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getIndex()
    {
        return $this->index;
    }
}