<?php

namespace common\models;

use Yii;
use common\helpers\Strings;

/**
 * This is the model class for table "visitor".
 *
 * @property int $id
 * @property string $slug
 * @property string $category inquiry / aptitude
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $school
 * @property string $semester
 * @property string $goal
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AptitudeAnswer[] $aptitudeAnswers
 */
class Visitor extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitor';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                 'class' => 'yii\behaviors\TimestampBehavior',
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                     \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                 ],
                 'value' => new \yii\db\Expression('NOW()'),
             ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'address', 'school'], 'required'],
            [['slug', 'address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'name', 'phone', 'email', 'school', 'semester', 'goal'], 'string', 'max' => 255],
            [['message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'category' => 'Category',
            'name' => 'Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'email' => 'Email',
            'school' => 'School',
            'semester' => 'Semester',
            'goal' => 'Goal',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'goalString' => 'Goal',
            'addressSort' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAptitudeAnswers()
    {
        return $this->hasMany(AptitudeAnswer::className(), ['visitor_id' => 'id']);
    }

    public function getAptitudeSortResult()
    {
        // get aptitude answer, if any
        $aptitudeAnswers = AptitudeAnswer::find()->where(['visitor_id' => $this->id])->all();
        if (count($aptitudeAnswers) < 60 ) {
            // return '<label class="label label-danger">N I H I L</label>';
            return '-';
        }

        // raw sql query
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
            select aptitude_type.name as name, aptitude_type.description as description, count(aptitude_type_id) as score
            from aptitude_answer, aptitude_type
            where aptitude_type.id = aptitude_answer.aptitude_type_id
            and visitor_id = '.$this->id.'
            group by aptitude_type_id
            order by score desc
            ');
        $score = $command->queryAll();

        // result
        $scoreI = 0;
        $scoreE = 0;
        $scoreS = 0;
        $scoreN = 0;
        $scoreT = 0;
        $scoreF = 0;
        $scoreJ = 0;
        $scoreP = 0;
        for ($i=0; $i<8; $i++) {
            switch ($score[$i]['name']) {
                case 'I': $scoreI = $score[$i]['score']; break;
                case 'E': $scoreE = $score[$i]['score']; break;
                case 'S': $scoreS = $score[$i]['score']; break;
                case 'N': $scoreN = $score[$i]['score']; break;
                case 'T': $scoreT = $score[$i]['score']; break;
                case 'F': $scoreF = $score[$i]['score']; break;
                case 'J': $scoreJ = $score[$i]['score']; break;
                case 'P': $scoreP = $score[$i]['score']; break;
            }
        }
        // result name
        // $result = '<label class="label label-success">';
        $result = '';
        if ($scoreI > $scoreE) $result .= 'I '; else $result .= 'E ';
        if ($scoreS > $scoreN) $result .= 'S '; else $result .= 'N ';
        if ($scoreT > $scoreF) $result .= 'T '; else $result .= 'F ';
        if ($scoreJ > $scoreP) $result .= 'J '; else $result .= 'P ';
        // $result .= '</label>';

        return $result;
    }

    public function getAptitudeResult()
    {
        // get aptitude answer, if any
        $aptitudeAnswers = AptitudeAnswer::find()->where(['visitor_id' => $this->id])->all();
        if (count($aptitudeAnswers) < 60 ) {
            return '<h1><span class="highlight">NIHIL</span></h1>';
        }

        // raw sql query
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
            select aptitude_type.name as name, aptitude_type.description as description, count(aptitude_type_id) as score
            from aptitude_answer, aptitude_type
            where aptitude_type.id = aptitude_answer.aptitude_type_id
            and visitor_id = '.$this->id.'
            group by aptitude_type_id
            order by score desc
            ');
        $score = $command->queryAll();

        $scoreI = 0;
        $scoreE = 0;
        $scoreS = 0;
        $scoreN = 0;
        $scoreT = 0;
        $scoreF = 0;
        $scoreJ = 0;
        $scoreP = 0;
        for ($i=0; $i<8; $i++) {
            switch ($score[$i]['name']) {
                case 'I': $scoreI = $score[$i]['score']; break;
                case 'E': $scoreE = $score[$i]['score']; break;
                case 'S': $scoreS = $score[$i]['score']; break;
                case 'N': $scoreN = $score[$i]['score']; break;
                case 'T': $scoreT = $score[$i]['score']; break;
                case 'F': $scoreF = $score[$i]['score']; break;
                case 'J': $scoreJ = $score[$i]['score']; break;
                case 'P': $scoreP = $score[$i]['score']; break;
            }
        }
        // result name
        $result = '<h1><span class="highlight">';
        if ($scoreI > $scoreE) $result .= 'I '; else $result .= 'E ';
        if ($scoreS > $scoreN) $result .= 'S '; else $result .= 'N ';
        if ($scoreT > $scoreF) $result .= 'T '; else $result .= 'F ';
        if ($scoreJ > $scoreP) $result .= 'J '; else $result .= 'P ';
        $result .= '</h1>';
        $result .= '<ul class="benefits__list">';

        // result comparison
        $result .= '<table class="table">';
        $result .= '<tr>'.
                    '<td>'.($scoreI>$scoreE?'<b>Introvert</b>':'Introvert').'</td><td>'.round(($scoreI/($scoreI+$scoreE))*100).'%</td>'.
                    '<td>'.round(($scoreE/($scoreI+$scoreE))*100).'%</td><td>'.($scoreI<$scoreE?'<b>Extrovert</b>':'Extrovert').'</td></tr>';
        $result .= '<tr>'.
                    '<td>'.($scoreS>$scoreN?'<b>Sensing</b>':'Sensing').'</td><td>'.round(($scoreS/($scoreS+$scoreN))*100).'%</td>'.
                    '<td>'.round(($scoreN/($scoreS+$scoreN))*100).'%</td><td>'.($scoreS<$scoreN?'<b>Intuition</b>':'Intuition').'</td></tr>';
        $result .= '<tr>'.
                    '<td>'.($scoreT>$scoreF?'<b>Thinking</b>':'Thinking').'</td><td>'.round(($scoreT/($scoreT+$scoreF))*100).'%</td>'.
                    '<td>'.round(($scoreF/($scoreT+$scoreF))*100).'%</td><td>'.($scoreT<$scoreF?'<b>Feeling</b>':'Feeling').'</td></tr>';
        $result .= '<tr>'.
                    '<td>'.($scoreJ>$scoreP?'<b>Judging</b>':'Judging').'</td><td>'.round(($scoreJ/($scoreJ+$scoreP))*100).'%</td>'.
                    '<td>'.round(($scoreP/($scoreJ+$scoreP))*100).'%</td><td>'.($scoreJ<$scoreP?'<b>Perceiving</b>':'Perceiving').'</td></tr>';
        $result .= '</table>';
        $result .= '</ul>';

        return $result;
    }

    /*
    public function getAptitudeFullResult()
    {
        // get aptitude answer, if any
        $aptitudeAnswers = AptitudeAnswer::find()->where(['visitor_id' => $this->id])->all();
        if (count($aptitudeAnswers) < 60 ) {
            return '<h1><span class="highlight">NIHIL</span></h1>';
        }

        // raw sql query
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('
            select aptitude_type.name as name, aptitude_type.description as description, count(aptitude_type_id) as score
            from aptitude_answer, aptitude_type
            where aptitude_type.id = aptitude_answer.aptitude_type_id
            and visitor_id = '.$this->id.'
            group by aptitude_type_id
            order by score desc
            ');
        $score = $command->queryAll();

        // result
        $result = '<h1><span class="highlight">';
        for ($i=0; $i<4; $i++) { $result .= $score[$i]['name'].' '; }
        $result .= '</h1>';
        $result .= '<ul class="benefits__list">';
        for ($i=0; $i<8; $i++) {
            if ($i<4) {
                $result .= '<li class="benefits__item"><i class="ui-check benefits__item-icon"></i> <span class="benefits__item-title highlight">'.$score[$i]['description'].' ('.$score[$i]['score'].')</span></li>';
            } else {
                $result .= '<li class="benefits__item"><i class="ui-check benefits__item-icon"></i> <span class="benefits__item-title">'.$score[$i]['description'].' ('.$score[$i]['score'].')</span></li>';
            }
        }
        $result .= '</ul>';

        return $result;
    }
    */

    public function getGoalString()
    {
        $goal = '';
        switch ($this->goal) {
            case '1':
                $goal = 'Konsultasi Kuliah Di Luar Negeri';
                break;
            case '2':
                $goal = 'Informasi Les Bahasa Inggris';
                break;
            case '3':
                $goal = 'Informasi International Test';
                break;
            case '4':
                $goal = 'Summer Course';
                break;
        }
        return $goal;
    }

    public function getAddressSort()
    {
        return Strings::sortText($this->address, 30);
    }
}
