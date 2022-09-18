<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestListener
{
  /**
   * @var EntityManager
   */
  private $userRepo;

  /**
   * @param UserRepository $userRepo
   */
  public function __construct(UserRepository $userRepo)
  {
    $this->userRepo = $userRepo;
  }

  /**
   * Hook on kernel request.
   *
   * @param GetResponseEvent $event
   */
  public function onKernelRequest(GetResponseEvent $event)
  {
    $request = $event->getRequest();

    $this->getUser($request);
    if ($content = $request->getContent()) {
      $data = json_decode($content, true);
      $request->request = new ParameterBag($data);
    }
  }

  /**
   * Get user from token.
   *
   * @param Request $request
   * @return void
   * @throws HttpException
   */
  private function getUser(Request $request)
  {
    $token = $request->headers->get('x-api-token', '');
    $user = $this->userRepo->getByToken($token);
    if (!$user) {
      throw new HttpException(Response::HTTP_FORBIDDEN, "Forbidden");
    }

    $request->user = $user;
  }
}
