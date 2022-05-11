<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Lang;
use common\helpers\Strings;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Profile';
?>

<div class="content-wrapper">
	<section class="text-center" style="margin-top: 50px;">
		<div class="container">
			<div class="page-title__holder">
                <?php
                if ($model->image !== null) {
                    echo Html::img($baseUrl.'/uploads/user/large-'.$model->image, ['style' => 'width: 100px; border-radius: 50%;']);
                } else {
                    echo Html::img($baseUrl.'/images/default-user.png', ['style' => 'width: 100px; border-radius: 50%;']);
                }
                ?>
				<h1 class="page-title__title"><?= $model->name ?></h1>
				<p class="page-title__subtitle">Welcome to member page of PT. PUS</p>
			</div>
            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $message . "</div>\n";
            }
            ?>
		</div>
	</section>
    <section class="section-wrap" style="margin-top: 200px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="benefits box-shadow-large offset-top-171">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="tabs">
                                    <ul class="tabs__list">
                                        <li class="tabs__item <?= $tab == 'profile' || $tab == null ? 'tabs__item--active' : '' ?>">
                                            <a href="#tab-1-profile" class="tabs__url tabs__trigger">Profile</a>
                                        </li>
                                        <li class="tabs__item <?= $tab == 'experience' ? 'tabs__item--active' : '' ?>">
                                            <a href="#tab-2-experience" class="tabs__url tabs__trigger">Working Experience</a>
                                        </li>
                                        <li class="tabs__item <?= $tab == 'training' ? 'tabs__item--active' : '' ?>">
                                            <a href="#tab-3-training" class="tabs__url tabs__trigger">Training</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tabs__content tabs__content-trigger">
                                    <div class="tabs__content-pane <?= $tab == 'profile' || $tab == null ? 'tabs__content-pane--active' : '' ?>" id="tab-1-profile">
                                        <?php if (count($model->userProfiles) == 0): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                You have to fill your profile
                                            </div>
                                        <?php else: ?>
                                            <ul class="benefits__list">
                                                <li class="benefits__item">
                                                    <strong>Full Name</strong>: <?= $model->name ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Gender</strong>: <?= $userProfile->gender ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Place of Birth</strong>: <?= $userProfile->place_of_birth ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Date of Birth</strong>: <?= Strings::getFullDate($userProfile->date_of_birth) ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Address</strong>: <?= $userProfile->address ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>ID Number</strong>: <?= $userProfile->id_number ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>City</strong>: <?= $userProfile->city ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>State</strong>: <?= $userProfile->state ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Nationality</strong>: <?= $userProfile->nationality ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Phone</strong>: <?= $userProfile->phone ?>
                                                </li>
                                                <li class="benefits__item">
                                                    <strong>Skills</strong>: <?= $userProfile->skill ?>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                        <br>
                                        <?= Html::a('Update Profile', ['user/update-profile'], ['class' => 'btn btn--md btn--alt-color']) ?>
                                    </div>
                                    <div class="tabs__content-pane <?= $tab == 'experience' ? 'tabs__content-pane--active' : '' ?>" id="tab-2-experience">
                                        <?php if (count($model->userExperiences) == 0): ?>
                                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                Fresh Graduate
                                            </div>
                                        <?php else: ?>
                                            <div id="accordion-experience">
                                                <?php foreach ($model->userExperiences as $userExperience) : ?>
                                                <div class="accordion">
                                                    <div class="accordion__panel">
                                                        <div class="accordion__heading" id="heading-experience-<?= $userExperience->id ?>">
                                                            <a data-toggle="collapse" href="#collapse-experience-<?= $userExperience->id ?>" class="accordion__link" aria-expanded="true" aria-controls="collapse-experience-<?= $userExperience->id ?>">
                                                                <h4><?= $userExperience->company_name ?></h4>
                                                                <span class="accordion__toggle"></span>
                                                            </a>
                                                        </div>
                                                        <div id="collapse-experience-<?= $userExperience->id ?>" class="collapse" data-parent="#accordion" role="tabpanel" aria-labelledby="heading-experience-<?= $userExperience->id ?>">
                                                            <div class="accordion__body">
                                                                <ul class="benefits__list">
                                                                    <li class="benefits__item">
                                                                        <strong>Job Title</strong>: <?= $userExperience->job_title ?>
                                                                    </li>
                                                                    <li class="benefits__item">
                                                                        <?= $userExperience->job_description ?>
                                                                    </li>
                                                                    <li class="benefits__item">
                                                                        <strong>Last Salary</strong>: <?= Strings::getRupiah($userExperience->last_salary) ?>
                                                                    </li>
                                                                </ul>
                                                                <br>
                                                                <?= Html::a('Delete', ['user/delete-experience', 'id' => $userExperience->id], ['class' => 'btn btn--md btn--color', 'onclick' => 'return confirm("Are you sure to delete this item?");']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <br>
                                        <?= Html::a('Add Experience', ['user/add-experience'], ['class' => 'btn btn--md btn--alt-color']) ?>
                                    </div>
                                    <div class="tabs__content-pane <?= $tab == 'training' ? 'tabs__content-pane--active' : '' ?>" id="tab-3-training">
                                        <?php if (count($model->userTrainings) == 0): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                You don't have any training
                                            </div>
                                        <?php else: ?>
                                            <div id="accordion-training">
                                                <?php foreach ($model->userTrainings as $userTraining) : ?>
                                                <div class="accordion">
                                                    <div class="accordion__panel">
                                                        <div class="accordion__heading" id="heading-training-<?= $userTraining->id ?>">
                                                            <a data-toggle="collapse" href="#collapse-training-<?= $userTraining->id ?>" class="accordion__link" aria-expanded="true" aria-controls="collapse-training-<?= $userTraining->id ?>">
                                                                <h4><?= $userTraining->title ?></h4>
                                                                <span class="accordion__toggle"></span>
                                                            </a>
                                                        </div>
                                                        <div id="collapse-training-<?= $userTraining->id ?>" class="collapse" data-parent="#accordion" role="tabpanel" aria-labelledby="heading-training-<?= $userTraining->id ?>">
                                                            <div class="accordion__body">
                                                                <ul class="benefits__list">
                                                                    <li class="benefits__item">
                                                                        <strong>Training Organizer</strong>: <?= $userTraining->organizer ?>
                                                                    </li>
                                                                    <li class="benefits__item">
                                                                        <strong>Date</strong>: <?= Strings::getFullDate($userTraining->date) ?>
                                                                    </li>
                                                                </ul>
                                                                <br>
                                                                <?= Html::a('Delete', ['user/delete-training', 'id' => $userTraining->id], ['class' => 'btn btn--md btn--color', 'onclick' => 'return confirm("Are you sure to delete this item?");']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <br>
                                        <?= Html::a('Add Training', ['user/add-training'], ['class' => 'btn btn--md btn--alt-color']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="benefits__title">Application History</h3>
                                <?php if ($model->userCareers): ?>
                                    <table class="table">
                                        <tr>
                                            <th>Date</th>
                                            <th>Job</th>
                                            <th>Status</th>
                                        </tr>
                                        <?php foreach ($model->userCareers as $userCareer): ?>
                                            <tr>
                                                <td><?= Strings::getHumanDate($userCareer->created_at) ?></td>
                                                <td><?= $userCareer->career->title ?></td>
                                                <td><?= $userCareer->statusString ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                <?php else: ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        You've never been send an application
                                    </div>
                                    <div class="text-center">
                                        <?= Html::a('<span>View all jobs</span>', ['career/index'], ['class' => 'btn btn--md btn--alt-color']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <br>
                <?= Html::a('<span>Logout</span>', ['user/logout'], ['class' => 'btn btn--md btn--color']) ?>
            </div>
        </div>
    </section>
</div>