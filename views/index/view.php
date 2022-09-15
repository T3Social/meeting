<?php
use humhub\modules\content\widgets\PinLink;
use humhub\modules\stream\assets\StreamAsset;
use humhub\modules\stream\actions\Stream;

/* @var $meeting \humhub\modules\meeting\models\Meeting */
/* @var $collapse boolean */
?>
<?php StreamAsset::register($this); ?>

<div data-action-component="stream.SimpleStream">
    <?= Stream::renderEntry($meeting, [
        'controlsOptions' => [
            'prevent' => [PinLink::class]
        ]
    ])?>
</div>

