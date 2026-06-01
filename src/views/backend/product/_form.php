<?php


/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Shop\entities\product\Characteristic;
use Besnovatyj\Shop\entities\product\Product;
use Besnovatyj\Shop\entities\Brand;
use Besnovatyj\Shop\forms\backend\product\ProductCreateForm;
use Besnovatyj\Shop\forms\backend\product\ProductEditForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ProductCreateForm|ProductEditForm */
/* @var $product Product|null */
/* @var $characteristics Characteristic[] */
$characteristics = $characteristics ?? [];
?>

<?php $form = ActiveForm::begin(); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Основные данные -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header d-md-flex justify-content-md-between">
                    <div class="pt-1">Основное</div>
                    <a class="btn btn-sm collapse-button" data-bs-toggle="collapse" href="#collapse-main" role="button"
                       aria-expanded="true" aria-controls="collapse-main">
                        <i class="bi bi-plus-lg"></i>
                        <i class="bi bi-dash-lg"></i>
                    </a>
                </div>
                <div class="collapse show" id="collapse-main">
                    <div class="card-body">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'brandId')->dropDownList(
                            ArrayHelper::map(Brand::find()->orderBy('name')->all(), 'id', 'name'),
                            ['prompt' => 'Без бренда']
                        ) ?>
                        <?= $form->field($model, 'weight')->textInput(['type' => 'number', 'min' => 0]) ?>
                        <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <!-- Категории -->
            <div class="card">
                <div class="card-header d-md-flex justify-content-md-between">
                    <div class="pt-1">Категории</div>
                    <a class="btn btn-sm collapse-button" data-bs-toggle="collapse" href="#collapse-cats" role="button"
                       aria-expanded="true" aria-controls="collapse-cats">
                        <i class="bi bi-plus-lg"></i>
                        <i class="bi bi-dash-lg"></i>
                    </a>
                </div>
                <div class="collapse show" id="collapse-cats">
                    <div class="card-body">
                        <?= $form->field($model->categories, 'main')->dropDownList(
                            $model->categories->categoriesList(),
                            ['prompt' => 'Не выбрано']
                        )->label('Основная категория') ?>
                        <?= $form->field($model->categories, 'additional')->checkboxList($model->categories->categoriesList())->label('Дополнительные категории') ?>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>

            <!-- Теги -->
            <div class="card">
                <div class="card-header"><div class="pt-1">Теги</div></div>
                <div class="card-body">
                    <?= $form->field($model->tags, 'tagsString')->textInput()->label('Теги (через запятую)') ?>
                </div>
                <div class="card-footer">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <!-- SEO -->
            <div class="card">
                <div class="card-header d-md-flex justify-content-md-between">
                    <div class="pt-1">SEO</div>
                    <a class="btn btn-sm collapse-button" data-bs-toggle="collapse" href="#collapse-seo" role="button"
                       aria-expanded="false" aria-controls="collapse-seo">
                        <i class="bi bi-plus-lg"></i>
                        <i class="bi bi-dash-lg"></i>
                    </a>
                </div>
                <div class="collapse" id="collapse-seo">
                    <div class="card-body">
                        <?= $form->field($model->meta, 'title')->textInput() ?>
                        <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
                        <?= $form->field($model->meta, 'keywords')->textInput() ?>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($characteristics)): ?>
        <!-- Характеристики -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-md-flex justify-content-md-between">
                        <div class="pt-1">Характеристики</div>
                        <a class="btn btn-sm collapse-button" data-bs-toggle="collapse" href="#collapse-characteristics" role="button"
                           aria-expanded="true" aria-controls="collapse-characteristics">
                            <i class="bi bi-plus-lg"></i>
                            <i class="bi bi-dash-lg"></i>
                        </a>
                    </div>
                    <div class="collapse show" id="collapse-characteristics">
                        <div class="card-body">
                            <div class="row">
                                <?php $formName = $model->formName(); ?>
                                <?php foreach ($characteristics as $characteristic): ?>
                                    <?php
                                    $fieldId      = strtolower($formName) . '-values-' . $characteristic->id;
                                    $fieldName    = $formName . '[values][' . $characteristic->id . ']';
                                    $value        = $model->values[$characteristic->id] ?? ($characteristic->default ?? '');
                                    $label        = Html::encode($characteristic->name)
                                        . ($characteristic->required ? ' <span class="text-danger">*</span>' : '');
                                    $inputOptions = [
                                        'id'    => $fieldId,
                                        'class' => 'form-control',
                                    ];
                                    ?>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="form-label" for="<?= $fieldId ?>"><?= $label ?></label>
                                        <?php if ($characteristic->isSelect()): ?>
                                            <?= Html::dropDownList(
                                                $fieldName,
                                                $value,
                                                array_combine($characteristic->variants, $characteristic->variants),
                                                array_merge($inputOptions, ['prompt' => '— не задано —'])
                                            ) ?>
                                        <?php elseif ($characteristic->isInteger()): ?>
                                            <?= Html::input('number', $fieldName, $value, array_merge($inputOptions, ['step' => 1])) ?>
                                        <?php elseif ($characteristic->isFloat()): ?>
                                            <?= Html::input('number', $fieldName, $value, array_merge($inputOptions, ['step' => 'any'])) ?>
                                        <?php else: ?>
                                            <?= Html::textInput($fieldName, $value, $inputOptions) ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($model instanceof ProductCreateForm): ?>
        <!-- Цена и остаток — только при создании -->
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header"><div class="pt-1">Цена</div></div>
                    <div class="card-body">
                        <?= $form->field($model->price, 'new')->textInput(['type' => 'number', 'min' => 0]) ?>
                        <?= $form->field($model->price, 'old')->textInput(['type' => 'number', 'min' => 0]) ?>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header"><div class="pt-1">Количество</div></div>
                    <div class="card-body">
                        <?= $form->field($model->quantity, 'quantity')->textInput(['type' => 'number', 'min' => 0]) ?>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>
