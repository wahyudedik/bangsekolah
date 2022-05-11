<?php
namespace common\helpers;

use Yii;
use common\models\Setting;
use common\models\Peserta;
use common\models\Vendor;
use common\models\Notifikasi;

/**
 * Description of Notif
 *
 * @author maf
 */
class Notif {

    public static function simpan($jenisuser, $userid, $judul, $keterangan, $url)
    {
        $notifikasi = new Notifikasi();
        if ($jenisuser == 'peserta') {
            $notifikasi->peserta_id = $userid;
        } else if ($jenisuser == 'vendor') {
            $notifikasi->vendor_id = $userid;
        }
        $notifikasi->judul = $judul;
        $notifikasi->keterangan = $keterangan;
        $notifikasi->url = $url;
        $notifikasi->status_baca = 0;
        return $notifikasi->save();
    }
}