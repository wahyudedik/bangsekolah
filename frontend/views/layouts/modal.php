<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Subscriber;

$baseUrl = Yii::$app->request->baseUrl;
$model = new Subscriber();
?>

<div id="search" class="close">
    <button type="button" class="close" id="search-close"><i class="icon_close"></i></button>
    <form action="<?= Url::to(['post/search']) ?>" method="GET">
        <input type="search" name="q" placeholder="Type your topic here">
        <button type="submit" class="button button-search">Search</button>
    </form>
</div>

<div class="sticky-social hidden-mobile">
    <a class="btn-sticky" href="https://www.facebook.com/ptpus" target="_blank">
        <img src="<?= $baseUrl ?>/images/icons8-facebook-48.png" width="100%">
    </a>
    <a class="btn-sticky" href="https://instagram.com/pt-pus.com/?igshid=jqh4uawtqowc" target="_blank">
        <img src="<?= $baseUrl ?>/images/icons8-instagram-48.png" width="100%">
    </a>
    <a class="btn-sticky" href="https://www.linkedin.com/company/ptpus" target="_blank">
        <img src="<?= $baseUrl ?>/images/icons8-linkedin-48.png" width="100%">
    </a>
    <a class="btn-sticky" href="https://twitter.com/ptpus" target="_blank">
        <img src="<?= $baseUrl ?>/images/icons8-twitter-48.png" width="100%">
    </a>
</div>


<div class="modal fade" id="modal-subscriber" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog" style="margin-top: 200px;">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(['action' => ['subscribe/join']]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <center>
                            <img src="https://pt-pus.com/images/new-logo.png" width="100px">
                            <h1 style="margin-top: 0px;">PT. PUS</h1>
                            <p>Subscribe to the latest headlines in society, stories, success, and more!</p>
                            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Enter your email', 'style' => 'width: 300px;'])->label(false) ?>
                            <button id="subscribe-close-button" type="button" class="btn btn-default">No thanks</button>
                            <?= Html::submitButton('Join the club', ['class' => 'btn btn-danger']) ?>
                        </center>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style type="text/css">
    .sticky-social {
        top: 150px;
        position: absolute;
    }

    #search {
        background: white;
        /*padding: 20px;*/
    }
    #search .close {
        color: #222 !important;
        right: 0px;
    }
    #search .close i {
        font-size: 50px;
    }
    #search input[type="search"] {
        color: #222;
        border-bottom: 1px solid #aaa;
        font-size: 30px;
        /*width: 50%;*/
        /*text-align: left;*/
        /*position: relative;*/
    }
    #search .button {
        /*position: relative;*/
        /*left: 0%;*/
        /*margin-left: 20px;*/
        background: #333;
        font-size: 20px;
        left:  48%;
    }

    .close {
        position: absolute;
        right: 20px;
        font-size: 30px !important;
        color: #FA6262;
    }
    .fade {
        opacity: 1;
    }
    @media only screen and (min-width: 992px) {
        .modal-content {
            border-radius: 50%;
            width: 400px;
            height: 400px;
            margin-left: 100px;
        }
    }
    @media only screen and (max-width: 991px) {
        .modal-content {
            border-radius: 0px;
            width: 100%;
            height: 400px;
        }
        #subscriber-email {
            width: 100% !important;
        }
    }
</style>

<script type="text/javascript">
    $("#search-close").click(function(){
        $("#search").removeClass('open');
    });

    $("#subscribe-close").click(function(){
        $("#modal-subscriber").modal('hide');
    });

    $("#subscribe-close-button").click(function(){
        $("#modal-subscriber").modal('hide');
    });

    $(document).ready(function(){

        /*
        setTimeout(function() {
            $('#modal-subscriber').modal('show');
        }, 1000);
        */

        setTimeout(function() {
            <?php
            // show modal just when open in first time
            $cookies = Yii::$app->request->cookies;
            if (isset($cookies['modal-subscriber'])) {
                $modalSubscriber = $cookies['modal-subscriber']->value;
                if ($modalSubscriber !== 'true') {
                    echo "$('#modal-subscriber').modal('show');";
                } else {
                    echo "console.log('cookies modal-subscriber: ".$modalSubscriber."');";
                }
            } else {
                echo "$('#modal-subscriber').modal('show');";
                // disable modal after show in first time
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'modal-subscriber',
                    'value' => 'true',
                ]));
            }
            ?>
        }, 5000);
    });
    
</script>