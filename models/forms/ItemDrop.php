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
 * Date: 25.06.2017
 * Time: 23:23
 */

namespace humhub\modules\meeting\models\forms;


use humhub\modules\meeting\models\Meeting;
use yii\base\Model;

class ItemDrop extends Model
{
    /**
     * @var integer
     */
    public $meetingId;

    /**
     * @var Meeting
     */
    public $meeting;

    /**
     * @var integer
     */
    public $index;

    /**
     * @var integer
     */
    public $itemId;


    public function init()
    {
        $this->meeting = Meeting::findOne(['id' => $this->meetingId]);
    }

    public function save()
    {
        return $this->meeting->moveItemIndex($this->itemId, $this->index);
    }

    public function rules()
    {
        return [
            [['itemId', 'index'], 'integer']
        ];
    }

}