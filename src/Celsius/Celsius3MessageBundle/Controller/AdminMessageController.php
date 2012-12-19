<?php
namespace Celsius\Celsius3MessageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Celsius\Celsius3MessageBundle\Controller\MessageController;

/**
 * AdminMessageController controller.
 * @Route("/admin/Message")
 */
class AdminMessageController extends MessageController
{
    /**
     * Displays the authenticated participant inbox
     * @Route("/inbox", name="admin_messages_inbox")
     * @return Response
     */
    public function inboxAction()
    { 
       return parent::inboxAction();
    }

    /**
     * Displays a thread, also allows to reply to it
     *
     * @Route("/reply/{threadId}", name="admin_message_thread_view")
     * @param strind $threadId the thread id
     * @return Response
     */
    public function threadAction($threadId)
    {
       return parent::threadAction($threadId);
    }

    /**
     * Displays the authenticated participant sent mails
     * @Route("/sent", name="admin_message_sent")
     * @return Response
     */
    public function sentAction()
    {
        return parent::sentAction();
    }
    
    /**
     * Create a new message thread
     * @Route("/new", name="admin_message_thread_new")
     * @return Response
     */
    public function newThreadAction()
    {
        return parent::newThreadAction();
    }

    /**
     * Deletes a thread
     * @Route("/{threadId}/delete", name="admin_message_thread_delete")
     * @Method("post")
     * @return Response
     */
    public function deleteAction($threadId)
    {
        return parent::deleteAction($threadId);
    }

    /**
     * Searches for messages in the inbox and sentbox
     * @Route("/search", name="admin_message_search")
     * @return Response
     */
    public function searchAction()
    {
        return parent::searchAction();
    }

}
