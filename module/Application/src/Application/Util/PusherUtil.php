<?php
/**
 * Created by PhpStorm.
 * User: albo.vieira
 * Date: 15/09/2015
 * Time: 09:40
 */

namespace Application\Util;


class PusherUtil extends \Pusher
{

    private $channel;
    private $event;
    private $data;

    const APP_KEY = 'c2a648e31b7233e57d83';
    const APP_SECRET = '7c782ed5085dbb1d2016';
    const APP_ID = '141845';

    public function triggerEvent()
    {
        $this->trigger($this->channel, $this->event, $this->data);
    }

    public function __construct()
    {
        parent::__construct(PusherUtil::APP_KEY, PusherUtil::APP_SECRET, PusherUtil::APP_ID);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }


}