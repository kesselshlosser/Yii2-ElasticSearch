<?php
namespace common\modules\elasticSearch\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\product\models\Product;

class ProductWidget extends Widget
{

    public $product = null;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $product = $this->product;
        $model = new Product();
        $img_url = ($product->main_image != NULL)?Yii::getAlias('@web')."/../uploads/product/" .$product->id . '/thumb-'. $product->main_image:
        Yii::getAlias('@web')."/../img/placeholder.png";
    ?>
        <div class="col-md-3 col-sm-6">
            <div class="ibox">
                <div class="ibox-content product-box">
                    <a  href="<?= Url::to(['/product/product/view','id'=>$product->id]) ?>">
                         <?=
                    '<div class="mar-btm" style="background:url(\'' . $img_url . '\') no-repeat center; background-size:cover;height:200px;">    
                                                        </div>'
                      ?>
                    </a>
                    <div class="product-desc">
                        <span class="product-price">
                            <?php if ($product->type != Product::TYPE_GROUPED) :?>
                                <?= Yii::$app->formatter->asCurrency($product->default_price, Yii::$app->settings->currency)?>
                            <?php else :?>
                                <span class="price-range"><?=Yii::$app->formatter->asCurrency($product->minPrice, Yii::$app->settings->currency) . ' - ' . Yii::$app->formatter->asCurrency($product->maxPrice, Yii::$app->settings->currency)?></span>
                            <?php endif; ?>
                        </span>
                      
                        <div class="small m-t-xs">
                            <?php if(!empty($product->code)): ?>
                            <div class="code">
                                <span class="text-semibold"><?= $model->attributeLabels()['code']?></span> : <span class="text-bold"><?= Html::encode($product->code)?></span>
                            </div>
                            <?php endif ?>
                            <?= Html::a($product->name, ['/product/product/view', 'id'=>$product->id], ['class' => 'product-name']) ?>
                            <?php if(!empty($product->reference)): ?>
                            <div class="ref">
                                <span class="text-semibold"><?= $model->attributeLabels()['reference']?></span> : <span class="text-bold"><?= Html::encode($product->reference)?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="m-t text-right">
                            <?= Html::a(Yii::t('app','Détails   ').' <i class="fa fa-long-arrow-right"></i>', ['/product/product/view', 'id'=>$product->id], ['class' => 'btn btn-xs btn-outline btn-primary']) ?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}