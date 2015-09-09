<?php


namespace Application\Helper;


use Zend\View\Helper\AbstractHelper;

class  NavHelper extends AbstractHelper
{

    private $title;
    private $content;
    private $footer;
    private $id = 'myModal';

    /*
 <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Instituicao</a></li>
    <li><a data-toggle="tab" href="#menu1">Contato</a></li>
    <li><a data-toggle="tab" href="#menu2">Endereco</a></li>
</ul>

<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <div class="ajust-left">
            <div class="row botoesacao">
                <?php echo $this->formButton($this->formInstitution->get('btn_salvar')); ?>
                <?php echo $this->formButton($this->formInstitution->get('btn_cancelar')); ?>
            </div>
            <?php echo $this->form()->closeTag(); ?>
        </div>
    </div>
    <div id="menu1" class="tab-pane fade">
        <div class="ajust-left">

            <div class="row botoesacao">
            </div>
            <?php echo $this->form()->closeTag(); ?>
        </div>
    </div>
    <div id="menu2" class="tab-pane fade">
        <h3>Menu 2</h3>
        <p>Some content in menu 2.</p>
    </div>
</div>

      */


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