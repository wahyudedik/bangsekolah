<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\ViewNotFoundException;

class BackupController extends Controller
{
    public function actionDatabase()
    {
        // $db = new \mysqli('localhost', 'banksekolah', 'BanksekolahDB123', 'banksekolah');
        $db = new \mysqli('localhost', 'root', 'root', 'banksekolah');
        $sql = "";

        // get tables
        $tables = [];
        $query = $db->query("SHOW TABLES");
        while($row = $query->fetch_row()){
            $tables[] = $row[0];
        }

        // loop for all tables
        foreach($tables as $table){
            $query = $db->query("SELECT * FROM $table");
            $fieldCount = $query->field_count;

            // drop table            
            $sql .= "DROP TABLE $table;";
            $query2 = $db->query("SHOW CREATE TABLE $table");
            $createSQL = $query2->fetch_row();

            // create table
            $sql .= "\n\n".$createSQL[1].";\n\n";

            for($i = 0; $i < $fieldCount; $i++){
                while($row = $query->fetch_row()){

                    // insert into
                    $sql .= "INSERT INTO $table VALUES(";
                    for($j=0; $j < $fieldCount; $j++){
                        $row[$j] = addslashes($row[$j]);
                        // $row[$j] = preg_replace("\n", "\\n", $row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) { $sql .= '"'.$row[$j].'"' ; } else { $sql .= '""'; }
                        if ($j < ($fieldCount-1)) { $sql.= ','; }
                    }
                    $sql .= ");\n";

                }
            }
            $sql .= "\n\n\n";
        }
        
        // save file
        $handle = fopen(Yii::getAlias('@uploads').'/database/banksekolah '.date('Y-m-d').'.sql', 'w+');
        fwrite($handle,$sql);
        fclose($handle);

        return 'Database backup success<br>Filename: banksekolah '.date('Y-m-d');
    }
}