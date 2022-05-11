<?php
namespace common\helpers;

use Yii;
use common\models\Setting;
use common\models\Peserta;
use common\models\Vendor;
use common\models\Aktifitas;

/**
 * Description of Poin
 *
 * @author maf
 */
class Poin {

    public static function simpan($jenisuser, $userid, $slugaktifitas, $keterangan)
    {
        $setting = Setting::find()->where(['key' => $slugaktifitas])->one();
        $aktifitas = new Aktifitas();
        if ($jenisuser == 'peserta') {
            $peserta = Peserta::findOne($userid);
            $aktifitas->peserta_id = $userid;
            $aktifitas->aktifitas = $keterangan;
            $aktifitas->poin = $setting->nilai;
            if ($aktifitas->save()) {
                $peserta->poin = $peserta->poin + $setting->nilai;
                $peserta->save();
            }
        } else if ($jenisuser == 'vendor') {
            $vendor = Vendor::findOne($userid);
            $aktifitas->vendor_id = $userid;
            $aktifitas->aktifitas = $keterangan;
            $aktifitas->poin = $setting->nilai;
            if ($aktifitas->save()) {
                $vendor->poin = $vendor->poin + $setting->nilai;
                $vendor->save();
            }
        }
    }
}