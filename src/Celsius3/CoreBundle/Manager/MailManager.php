<?php

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Order;

class MailManager
{

    const MAIL__ORDER_PRINTED = 'order_printed';
    const MAIL__ORDER_DOWNLOAD = 'order_download';
    const MAIL__ORDER_CANCEL = 'order_cancel';
    const MAIL__ORDER_PRINTED_RECONFIRM = 'order_printed_reconfirm';
    const MAIL__USER_WELCOME = 'user_welcome';
    const MAIL__USER_WELCOME_PROVISION = 'user_welcome_provision';
    const MAIL__USER_LOST = 'user_lost';

    private $dm;
    private $twig;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $loader = new \Twig_Loader_String();
        $this->twig = new \Twig_Environment($loader);
    }

    public function renderTemplate($code, BaseUser $user, Order $order = null)
    {
        $template = $this->dm->getRepository('Celsius3CoreBundle:MailTemplate')
                ->findOneBy(array('code' => $code));

        return $this->twig->render($template->getText(), array(
            'user' => $user,
            'order' => $order
        ));
    }

}
