<?php

use yii\db\Migration;

/**
 * Memastikan semua kolom `id` pada tabel utama punya AUTO_INCREMENT.
 *
 * Latar belakang: dump `banksekolah_banksekolah.sql` mendefinisikan `id` sebagai
 * `int NOT NULL` tanpa AUTO_INCREMENT, dan atribut AUTO_INCREMENT baru ditambahkan
 * oleh blok `ALTER TABLE ... MODIFY` di AKHIR file dump. Bila import terpotong,
 * INSERT dari aplikasi gagal dengan error 1364 "Field 'id' doesn't have a default value".
 *
 * Migration ini idempoten: aman dijalankan berulang.
 */
class m261123_120000_fix_auto_increment extends Migration
{
    private $tables = [
        'activity', 'backup', 'day', 'finger_log', 'holiday', 'invoice', 'lesson',
        'lesson_schedule', 'lesson_schedule_attendance', 'lesson_schedule_template',
        'payment_type', 'post', 'post_category', 'post_image', 'presence', 'role',
        'school', 'school_class', 'school_month', 'school_payment', 'setting',
        'student', 'teacher', 'transaction', 'user', 'user_school', 'year',
    ];

    private $unsignedTables = ['menu', 'role_menu'];

    public function safeUp()
    {
        foreach ($this->tables as $table) {
            $this->fixTable($table, 'int NOT NULL');
        }
        foreach ($this->unsignedTables as $table) {
            $this->fixTable($table, 'int UNSIGNED NOT NULL');
        }
    }

    public function safeDown()
    {
        echo "m261123_120000_fix_auto_increment tidak di-rollback (perbaikan struktural).\n";
        return true;
    }

    private function fixTable($table, $definition)
    {
        $schema = $this->db->getTableSchema($table, true);
        if ($schema === null) {
            echo "  - lewati: tabel `$table` tidak ada\n";
            return;
        }
        $column = $schema->getColumn('id');
        if ($column === null) {
            echo "  - lewati: kolom `id` tidak ada di `$table`\n";
            return;
        }
        if ($column->autoIncrement) {
            echo "  = ok: `$table`.`id` sudah AUTO_INCREMENT\n";
            return;
        }
        $this->execute("ALTER TABLE `$table` MODIFY `id` $definition AUTO_INCREMENT");
        echo "  + diperbaiki: `$table`.`id` -> AUTO_INCREMENT\n";
    }
}
