<?php

namespace app\components;

use Closure;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidCallException;

class ActionBehavior extends Behavior {
    /**
     * @var array список событий, по которым проверять
     *
     * ```php
     * [
     *     ActiveRecord::EVENT_BEFORE_INSERT,
     *     ActiveRecord::EVENT_BEFORE_UPDATE,
     * ]
     * ```
     */
    public $allevents = [];

    /**
     * @var mixed функция для выполнения
     *
     * ```php
     * function ($event)
     * {
     * }
     * ```
     */
    public $action;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_fill_keys($this->allevents, 'testEvent');
    }

    /**
     * Выполняем проверку на необходимость извещения
     * @param Event $event
     */
    public function testEvent($event)
    {
        if( in_array($event->name, $this->allevents) ) {
            $this->doAction($event);
        }
    }

    /**
     * @param Event $event
     */
    protected function doAction($event)
    {
        if( $this->action instanceof Closure ) {
            call_user_func($this->action, $event);
        }
        else {
            throw new InvalidCallException('Dosnt exist action in ActionBehavior');
        }
    }
}