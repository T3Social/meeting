<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\meeting\widgets;

use humhub\modules\content\widgets\stream\StreamEntryOptions;
use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use humhub\modules\content\widgets\WallEntryControlLink;
use humhub\modules\meeting\assets\Assets;
use humhub\modules\meeting\models\Meeting;
use Yii;

/**
 * @inheritdoc
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @inheritDoc
     */
    public $editMode = self::EDIT_MODE_MODAL;

    /**
     * @var Meeting
     */
    public $model;

    /**
     * @inheritDoc
     */
    public function getEditUrl()
    {
        return $this->model->content->container->createUrl('/meeting/index/edit', ['id' => $this->model->id]);
    }

    /**
     * @inheritdoc
     */
    public function renderContent()
    {
        Assets::register($this->view);
        return $this->render('wallEntry', ['meeting' => $this->model, 'justEdited' => $this->renderOptions->justEdited]);
    }

    /**
     * @inheritDoc
     */
    public function getControlsMenuEntries()
    {
        $result = parent::getControlsMenuEntries();

        if(!$this->isInModal() || !$this->model->content->canEdit()) {
            return $result;
        }

        // TODO: remove this after simplestream modal edit/delete runs as expected
        $this->renderOptions->disableControlsEntryEdit()->disableControlsEntryDelete();

        $result[] =  [
            'class' => WallEntryControlLink::class,
            'label' => Yii::t('MeetingModule.base', 'Edit'),
            'icon' => 'fa-pencil',
            'data-action-click' => 'meeting.editMeeting',
            'data-action-url' => $this->model->content->container->createUrl('/meeting/index/edit', ['id' => $this->model->id, 'cal' => true]),
            'sortOrder' => 100
        ];

        $result[] =  [
            'class' => WallEntryControlLink::class,
            'label' => Yii::t('MeetingModule.base', 'Delete'),
            'icon' => 'fa-trash',
            'data-action-click' => 'meeting.deleteMeeting',
            'data-action-url' => $this->model->content->container->createUrl('/meeting/index/delete', ['id' => $this->model->id, 'cal' => true]),
            'sortOrder' => 100
        ];

        return $result;
    }

    /**
     * @return bool true if view context is modal
     */
    public function isInModal()
    {
        return $this->renderOptions->isViewContext(StreamEntryOptions::VIEW_CONTEXT_MODAL);
    }

    /**
     * @return string a non encoded plain text title (no html allowed) used in the header of the widget
     */
    protected function getTitle()
    {
        return $this->model->title;
    }
}
