<?php
use yii\helpers\Url;
$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="sticky-col sticky-right">
    <a href="<?= Url::to(['contact/index']) ?>">
        <img src="<?= $baseUrl ?>/images/floating-button-inquiry.png">
    </a>
    <a href="javascript:$zopim.livechat.window.show();">
        <img src="<?= $baseUrl ?>/images/floating-button-chat.png">
    </a>
    <a href="https://api.whatsapp.com/send?phone=6282210008181&text=Halo%20Vista%20Education,%20mau%20tanya%20dong">
        <img src="<?= $baseUrl ?>/images/floating-button-whatsapp.png">
    </a>
</div>