<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Email\Mailer;
use App\Entity\User;

use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;
    /**
     * @var Mailer
     */
    private $mailer;


    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        Mailer $mailer
    )
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
      return [
        KernelEvents::VIEW => ['userRegister', EventPriorities::PRE_WRITE]
      ];
    }

    public function userRegister(GetResponseForControllerResultEvent $event)
    {
      $user = $event->getControllerResult();
      $method = $event->getRequest()->getMethod();

      if(!$user instanceof User || !in_array($method, [Request::METHOD_POST])){
        return;
      }

      $user->setPassword(
        $this->passwordEncoder->encodePassword($user, $user->getPassword())
      );

      //Create a confirmation token
        $user->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        //Send email here
        $this->mailer->sendConfirmationEmail($user);
    }
}