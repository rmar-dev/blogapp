<?php
/**
 * Created by PhpStorm.
 * User: rmar2
 * Date: 02/04/2019
 * Time: 12:17
 */

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Security\UserConfirmationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{


    /**
     * @var UserConfirmationService
     */
    private $confirmationService;

    public function __construct(
        UserConfirmationService $confirmationService
    )
    {

        $this->confirmationService = $confirmationService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
        ];
    }

    public function confirmUser(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        if('api_user_confirmations_post_collection' !== $request->get('_route')){
            return;
        }

        $confirmationToken = $event->getControllerResult();

        $this->confirmationService->confirmUser(
             $confirmationToken->confirmationToken
        );

        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));

    }
}