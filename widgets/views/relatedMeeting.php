<span class="meeting-link well well-small pull-right">
    <?= Yii::t('MeetingModule.widgets_views_relatedMeeting', 'This task is related to %link%', array('%link%' => '<a href="'. $meeting->content->container->createUrl('//meeting/index/view', array('id' => $meeting->id)). '"><strong>'. $meeting->title .'</strong></a>')); ?>
</span>


<style type="text/css">
    .meeting-link {
        font-size: 11px;
        color: #999999;
        padding: 2px 5px;
        margin-top: 4px;
    }

    .meeting-link a, .meeting-link a:hover, .meeting-link a:active, .meeting-link a:visited {
        color: #61c2d0;
    }
</style>