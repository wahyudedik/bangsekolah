<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property string $slug
 * @property string $number
 * @property string $category
 * @property string $name
 * @property string $passport_id
 * @property string $nationality
 * @property string $country_of_birth
 * @property string $date_of_birth
 * @property string $gender
 * @property string $religion
 * @property string $email
 * @property string $mobile_phone
 * @property string $phone
 * @property string $address
 * @property string $country
 * @property string $postcode
 * @property string $prev_education_name
 * @property string $prev_education_address
 * @property string $prev_major
 * @property string $employment_status
 * @property string $marital_status
 * @property string $father_name
 * @property string $father_mobile
 * @property string $father_occupation
 * @property string $mother_name
 * @property string $mother_mobile
 * @property string $mother_occupation
 * @property string $study_program
 * @property string $major
 * @property string $intake
 * @property string $intended_transfer_university
 * @property string $intended_transfer_university_1
 * @property string $intended_transfer_university_2
 * @property string $intended_transfer_university_3
 * @property string $prev_school
 * @property string $prev_school_city
 * @property string $prev_school_country
 * @property string $level
 * @property string $stream
 * @property string $english_proficiency_test
 * @property string $english_proficiency_result
 * @property string $english_prev_study
 * @property string $application_referrer
 * @property string $application_referrer_name
 * @property string $currently_student
 * @property string $currently_student_name
 * @property string $student_signature_name
 * @property string $student_signature_date
 * @property string $student_signature_image
 * @property string $parent_signature_name
 * @property string $parent_signature_date
 * @property string $parent_signature_image
 * @property string $student_image
 * @property string $created_at
 * @property string $updated_at
 */
class Application extends \yii\db\ActiveRecord
{
    public $step;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
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
        $rules = [
            [['address', 'prev_education_address'], 'string'],
            [['date_of_birth', 'student_signature_date', 'parent_signature_date', 'created_at', 'updated_at', 'step'], 'safe'],
            [['slug', 'number', 'category', 'name', 'passport_id', 'nationality', 'country_of_birth', 'gender', 'religion', 'email', 'mobile_phone', 'phone', 'country', 'postcode', 'prev_education_name', 'prev_major', 'employment_status', 'marital_status', 'father_name', 'father_mobile', 'father_occupation', 'mother_name', 'mother_mobile', 'mother_occupation', 'study_program', 'major', 'intake', 'intended_transfer_university', 'intended_transfer_university_1', 'intended_transfer_university_2', 'intended_transfer_university_3', 'prev_school', 'prev_school_city', 'prev_school_country', 'level', 'stream', 'english_proficiency_test', 'english_proficiency_result', 'english_prev_study', 'application_referrer', 'application_referrer_name', 'currently_student', 'currently_student_name', 'student_signature_name', 'student_signature_image', 'parent_signature_name', 'parent_signature_image', 'student_image'], 'string', 'max' => 255],
            // [['name', 'student_image', 'student_signature_image', 'parent_signature_image'], 'required'],
        ];

        if ($this->step == 1) {
            $rules[] = [['name', 'passport_id', 'nationality', 'country_of_birth', 'date_of_birth', 'gender', 'email', 'mobile_phone', 'address', 'country', 'postcode', 'prev_education_name', 'prev_education_address', 'prev_major', 'employment_status', 'religion', 'father_name', 'father_mobile', 'father_occupation', 'mother_name', 'mother_mobile', 'mother_occupation', 'level'], 'required'];
        }
        if ($this->step == 2) {
            $rules[] = [['study_program', 'major', 'intake', 'intended_transfer_university'], 'required'];
        }
        if ($this->step == 3) {
            $rules[] = [['english_proficiency_test'], 'required'];
        }
        if ($this->step == 4) {
            $rules[] = [['student_signature_image', 'student_signature_name', 'student_signature_date', 'student_image'], 'required'];
        }
        return $rules;        

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'number' => 'Number',
            'category' => 'Category',
            'name' => 'Name (As in Passport)',
            'passport_id' => 'Passport / ID',
            'nationality' => 'Nationality (As in Passport)',
            'country_of_birth' => 'Country Of Birth',
            'date_of_birth' => 'Date Of Birth',
            'gender' => 'Gender',
            'religion' => 'Religion',
            'email' => 'Email',
            'mobile_phone' => 'Mobile Phone',
            'phone' => 'Phone',
            'address' => 'Address',
            'country' => 'Country',
            'postcode' => 'Postcode',
            'prev_education_name' => 'Previous Education Name',
            'prev_education_address' => 'Previous Education Address',
            'prev_major' => 'Previous Major',
            'employment_status' => 'Employment Status',
            'marital_status' => 'Marital Status',
            'father_name' => 'Fathers Name',
            'father_mobile' => 'Fathers Mobile',
            'father_occupation' => 'Fathers Occupation',
            'mother_name' => 'Mothers Name',
            'mother_mobile' => 'Mothers Mobile',
            'mother_occupation' => 'Mothers Occupation',
            'study_program' => 'Study Program',
            'major' => 'Major',
            'intake' => 'Intake',
            'intended_transfer_university' => 'Do You have any choices of intended transfer universities?',
            'intended_transfer_university_1' => 'Intended Transfer University 1',
            'intended_transfer_university_2' => 'Intended Transfer University 2',
            'intended_transfer_university_3' => 'Intended Transfer University 3',
            'prev_school' => 'Previous School',
            'prev_school_city' => 'Previous School City',
            'prev_school_country' => 'Previous School Country',
            'level' => 'Level',
            'stream' => 'Stream',
            'english_proficiency_test' => 'I have done English proficiency test before',
            'english_proficiency_result' => 'English Proficiency Result',
            'english_prev_study' => 'English was the language of instruction in previous studies completed',
            'application_referrer' => 'This Appication form is submitted on the behalf of',
            'application_referrer_name' => 'Application Referrer Name',
            'currently_student' => 'Are you currently enrolled as a college student?',
            'currently_student_name' => 'College name',
            'student_signature_name' => 'Student Signature Name',
            'student_signature_date' => 'Student Signature Date',
            'student_signature_image' => 'Student Signature Image',
            'parent_signature_name' => 'Parent Signature Name',
            'parent_signature_date' => 'Parent Signature Date',
            'parent_signature_image' => 'Parent Signature Image',
            'student_image' => 'Student Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
