<?php

namespace common\helpers;

class JwtValidationData extends \sizeg\jwt\JwtValidationData
{
 
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->validationData->setIssuer('https://pelatihan.co.id');
        $this->validationData->setAudience('https://pelatihan.co.id');
        $this->validationData->setId('a6df09asd7f89');

        parent::init();
    }
}    