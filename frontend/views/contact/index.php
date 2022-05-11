<?php
use common\helpers\Lang;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = Lang::text('contact-header-title');
?>

<div class="sub_header header-background">
    <div id="intro_txt" class="animated fadeInDown">
        <h1><strong><?= Lang::text('contact-header-title') ?></strong></h1>
        <h4><?= Lang::text('contact-header-description') ?></h4>
    </div>
</div>

<!-- <div style="height: 500px; background: url(<?= $baseUrl ?>/images/contact-header.jpg); background-size: cover;">
    <div class="header-blue-opacity">
        <h1 style="margin-top: 0px; color: white;"><strong><?= Lang::text('contact-header-title') ?></strong></h1>
        <h4 style="color: white;"><?= Lang::text('contact-header-description') ?></h4>
    </div>
</div> -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>
                <h2><?= Lang::text('contact-header-title') ?></h2>
                <p><?= Lang::text('contact-header-description') ?></p>
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="form-group">
                            <label><?= Lang::text('contact-name-label') ?></label>
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Lang::text('contact-name-label', 'Name')])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="form-group">
                            <label><?= Lang::text('contact-email-label') ?></label>
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Lang::text('contact-email-label', 'Email')])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="form-group">
                            <label><?= Lang::text('contact-message-label') ?></label>
                            <?= $form->field($model, 'message')->textArea(['rows' => '5', 'placeholder' => Lang::text('contact-message-label', 'Message'), 'style' => 'resize: none;'])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input type="submit" value="<?= Lang::text('contact-button-submit') ?>" class="button red-button add_bottom_30" id="submit-contact">
                    </div>
                </div>
            <?php ActiveForm::end(); ?> 
        </div>
        <!-- <div class="col-md-2"></div> -->
        <div class="col-md-6">
            <!-- <div class="office-header">
                <div class="container">
                    <h2 class="text-white"><?= Lang::text('contact-header-title') ?></h2>
                </div>
            </div> -->
            <h2><?= Lang::text('contact-hour-title') ?></h2>
            <p class="sub-heading">
                9.00am - 6.00pm (Monday-Friday)<br>
                10.00 - 5.00pm (Saturday)<br>
                Closed on Public Holidays<br>
            </p>
            <table>
                <tr>
                    <td style="padding: 3px; padding-left: 0px;">Admission Info</td>
                    <td style="padding: 3px;">:</td>
                    <td style="padding: 3px;">
                        <a href="mailto:admission@ptpus.co.id"><img src="<?= $baseUrl ?>/images/icon-envelope.jpg" width="15px"> admission@ptpus.co.id</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 3px; padding-left: 0px;">Marketing Info</td>
                    <td style="padding: 3px;">:</td>
                    <td style="padding: 3px;">
                        <a href="mailto:marketing@ptpus.co.id"><img src="<?= $baseUrl ?>/images/icon-envelope.jpg" width="15px"> marketing@ptpus.co.id</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 3px; padding-left: 0px;">General Info</td>
                    <td style="padding: 3px;">:</td>
                    <td style="padding: 3px;">
                        <a href="mailto:info@ptpus.co.id"><img src="<?= $baseUrl ?>/images/icon-envelope.jpg" width="15px"> info@ptpus.co.id</a>
                    </td>
                </tr>
            </table>
            <!-- <p class="sub-heading">
            </p> -->

            <div class="spacer-30"></div>
            <h4 class="red-heading">Bali Study Center</h4>
            <p class="sub-heading">
                <a href="tel:03618466303"><img src="<?= $baseUrl ?>/images/icon-phone.jpg" width="15px"> <b>(0361) 8466303</b></a><br>
                <a href="http://www.google.com/maps/place/-8.674194,115.189731"><img src="<?= $baseUrl ?>/images/icon-pin.jpg" width="15px"> <b>Jl. Mahendradata No. 45, Denpasar, Bali, 80119</b></a><br>
                <a href="https://api.whatsapp.com/send/?phone=628118009998&text=Hello">
                    <img src="<?= $baseUrl ?>/images/icon-whatsapp.jpg" width="15px"> <b> 0811-8009-998</b>
                </a>
            </p>
            <!--
            <?php foreach ($centers as $center): ?>
                <h4 class="red-heading"><?= $center->name ?></h4>
                <p class="sub-heading">
                    <a href="tel:<?= $center->phone ?>"><i class="icon-phone"></i> <?= $center->phone ?></a><br>
                    <a href="http://www.google.com/maps/place/<?= $center->latitude ?>,<?= $center->longitude ?>"><i class="icon-location"></i>  <?= $center->address ?></a>
                </p>
            <?php endforeach; ?>
            -->
        </div>
    </div>

</div>
<div class="spacer-50"></div>

<style type="text/css">
	.red-heading {
		color: #e43234;
		font-weight: 600;
	}
	.header-background {
		/*background: linear-gradient(0deg, rgba(38, 103, 169, 0.6), rgba(38, 103, 169, 0.6)), url(<?= $baseUrl ?>/images/contact-header.jpg);*/
        background: url(<?= \common\helpers\Header::getUrl('contact-header') ?>);
		background-size:cover;
	}
	.office-header {
		background: url(<?= $baseUrl ?>/images/contact-office.jpg);
		background-size:cover;
	}
</style>

<script type="text/javascript">
    $('.testimony-slider').slick({
        autoplay: true,
        arrows: false,
        autoplaySpeed: 3000
    });
</script>