<?php 
namespace common\modules\elasticSearch\widgets;

use yii\base\Widget;
use common\assets\elasticsearch\ElasticSearchAsset;

class ElasticSearchWidget extends Widget
{
    public $message;
    public $scope = "admin";
    public $link = "/b2b-panel/index.php?r=elasticSearch%2Felastic-search%2Fadmin-index";
    public function init()
    {
        parent::init();
        $view = $this->getView();
        ElasticSearchAsset::register($view);
        if ($this->message === null) {
            $this->message = 'Recherche';
        }
        if($this->scope == "client") {
            $this->link = "index.php?r=elasticSearch%2Felastic-search%2Findex";
        }
    }

    public function run()
    {
        echo "  <div class=\"custom-search-form\" id=\"elastic-search-form\">
                    <form autocomplete=\"off\" action=\"".$this->link."\" method=\"post\">
                        <div class=\"input-group mar-btm\">
                                <input id=\"search-input-id\" name=\"id\" hidden>
                                <input id=\"search-input-type\" name=\"type\" hidden>  
                                <input id=\"search-input-text\" name=\"req\" type=\"text\" class=\"form-control\" placeholder=\"".$this->message."\">
                                    <span class=\"input-group-btn\">
                                    <button class=\"btn btn-mint\" type=\"submit\"><i class=\"fa fa-search\" aria-hidden=\"true\"></i></button>
                                </span>
                        </div>
                    </form>
                    <div id=\"search-results\">
                    </div>  
                </div>
        ";
    }
}
