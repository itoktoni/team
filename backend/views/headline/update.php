<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\Headline */

$this->title = 'Update Headline: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Headlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
            <div class="card">
            	<div class="card-header card-header-text" data-background-color="purple">
				    <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
				</div>
    <?= $this->render('_form', [ 
        'model' => $model,
    ]) ?>

 </div>
        </div>
	</div>
</div>
