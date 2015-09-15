<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sisdo\Controller;

use Application\Custom\ActionControllerAbstract;
use Application\Util\PusherUtil;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class NotificationController extends ActionControllerAbstract
{
    public function indexAction()
    {

        $pusher = new PusherUtil();

        $data['message'] = 'hello world';
        $pusher->setChannel('notifications');
        $pusher->setEvent('new_notification');
        $pusher->setData($data);

        $pusher->triggerEvent();

        return new ViewModel(
            array()
        );
    }

    public function getJsonAction()
    {
        return new JsonModel(array(
            'teste' => 'tesstessss'
        ));
    }

    /**
     * Retorna o titulo da pagina (especializar)
     *
     * @return mixed
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @return mixed
     */
    public function getBreadcrumb()
    {
        // TODO: Implement getBreadcrumb() method.
    }


}
