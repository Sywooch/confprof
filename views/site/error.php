<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message) . "\n" . Html::encode($this->title)) ?>
    </div>

    <p>
        Данная ошибка возникла в процессе обработки сервером Вашего запроса.
    </p>
    <p>
        Мы уже пытаемся исправить ситуацию.
    </p>

</div>
