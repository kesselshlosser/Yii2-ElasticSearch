<?php

use yii\helpers\Html;
use common\modules\product\models\Product;
use common\modules\product\models\Category;
use common\modules\elasticSearch\widgets\ProductWidget;
use common\modules\elasticSearch\widgets\AdminProductWidget;
use yii\helpers\Url;
use common\modules\core\helpers\ListesHelper;

$this->title = "Résultats";
$this->params['breadcrumbs'][] = $this->title;
$productModel = new Product();
?>
<?php   if(isset($categories)) { ?>
    <div class="panel panel-flat">
        <div class="panel-heading"><h6 class="panel-title">Catégories <span class="text-muted"><?= "(".count($categories).")"  ?></span></h6></div>  
        <div class="panel-body">
            <div class="table-responsive">
                <!-- TODO tranform to GRID -->
                <?php 
                $categoryModel = new Category();
                \common\assets\footable\FootableAsset::register($this);
                $this->registerJs("jQuery('#cart-table').footable().trigger('footable_expand_all');");
                ?>
                <table id="cart-table" class="table table-striped invoice-summary toggle-arrow-tiny" data-show-toggle="false"  data-sorting="false">
                    <tbody>
                        <?php
                        if(!empty($categories)) {
                                foreach ($categories as $model) {
                                    $model = $model->_source;
                                ?>
                                <tr>
                                    <td>
                                        <?=Html::a(Html::img($categoryModel->getThumbUploadUrl('image'), ['class' => 'product-img']))?>
                                    </td>
                                    <td> 
                                        <?= Html::a(Html::encode($model->name), ['/elasticSearch/elastic-search/products-by-category', 'id'=>$model->id])?>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                        <span class="text-bold">
                                        <?php
                                            if ($model->status == Category::STATUS_ACTIVE) {
                                                echo Html::tag('span', ListesHelper::getCategoryStatus()[$model->status], ['class' => 'label label-table label-success']);
                                            } else {
                                                echo Html::tag('span', ListesHelper::getCategoryStatus()[$model->status], ['class' => 'label label-table label-danger']);
                                            }
                                        ?>
                                        </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pull-right" style="padding-right: 20px !important">
                                        <?php 
                                        echo \common\modules\core\widgets\SwalButton::widget([
                                                'content' => '<i class="glyphicon glyphicon-trash"></i>',
                                                'options' => ['class'=>'btn btn-danger'],
                                                'text' => Yii::t('app', 'Êtes vous sur de vouloir supprimer cette catégorie?'),
                                                'ajax' => true,
                                                'ajaxUrl' => Url::to(['/product/admin-category/delete', 'id' => $model->id]),
                                            ]);

                                        echo Html::a('<i class="glyphicon glyphicon-pencil"></i>', 
                                            Url::to(['/product/admin-category/update', 'id' => $model->id]), 
                                            [
                                                'class' => 'btn btn-md btn-warning'
                                            ]);
                                        ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        }else{
                            echo("No Categories found");
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<div class="panel panel-flat">
    <div class="panel-heading"><h6 class="panel-title">Produits <span class="text-muted"><?= "(".count($products).")"  ?></span></h6></div>
    <div class="panel-body">
            <?php
                if(!empty($products)) {
                    $i = 0;
                    foreach ($products as $product) :
                        echo($i%4==0?'<div class="row">':'');
                            $product = $product->_source;
                            if($scope == "client") {
                                echo ProductWidget::widget(["product"=>$product]);
                            }else{
                                echo AdminProductWidget::widget(["product"=>$product]);
                            }
                            
                        echo($i%4==3?'</div>':'');
                        $i++;
                    endforeach;
                    echo($i%4==0?'':'</div>');
                }else{
                    echo("No products found");
                }
            ?>

	</div>
</div>

