<?php


namespace Application\Helper;


use Zend\View\Helper\AbstractHelper;

class  ModalHelper extends AbstractHelper
{

    private $title;
    private $content;
    private $footer;
    private $id = 'myModal';


    public function __construct($id = null)
    {
        if (isset($id)) {
            $this->id = $id;
        }
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param mixed $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
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


    public function renderModal()
    {
        return <<<DOC
        <div class="modal fade" id="{$this->getId()}" tabindex="-1" role="dialog" aria-labelledby="{$this->getTitle()}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{$this->getTitle()}</h4>
                    </div>
                    <div class="modal-body">
                        {$this->getContent()}
                    </div>
                    <div class="modal-footer">
                        <div class="botoesacao" style="margin: 0">
                            {$this->getFooter()}
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div><!-- /.modal -->
DOC;

    }

}