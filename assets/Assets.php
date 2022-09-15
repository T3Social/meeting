<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\meeting\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class Assets extends AssetBundle
{
    /**
     * v1.5 compatibility defer script loading
     *
     * Migrate to HumHub AssetBundle once minVersion is >=1.5
     *
     * @var bool
     */
    public $defer = true;

    public $publishOptions = [
        'forceCopy' => false
    ];

    public $sourcePath = '@meeting/resources';

    public $css = [
        'css/meeting.css',
    ];

    // We have to use the timeentry lib for the duration since the TimePicker widget uses an older version without maxHour setting...
    public $js = [
        'js/timeentry/jquery.plugin.min.js',
        'js/timeentry/jquery.timeentry.min.js',
        'js/humhub.meeting.js'
    ];

    /**
     * @param View $view
     * @return AssetBundle
     */
    public static function register($view)
    {
        $view->registerJsConfig([
            'meeting' => [
                'text' => [
                    'success.send' => Yii::t('MeetingModule.base', 'Info message has been sent.')
                ]
            ]
        ]);
        return parent::register($view);
    }
}
