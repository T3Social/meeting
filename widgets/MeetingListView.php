<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 01.07.2017
 * Time: 12:22
 */

namespace humhub\modules\meeting\widgets;


use humhub\components\Widget;
use humhub\modules\meeting\models\forms\MeetingFilter;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class MeetingListView extends Widget
{
    /**
     * @var MeetingFilter
     */
    public $filter;

    public $canEdit;

    public function run()
    {
        $meetingsProvider = new ActiveDataProvider([
            'query' => $this->filter->query(),
            'pagination' => [
                'pageSize' => 20,
                'route' => '/meeting/index/filter-meetings'
            ],
        ]);

        return  ListView::widget([
            'dataProvider' => $meetingsProvider,
            'itemView' => '@meeting/widgets/views/_meetingItem',
            'viewParams' => [
                'contentContainer' => $this->filter->contentContainer,
                'canEdit' => $this->canEdit
            ],
            'options' => [
                'tag' => 'ul',
                'class' => 'media-list'
            ],
            'itemOptions' => [
                'tag' => 'li'
            ],
            'layout' => "{summary}\n{items}\n<div class=\"pagination-container\">{pager}</div>"
        ]);
    }

}