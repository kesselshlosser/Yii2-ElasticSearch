<?php 
namespace common\modules\elasticSearch\widgets;

use Yii;
use yii\base\Widget;
use common\assets\elasticsearch\ElasticSearchAsset;

class SearchResultsWidget extends Widget
{
    public $message;
    public $results;
    public function init()
    {
        parent::init();
        $view = $this->getView();
        ElasticSearchAsset::register($view);
    }

    public function run()
    {
        return "<div class='row'>
            <?php
            $i = 0;
            foreach ($products as $product) :?>

                <?= ($i%4==0?'<div class='row'>':'') ?>
                <div class='product-item col-md-3 col-sm-12 col-xs-12'>
                    <div class='panel'>
                        <div class='panel-body text-center'>
                            <div class='title'>
                                <h4><?= Html::a(Html::encode($product->name), ['/product/admin-product/view', 'id'=>$product->id])?></h4>
                            </div>
                            <img alt='Profile Picture' class='mar-btm' src='<?= $product->getThumbUploadUrl('main_image') ?>'>
                            <p class='text-lg text-semibold mar-no text-main'><?= $product->attributeLabels()['reference']?></span> : <span class='text-bold'><?= Html::encode($product->reference)?></p>
                            <p class='text-muted'><?=Yii::t('app', 'Quantité')?></span> : <span><?= $product->quantity?></p>
                            <div class='mar-top'>
                                <?php
                                    switch ($product->availability) {
                                        case Product::OUT_STOCK:
                                            echo('<span class='label label-table label-danger'>Non disponible</span>');
                                            break;
                                        case Product::AVAILABLE_SOON:
                                            $date = $product->availabilityDate;
                                            echo('<span class='label label-table label-warning'>' . ($date ? 'Disponible à partir du ' . Yii::$app->formatter->asDate($date) : 'Disponible prochainement') . '</span>');
                                            break;
                                        case Product::IN_STOCK:
                                            echo('<span class='label label-table label-success'>Disponible</span>');
                                            break;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?=($i%4==3?'</div>':'')?>
                <?php $i++; ?>
            <?php
            endforeach;?>
            <?=($i%4==0?'':'</div>')?>
        </div>";
    }
}
