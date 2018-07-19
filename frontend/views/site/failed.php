<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Payment Failure';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="bgwhite p-t-66 p-b-66">
	<div class="container">
		<div class="row">
			<div class="p-t-40 p-b-40 t-center" style="width: 100%;">
				<img src="<?php echo Url::to('@web/images/icons/failure.svg'); ?>" class="" alt="Payment Failure" style="width: 60px;height: auto;">
				<h2 class="l-text2 t-center p-t-40 color2" style="font-size: 30px;">Payment Failure</h2>
				<p>Sorry. We were unable to process your payment. Please contact our customer service.</p>
				<div class="t-center size14 m-t-30" style="margin: 30px auto 0 auto;">
					<a class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4" href="<?php echo Url::to('/site/') ;?>">Go Back Home</a>
				</div>
			</div>
		</div>
	</div>
</section>