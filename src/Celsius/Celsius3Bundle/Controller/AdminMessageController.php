<?php
namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
/**
 * AdminMessageController controller.
 *
 * @Route("/admin/Message")
 */
class AdminMessageController extends \FOS\MessageBundle\Controller\MessageController 
{
    
    public function generateFormsToThreads($threads)
    {
        $forms = array();
        foreach ($threads as $thread) {
            $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
            $forms[$thread->getId()] = $form->createView();
        }
        return $forms;
    }

    
    /**
     * Displays the authenticated participant inbox
     *
     * @Route("/unRead_message", name="admin_unRead_message")
     * @return Response
     */
    public function getUnReadMessageAction()
    { 
        $threads = $this->getProvider()->getNbUnreadMessages();
        return new Response($threads);
    }
    
    /**
     * Displays the authenticated participant inbox
     *
     * @Route("/inbox", name="admin_messages_inbox")
     * @return Response
     */
    public function inboxAction()
    { 
        $threads = $this->getProvider()->getInboxThreads();
        $forms = $this->generateFormsToThreads($threads);
        
        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
            'threads' => $threads,
            'forms' => $forms
        ));
    }

    /**
     * Displays a thread, also allows to reply to it
     *
     * @Route("/{threadId}", name="admin_message_thread_view")
     * @param strind $threadId the thread id
     * @return Response
     */
    public function threadAction($threadId)
    {
        
        $threads = $this->getProvider()->getInboxThreads();
        $forms = $this->generateFormsToThreads($threads);
        
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
            'forms' => $forms,
            'threads' => $threads
        ));
    }

    /**
     * Displays the authenticated participant sent mails
     * @Route("/sent", name="admin_message_sent")
     * @return Response
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreads();
        $forms = $this->generateFormsToThreads($threads);
        
        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:sent.html.twig', array(
            'threads' => $threads,
            'forms' => $forms
        ));
    }
    
    /**
     * Create a new message thread
     * @Route("/new", name="admin_message_thread_new")
     * @return Response
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData()
        ));
    }

    /**
     * Deletes a thread
     * @Route("/{threadId}/delete", name="admin_message_thread_delete")
     * @Method("post")
     * @return Response
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('admin_messages_inbox'));
    }

    /**
     * Searches for messages in the inbox and sentbox
     * @Route("/search", name="admin_message_search")
     * @return Response
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:search.html.twig', array(
            'query' => $query,
            'threads' => $threads
        ));
    }

    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
}
