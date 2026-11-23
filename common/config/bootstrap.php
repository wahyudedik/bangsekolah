<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/backend/web/uploads');
Yii::setAlias('@posts', dirname(dirname(__DIR__)) . '/frontend/web/uploads');
Yii::setAlias('@backurl', 'http://localhost/food-factory/backend/web');
// Yii::setAlias('@fronturl', 'http://localhost/banksekolah');

// Yii::setAlias('@fronturl', 'http://156.67.218.246/');

if ($_SERVER['SERVER_NAME'] == 'banksekolah.test') {
	Yii::setAlias('@fronturl', 'http://banksekolah.test');
	Yii::setAlias('@backurl', 'http://banksekolah.test/backend/web');
} else if ($_SERVER['SERVER_NAME'] == '192.168.0.4') {
	Yii::setAlias('@fronturl', 'http://192.168.0.4/banksekolah');
	Yii::setAlias('@backurl', 'http://192.168.0.4/banksekolah/backend/web');
} else {
	Yii::setAlias('@fronturl', 'https://banksekolah.com');
	Yii::setAlias('@backurl', 'https://banksekolah.com/backend/web');
}
