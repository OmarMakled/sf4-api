<?php

namespace App\Controller\Api;

use App\Service\InvitationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/invitation")
 */
class InvitationController extends AbstractController
{
  /**
   * @Route (methods="POST", name="create_invitation")
   *
   * @param Request $request
   * @param InvitationService $invitationService
   * @return JsonResponse
   * @throws HttpException
   */
  public function create(Request $request, InvitationService $invitationService)
  {
    $id = $invitationService->create($request->user, $request->get('email'));

    return new JsonResponse([
      'url' => "/api/invitation/{$id}",
    ], Response::HTTP_OK);
  }

  /**
   * @Route ("/{id}/status", methods="PUT", name="update_invitation")
   *
   * @param int $id Invitation id
   * @param Request $request
   * @param InvitationService $invitationService
   * @return JsonResponse
   * @throws HttpException
   */
  public function update(int $id, Request $request, InvitationService $invitationService)
  {
    $invitationService->update($request->user, $id, $request->get('status'));

    return new JsonResponse('', Response::HTTP_NO_CONTENT);
  }

  /**
   * @Route ("/{id}", methods="DELETE", name="cancel_invitation")
   *
   * @param int $id Invitation id
   * @param Request $request
   * @param InvitationService $invitationService
   * @return JsonResponse
   * @throws HttpException
   */
  public function cancel(int $id, Request $request, InvitationService $invitationService)
  {
    $invitationService->cancel($request->user, $id);

    return new JsonResponse('', Response::HTTP_NO_CONTENT);
  }
}
