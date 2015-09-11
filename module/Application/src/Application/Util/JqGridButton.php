<?php

/**
 * Created by PhpStorm.
 * User: albov
 * Date: 19/08/2015
 * Time: 23:52
 */
namespace Application\Util;

class JqGridButton
{
    private $class;
    private $id;
    private $url;
    private $icon;
    private $title;
    private $hasTexto;
    private $onClick;
    private $dataToggle;

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getHasTexto()
    {
        return $this->hasTexto;
    }

    /**
     * @param mixed $hasTexto
     */
    public function setHasTexto($hasTexto)
    {
        $this->hasTexto = $hasTexto;
    }

    /**
     * @return mixed
     */
    public function getDataToggle()
    {
        return $this->dataToggle;
    }

    /**
     * @param mixed $dataToggle
     */
    public function setDataToggle($dataToggle)
    {
        $this->dataToggle = $dataToggle;
    }

    public function getValidateOnClick()
    {
        return <<<EOF
        bootbox.confirm("Are you sure?", function(result) {
            return true;
        });
EOF;
    }

    public function setOnClick($function){
        $this->onClick = $function;
    }
    public function getOnClick(){
        return $this->onClick;
    }


    public function render(){
        $hasTexto = $this->hasTexto ? $this->title : '';
        return <<<EOF
        <a class="{$this->class}" href="{$this->url}" title ="{$this->title}" data-toggle = "{$this->dataToggle}"
          onClick="{$this->getOnClick()}"
          >
            <i class='{$this->icon}'></i>$hasTexto
        </a>
EOF;
    }

}