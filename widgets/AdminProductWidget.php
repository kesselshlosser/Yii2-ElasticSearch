<?php 
namespace common\modules\elasticSearch\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use common\assets\elasticsearch\ElasticSearchAsset;
use common\modules\product\models\Product;

class AdminProductWidget extends Widget
{
    public $message;
    public $product;
    public function init()
    {
        parent::init();
        $view = $this->getView();
        ElasticSearchAsset::register($view);
    }

    public function run()
    {
        $product = $this->product;
        $model = new Product();
        $img_url = ($product->main_image != NULL)?Yii::getAlias('@web')."/../uploads/product/" .$product->id . '/thumb-'. $product->main_image:
        Yii::getAlias('@web')."/../img/placeholder.png";
    ?>
        <div class='product-item col-md-3 col-sm-12 col-xs-12'>
            <div class='panel'>
                <div class='panel-body text-center'>
                    <div class='title'>
                        <h4><?= Html::a(Html::encode($product->name), ['/product/admin-product/view', 'id'=>$product->id])?></h4>
                    </div> 
                    <?=
                    '<div class="mar-btm" style="background:url(\'' . $img_url . '\') no-repeat center; background-size:cover;height:200px;">    
                                                        </div>'
                      ?>
                    <p class='text-lg text-semibold mar-no text-main'><?= $model->attributeLabels()['reference']?></span> : <span class='text-bold'><?= Html::encode($product->reference)?></p>
                    <p class='text-muted'><?=Yii::t('app', 'Quantité')?></span> : <span><?= $product->quantity?></p>
                    <div class='mar-top'>
                        <?php
                            switch ($product->stock_status) {
                                case Product::OUT_STOCK:
                                    echo('<span class="label label-table label-danger">Non disponible</span>');
                                    break;
                                case Product::AVAILABLE_SOON:
                                    $date = $product->availabilityDate;
                                    echo('<span class="label label-table label-warning">' . ($date ? 'Disponible à partir du ' . Yii::$app->formatter->asDate($date) : 'Disponible prochainement') . '</span>');
                                    break;
                                case Product::IN_STOCK:
                                    echo('<span class="label label-table label-success">Disponible</span>');
                                    break;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php 
    }
}
