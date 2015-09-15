<?php
/**
 * Created by PhpStorm.
 * User: albov
 * Date: 14/09/2015
 * Time: 20:36
 */

namespace Application\Util;


class PusherUtil extends \Pusher
{
    protected $app_id = '141845';
    protected $app_key = 'c2a648e31b7233e57d83';
    protected $app_secret = '7c782ed5085dbb1d2016';

    private $data;
    private $event;
    private $channel;

    public function triggerEvent(){
        return $this->trigger($this->channel,$this->event,$this->data);
    }

    public function __construct(){
        parent::__construct(
            $this->app_key,
            $this->app_secret,
            $this->app_id,
            array('encrypted' => true)
        );
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->app_key;
    }

    /**
     * @param string $app_key
     */
    public function setAppKey($app_key)
    {
        $this->app_key = $app_key;
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