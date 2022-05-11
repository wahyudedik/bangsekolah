<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "biodata".
 *
 * @property int $no
 * @property string $kd_biodata
 * @property string $nama
 * @property string $dob
 * @property string $gender
 * @property string $address
 * @property string $city
 * @property string $area
 * @property string $hm_ph
 * @property string $hp
 * @property string $org_school
 * @property string $grade
 * @property string $acd_history
 * @property string $company
 * @property string $add_comp
 * @property string $ph_comp
 * @property string $city_comp
 * @property string $parent_nm
 * @property string $add_parent
 * @property string $city_parent
 * @property string $ph_parent
 * @property string $hp_parent
 * @property string $data_source
 * @property string $ds_dtl
 * @property string $media_source
 * @property string $ms_dtl
 * @property string $dt_entry
 * @property string $level_school
 * @property string $pic
 * @property string $time_entry
 * @property string $groupname
 * @property string $branch
 * @property string $post_comp
 * @property string $promo_type
 * @property string $country_dest
 * @property string $school_dest
 * @property string $program_dest
 * @property string $hp_2
 * @property string $program_type
 * @property string $class_type
 * @property string $schedule
 * @property string $country_dest_dtl
 * @property string $school_dest_dtl
 * @property string $program_dest_dtl
 * @property string $e_mail
 * @property string $family_name
 * @property string $st_new
 * @property string $kd_pos
 * @property string $vip_status
 * @property string $ves_status
 * @property string $vip_program
 * @property string $middle_name
 * @property string $remark
 * @property string $major_dest
 * @property string $nationality
 * @property string $mother_name
 * @property string $ph_mother
 * @property string $fax
 * @property string $sibling_1
 * @property string $sibling_2
 * @property string $sibling_3
 * @property string $sibling_4
 * @property string $sibling_5
 * @property string $level_sibling_1
 * @property string $level_sibling_2
 * @property string $level_sibling_3
 * @property string $level_sibling_4
 * @property string $level_sibling_5
 * @property string $school_sibling_1
 * @property string $school_sibling_2
 * @property string $school_sibling_3
 * @property string $school_sibling_4
 * @property string $school_sibling_5
 * @property string $vip_remark
 * @property string $event_st
 * @property string $event_st_dtl
 * @property string $event_type
 * @property string $event_type_dtl
 * @property string $media_src
 * @property string $media_src_dtl
 * @property string $mkt_city
 * @property string $upload
 * @property string $vgo_program
 * @property string $vgo_country
 * @property string $vgo_city
 * @property string $vgo_dest_detail
 * @property string $vgo_departure
 * @property string $vgo_group
 * @property string $vgo_remark
 * @property string $vgo_status
 * @property string $sales_year
 * @property string $city_dest
 * @property string $new_old
 * @property string $upload_st
 * @property string $ves_last_update
 * @property string $vip_last_update
 * @property string $vip_class_code
 * @property string $vip_class_status
 * @property string $kode_urut_event
 * @property int $signed_status
 * @property string $signed_by
 * @property string $signed_to
 * @property string $signed_date
 * @property string $next_fu
 * @property string $date_fu
 * @property string $next_remark
 * @property string $sosmed
 * @property string $parent_email
 * @property string $partnership
 * @property string $kd_branch
 * @property string $last_result
 * @property string $sign_note
 * @property string $country_1
 * @property string $country_2
 * @property string $country_3
 * @property string $school_1
 * @property string $school_2
 * @property string $school_3
 * @property string $program_1
 * @property string $program_2
 * @property string $program_3
 * @property string $kd_school_1
 * @property string $kd_school_2
 * @property string $kd_school_3
 * @property int $task
 * @property string $vip_class_type
 * @property string $vip_schedule
 * @property string $sm_1
 * @property string $sm_2
 * @property string $sm_3
 * @property string $sm_4
 * @property string $sm_5
 * @property string $sm_6
 * @property string $org_city_school
 */
class Biodata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biodata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dob', 'dt_entry', 'time_entry', 'ves_last_update', 'vip_last_update', 'signed_date', 'date_fu'], 'safe'],
            [['signed_status', 'task'], 'integer'],
            [['kd_biodata', 'nama', 'gender', 'address', 'city', 'area', 'hm_ph', 'hp', 'org_school', 'grade', 'acd_history', 'company', 'add_comp', 'ph_comp', 'city_comp', 'parent_nm', 'add_parent', 'city_parent', 'ph_parent', 'hp_parent', 'data_source', 'ds_dtl', 'media_source', 'ms_dtl', 'level_school', 'pic', 'groupname', 'branch', 'post_comp', 'promo_type', 'country_dest', 'school_dest', 'program_dest', 'hp_2', 'program_type', 'class_type', 'schedule', 'country_dest_dtl', 'school_dest_dtl', 'program_dest_dtl', 'e_mail', 'family_name', 'st_new', 'kd_pos', 'vip_status', 'ves_status', 'vip_program', 'middle_name', 'remark', 'major_dest', 'nationality', 'mother_name', 'ph_mother', 'fax', 'sibling_1', 'sibling_2', 'sibling_3', 'sibling_4', 'sibling_5', 'level_sibling_1', 'level_sibling_2', 'level_sibling_3', 'level_sibling_4', 'level_sibling_5', 'school_sibling_1', 'school_sibling_2', 'school_sibling_3', 'school_sibling_4', 'school_sibling_5', 'vip_remark', 'event_st', 'event_st_dtl', 'event_type', 'event_type_dtl', 'media_src', 'media_src_dtl', 'mkt_city', 'upload', 'vgo_program', 'vgo_country', 'vgo_city', 'vgo_dest_detail', 'vgo_departure', 'vgo_group'], 'string', 'max' => 255],
            [['vgo_remark', 'vgo_status', 'sales_year', 'city_dest', 'new_old', 'upload_st', 'vip_class_code', 'vip_class_status', 'signed_by', 'signed_to', 'parent_email', 'last_result', 'country_1', 'country_2', 'country_3', 'school_1', 'school_2', 'school_3', 'program_1', 'program_2', 'program_3', 'kd_school_1', 'kd_school_2', 'kd_school_3', 'vip_class_type', 'vip_schedule', 'org_city_school'], 'string', 'max' => 45],
            [['kode_urut_event'], 'string', 'max' => 5],
            [['next_fu', 'partnership', 'sm_1', 'sm_2', 'sm_3', 'sm_4', 'sm_5', 'sm_6'], 'string', 'max' => 15],
            [['next_remark'], 'string', 'max' => 205],
            [['sosmed'], 'string', 'max' => 254],
            [['kd_branch'], 'string', 'max' => 4],
            [['sign_note'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'kd_biodata' => 'Kd Biodata',
            'nama' => 'Nama',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'address' => 'Address',
            'city' => 'City',
            'area' => 'Area',
            'hm_ph' => 'Hm Ph',
            'hp' => 'Hp',
            'org_school' => 'Org School',
            'grade' => 'Grade',
            'acd_history' => 'Acd History',
            'company' => 'Company',
            'add_comp' => 'Add Comp',
            'ph_comp' => 'Ph Comp',
            'city_comp' => 'City Comp',
            'parent_nm' => 'Parent Nm',
            'add_parent' => 'Add Parent',
            'city_parent' => 'City Parent',
            'ph_parent' => 'Ph Parent',
            'hp_parent' => 'Hp Parent',
            'data_source' => 'Data Source',
            'ds_dtl' => 'Ds Dtl',
            'media_source' => 'Media Source',
            'ms_dtl' => 'Ms Dtl',
            'dt_entry' => 'Dt Entry',
            'level_school' => 'Level School',
            'pic' => 'Pic',
            'time_entry' => 'Time Entry',
            'groupname' => 'Groupname',
            'branch' => 'Branch',
            'post_comp' => 'Post Comp',
            'promo_type' => 'Promo Type',
            'country_dest' => 'Country Dest',
            'school_dest' => 'School Dest',
            'program_dest' => 'Program Dest',
            'hp_2' => 'Hp 2',
            'program_type' => 'Program Type',
            'class_type' => 'Class Type',
            'schedule' => 'Schedule',
            'country_dest_dtl' => 'Country Dest Dtl',
            'school_dest_dtl' => 'School Dest Dtl',
            'program_dest_dtl' => 'Program Dest Dtl',
            'e_mail' => 'E Mail',
            'family_name' => 'Family Name',
            'st_new' => 'St New',
            'kd_pos' => 'Kd Pos',
            'vip_status' => 'Vip Status',
            'ves_status' => 'Ves Status',
            'vip_program' => 'Vip Program',
            'middle_name' => 'Middle Name',
            'remark' => 'Remark',
            'major_dest' => 'Major Dest',
            'nationality' => 'Nationality',
            'mother_name' => 'Mother Name',
            'ph_mother' => 'Ph Mother',
            'fax' => 'Fax',
            'sibling_1' => 'Sibling 1',
            'sibling_2' => 'Sibling 2',
            'sibling_3' => 'Sibling 3',
            'sibling_4' => 'Sibling 4',
            'sibling_5' => 'Sibling 5',
            'level_sibling_1' => 'Level Sibling 1',
            'level_sibling_2' => 'Level Sibling 2',
            'level_sibling_3' => 'Level Sibling 3',
            'level_sibling_4' => 'Level Sibling 4',
            'level_sibling_5' => 'Level Sibling 5',
            'school_sibling_1' => 'School Sibling 1',
            'school_sibling_2' => 'School Sibling 2',
            'school_sibling_3' => 'School Sibling 3',
            'school_sibling_4' => 'School Sibling 4',
            'school_sibling_5' => 'School Sibling 5',
            'vip_remark' => 'Vip Remark',
            'event_st' => 'Event St',
            'event_st_dtl' => 'Event St Dtl',
            'event_type' => 'Event Type',
            'event_type_dtl' => 'Event Type Dtl',
            'media_src' => 'Media Src',
            'media_src_dtl' => 'Media Src Dtl',
            'mkt_city' => 'Mkt City',
            'upload' => 'Upload',
            'vgo_program' => 'Vgo Program',
            'vgo_country' => 'Vgo Country',
            'vgo_city' => 'Vgo City',
            'vgo_dest_detail' => 'Vgo Dest Detail',
            'vgo_departure' => 'Vgo Departure',
            'vgo_group' => 'Vgo Group',
            'vgo_remark' => 'Vgo Remark',
            'vgo_status' => 'Vgo Status',
            'sales_year' => 'Sales Year',
            'city_dest' => 'City Dest',
            'new_old' => 'New Old',
            'upload_st' => 'Upload St',
            'ves_last_update' => 'Ves Last Update',
            'vip_last_update' => 'Vip Last Update',
            'vip_class_code' => 'Vip Class Code',
            'vip_class_status' => 'Vip Class Status',
            'kode_urut_event' => 'Kode Urut Event',
            'signed_status' => 'Signed Status',
            'signed_by' => 'Signed By',
            'signed_to' => 'Signed To',
            'signed_date' => 'Signed Date',
            'next_fu' => 'Next Fu',
            'date_fu' => 'Date Fu',
            'next_remark' => 'Next Remark',
            'sosmed' => 'Sosmed',
            'parent_email' => 'Parent Email',
            'partnership' => 'Partnership',
            'kd_branch' => 'Kd Branch',
            'last_result' => 'Last Result',
            'sign_note' => 'Sign Note',
            'country_1' => 'Country 1',
            'country_2' => 'Country 2',
            'country_3' => 'Country 3',
            'school_1' => 'School 1',
            'school_2' => 'School 2',
            'school_3' => 'School 3',
            'program_1' => 'Program 1',
            'program_2' => 'Program 2',
            'program_3' => 'Program 3',
            'kd_school_1' => 'Kd School 1',
            'kd_school_2' => 'Kd School 2',
            'kd_school_3' => 'Kd School 3',
            'task' => 'Task',
            'vip_class_type' => 'Vip Class Type',
            'vip_schedule' => 'Vip Schedule',
            'sm_1' => 'Sm 1',
            'sm_2' => 'Sm 2',
            'sm_3' => 'Sm 3',
            'sm_4' => 'Sm 4',
            'sm_5' => 'Sm 5',
            'sm_6' => 'Sm 6',
            'org_city_school' => 'Org City School',
        ];
    }
}
