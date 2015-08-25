<?php

namespace elitedivision\amos\core\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\Expression;

class SoftDeleteByBehavior extends Behavior
{


    public $deletedAtAttribute = 'deleted_at';
    public $deletedByAttribute = 'deleted_by';
    /**
     * @inheritdoc
     */
    public $timestamp;
    /**
     * @var bool If true, this behavior will process '$model->delete()' as a soft-delete. Thus, the
     *           only way to truely delete a record is to call '$model->forceDelete()'
     */
    public $safeMode = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [ActiveRecord::EVENT_BEFORE_DELETE => 'doDeleteTimestamp'];
    }

    /**
     * Set the attribute with the current timestamp to mark as deleted
     *
     * @param Event $event
     */
    public function doDeleteTimestamp($event)
    {
        // do nothing if safeMode is disabled. this will result in a normal deletion
        if (!$this->safeMode) {
            return;
        }
        // remove and mark as invalid to prevent real deletion
        $this->remove();

        $event->isValid = false;
        return false;
    }

    /**
     * Remove (aka soft-delete) record
     */
    public function remove()
    {
        $user = Yii::$app->get('user', false);
        $timestamp = new Expression('NOW()');
        $userId = $user && !$user->isGuest ? $user->id : null;

        $deletedAtAttribute = $this->deletedAtAttribute;
        $deletedByAttribute = $this->deletedByAttribute;

        $this->owner->$deletedAtAttribute = $timestamp;
        $this->owner->$deletedByAttribute = $userId;

        // save record
        $this->owner->save(false, [$deletedAtAttribute, $deletedByAttribute]);
    }

    /**
     * Restore soft-deleted record
     */
    public function restore()
    {
        // mark attribute as null
        $attribute = $this->attribute;
        $this->owner->$attribute = null;
        // save record
        $this->owner->save(false, [$attribute]);
    }

    /**
     * Delete record from database regardless of the $safeMode attribute
     */
    public function forceDelete()
    {
        // store model so that we can detach the behavior and delete as normal
        $model = $this->owner;
        $this->detach();
        $model->delete();
    }
}