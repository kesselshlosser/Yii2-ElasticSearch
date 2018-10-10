<?php

use yii\helpers\Html;
use common\modules\product\models\Product;


$this->title = "Résultats";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-flat">
    <div class="panel-heading"><h6 class="panel-title">Produits</h6></div>
    <div class="panel-body">
    	<div class='row'>
            <?php
                if(!empty($products)) {
                $i = 0;
                foreach ($products as $product) :
                ?>

                    <?= ($i%4==0?'<div class="row">':'') ?>
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
                    <?=($i%4==3?'</div>':'')?>
                    <?php $i++;?>

                <?php
                endforeach;?>
                <?=($i%4==0?'':'</div>')?>
                <?php 
            }else{
                echo("No results found");
            }
            ?>

        </div>
	</div>
</div>