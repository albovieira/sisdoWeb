<?php


namespace Application\Helper;


use Zend\View\Helper\AbstractHelper;

class  NavHelper extends AbstractHelper
{

    private $menuNav;
    private $conteudoContainer;

    public function criaMenuNav(array $opcoes){

        $this->menuNav = '<ul class="nav nav-tabs">';

        foreach($opcoes as $key=>$opcao){
            if($key == 0){
                $this->menuNav .= "<li class='active'><a data-toggle='tab' href='#home'>$opcao</a></li>";
            }else{
                $this->menuNav .= "<li><a data-toggle='tab' href='#menu{$key}'>$opcao</a></li>";
            }
        }
        $this->menuNav .= '</ul>';
    }

    public function criaConteudo($quantItens){
        $this->conteudoContainer = "<div class='tab-content'>";

        for($i=0;$i<$quantItens;$i++){
            if($i==0){
                $this->conteudoContainer .= "
                    <div id='home' class='tab-pane fade in active'>
                        <div class='ajust-left'>
                            <div class='row botoesacao'>

                            </div>
                        </div>
                    </div>
                ";
            }else{
                $this->conteudoContainer .= "
                <div id='menu1' class='tab-pane fade'>
                    <div class='ajust-left'>
                        <div class='row botoesacao'>

                        </div>
                    </div>
                </div>";
            }
        }

        $this->conteudoContainer .= "</div>";
    }


    public function renderNav()
    {
        return <<<DOC
        $this->menuNav
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="ajust-left">
                    <div class="row botoesacao">

                    </div>
                </div>
            </div>
            <div id="menu1" class="tab-pane fade">
                <div class="ajust-left">
                    <div class="row botoesacao">

                    </div>
                </div>
            </div>
            <div id="menu2" class="tab-pane fade">
               <div class="ajust-left">
                    <div class="row botoesacao">

                    </div>
                </div>
            </div>
        </div>
DOC;

    }

}