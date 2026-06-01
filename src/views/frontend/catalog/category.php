<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\category\Category;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

/* @var $this View */
/* @var $category Category */
/* @var $dataProvider ActiveDataProvider */

$this->title = $category->name;
?>

<h1><?= $this->title ?></h1>

<?php if ($category->description): ?>
    <div class="category-description"><?= $category->description ?></div>
<?php endif; ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'     => '_product_card',
    'layout'       => "{items}\n{pager}",
    'emptyText'    => 'Товары не найдены.',
]) ?>
