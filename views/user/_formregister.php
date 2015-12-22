<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

use app\models\User;

$this->title = 'Регистрация пользователя';
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$sCss = <<<EOT
div.required label:after {
    content: " *";
/*    color: red;*/
}
EOT;

$this->registerCss($sCss);

$sJs = <<<EOT
jQuery('.selectgrouplink').on(
	"click",
	function(event){
		var oLink = jQuery(this);
		event.preventDefault();
		jQuery("#user-us_group").val(oLink.data("group"));
		jQuery(".user-formregister-select").fadeOut(function() {jQuery(".user-formregister").fadeIn();});

		return false;
	});
EOT;

$this->registerJs($sJs, View::POS_READY);

?>

<div class="registration user-formregister-select">
	<?php
	foreach(User::getRegGroups() as $k=>$v) {
		echo '<a href="#" title="" data-group="'.$k.'" class="selectgrouplink">'.$v.'</a> <br />';
	}

	?>

</div>

<div class="registration user-formregister" style="display: none;">

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => [
            'class' => 'form-horizontal well'
        ],
        'fieldConfig' => [
//            'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-offset-6 col-md-6\">{error}</div>",
//            'template' => "{input}\n{error}",
            'template' => '<div class="control-label">{label}</div><div class="controls">{input}{error}</div>',
            'options' => ['class' => 'control-group'],
//            'labelOptions'=>['class'=>'control-label col-md-6'],
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'validateOnType' => false,
        'validateOnSubmit' => true,
    ]); ?>
    <fieldset>
    <legend>Регистрация пользователя</legend>

    <?= $form->field($model, 'us_group', ['template' => "{input}\n{error}"])->hiddenInput() ?>

    <?= $form->field($model, 'us_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <div class="controls">
        <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary validate']) ?>
    </div>
    <fieldset>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
                                        <div class="registration">

	<form id="member-registration" action="/dobavlenie-uchastnika-konferentsii.html?task=registration.register" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
<fieldset>
<legend>Регистрация пользователя</legend>
<div class="control-group">

    <div class="control-label">
        <span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strong class="red">*</strong> Обязательное поле</label></span><span class="after"></span></span>														</div>
        <div class="controls"></div>
    </div>

    <div class="control-group">
        <div class="control-label">
		    <label id="jform_name-lbl" for="jform_name" class="hasTooltip required" title="&lt;strong&gt;Имя&lt;/strong&gt;&lt;br /&gt;Введите ваше полное имя">Имя<span class="star">&#160;*</span></label>
        </div>
        <div class="controls">
            <input type="text" name="jform[name]" id="jform_name" value="" class="required" size="30" required aria-required="true" />
        </div>
    </div>

																									<div class="control-group">
							<div class="control-label">
							<label id="jform_username-lbl" for="jform_username" class="hasTooltip required" title="&lt;strong&gt;Логин&lt;/strong&gt;&lt;br /&gt;Введите желаемый логин.">
	Логин<span class="star">&#160;*</span></label>														</div>
							<div class="controls">
								<input type="text" name="jform[username]" id="jform_username" value="" class="validate-username required" size="30" required aria-required="true" />							</div>
						</div>
																									<div class="control-group">
							<div class="control-label">
							<label id="jform_password1-lbl" for="jform_password1" class="hasTooltip required" title="&lt;strong&gt;Пароль&lt;/strong&gt;&lt;br /&gt;Введите пароль.">
	Пароль<span class="star">&#160;*</span></label>														</div>
							<div class="controls">
								<input type="password" name="jform[password1]" id="jform_password1" value="" autocomplete="off" class="validate-password required" size="30" maxlength="99" required aria-required="true" />							</div>
						</div>
																									<div class="control-group">
							<div class="control-label">
							<label id="jform_password2-lbl" for="jform_password2" class="hasTooltip required" title="&lt;strong&gt;Повтор пароля&lt;/strong&gt;&lt;br /&gt;Подтверждение пароля">
	Повтор пароля<span class="star">&#160;*</span></label>														</div>
							<div class="controls">
								<input type="password" name="jform[password2]" id="jform_password2" value="" autocomplete="off" class="validate-password required" size="30" maxlength="99" required aria-required="true" />							</div>
						</div>
																									<div class="control-group">
							<div class="control-label">
							<label id="jform_email1-lbl" for="jform_email1" class="hasTooltip required" title="&lt;strong&gt;Адрес электронной почты&lt;/strong&gt;&lt;br /&gt;Введите адрес электронной почты">
	Адрес электронной почты<span class="star">&#160;*</span></label>														</div>
							<div class="controls">
								<input type="email" name="jform[email1]" class="validate-email required" id="jform_email1" value="" size="30" required aria-required="true" />							</div>
						</div>
																									<div class="control-group">
							<div class="control-label">
							<label id="jform_email2-lbl" for="jform_email2" class="hasTooltip required" title="&lt;strong&gt;Подтверждение адреса электронной почты&lt;/strong&gt;&lt;br /&gt;Подтвердите указанный вами адрес электронной почты">
	Подтверждение адреса электронной почты:<span class="star">&#160;*</span></label>														</div>
							<div class="controls">
								<input type="email" name="jform[email2]" class="validate-email required" id="jform_email2" value="" size="30" required aria-required="true" />							</div>
						</div>
																																						</fieldset>
							<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate">Регистрация</button>
				<a class="btn" href="/" title="Отмена">Отмена</a>
				<input type="hidden" name="jform[groups][]" value="59" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
			</div>
		</div>
		<input type="hidden" name="d2ebb610356b2d2c96292f2a3f381073" value="1" />	</form>
</div>
*/
