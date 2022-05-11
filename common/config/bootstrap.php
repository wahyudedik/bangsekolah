<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/backend/web/uploads');
Yii::setAlias('@backurl', 'http://localhost/food-factory/backend/web');

// Yii::setAlias('@fronturl', 'http://156.67.218.246/');

if ($_SERVER['SERVER_NAME'] == 'pt-pus.test') {
	Yii::setAlias('@fronturl', 'http://pt-pus.test');
	Yii::setAlias('@backurl', 'http://pt-pus.test/backend/web');
} else if ($_SERVER['SERVER_NAME'] == '192.168.0.13') {
	Yii::setAlias('@fronturl', 'http://192.168.0.13/pt-pus');
	Yii::setAlias('@backurl', 'http://192.168.0.13/pt-pus/backend/web');
}  else if ($_SERVER['SERVER_NAME'] == '192.168.254.147') {
	Yii::setAlias('@fronturl', 'http://192.168.254.147/pt-pus');
	Yii::setAlias('@backurl', 'http://192.168.254.147/pt-pus/backend/web');
} else {
	Yii::setAlias('@fronturl', 'https://pt-pus.com');
	Yii::setAlias('@backurl', 'https://pt-pus.com/backend/web');
}