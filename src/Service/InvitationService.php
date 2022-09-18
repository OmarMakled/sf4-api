<?php
namespace App\Service;

use App\Constants\InvitationConstant;
use App\Entity\Invitation;
use App\Entity\User;
use App\Repository\InvitationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvitationService
{
  /**
   * @var EntityManagerInterface
   */
  private $em;

  /**
   * @var InvitationRepository
   */
  private $invitationRepo;

  /**
   * @var UserRepository
   */
  private $userRepo;

  /**
   * UserService constructor.
   * @param EntityManagerInterface $em
   * @param InvitationRepository $invitationRepo
   * @param UserRepository $userRepo
   */
  public function __construct(EntityManagerInterface $em, InvitationRepository $invitationRepo, UserRepository $userRepo)
  {
    $this->em = $em;
    $this->invitationRepo = $invitationRepo;
    $this->userRepo = $userRepo;
  }

  /**
   * Create new invitation.
   *
   * @param User $user
   * @param string $email invited user email
   * @return int invitation id
   * @throws HttpException
   */
  public function create(User $user, string $email)
  {
    $to = $this->userRepo->findOneBy(['email' => $email]);
    if (!$to) {
      throw new HttpException(Response::HTTP_NOT_FOUND, "The user not found");
    }
    if ($to->getEmail() == $user->getEmail()) {
      throw new HttpException(Response::HTTP_NOT_FOUND, "Can not send invitation to same user");
    }
    $invitation = new Invitation;
    $invitation->setStatus(InvitationConstant::PENDING);
    $invitation->setSender($user);
    $invitation->setReceiver($to);
    $this->em->persist($invitation);
    $this->em->flush();

    return $invitation->getId();
  }

  /**
   * Update invitation
   *
   * @param User $user
   * @param integer $invitationId
   * @param string $status
   * @return void
   * @throws HttpException
   */
  public function update(User $user, int $invitationId, string $status)
  {
    $invitaion = $this->invitationRepo->getInvitedTo($user->getId(), $invitationId);
    $this->isValid($invitaion);
    if (!in_array($status, InvitationConstant::ALLOW)) {
      throw new HttpException(
        Response::HTTP_NOT_FOUND,
        sprintf("status not valid allow only %s", implode(', ', InvitationConstant::ALLOW))
      );
    }
    $invitaion->setStatus($status);
    $this->em->persist($invitaion);
    $this->em->flush();
  }

  /**
   * Cancel invitation
   *
   * @param User $user
   * @param integer $invitationId
   * @return void
   * @throws HttpException
   */
  public function cancel(User $user, int $invitationId)
  {
    $invitaion = $this->invitationRepo->getInvitedBy($user->getId(), $invitationId);
    $this->isValid($invitaion);
    $invitaion->setStatus(InvitationConstant::CANCELED);
    $this->em->persist($invitaion);
    $this->em->flush();
  }

  /**
   * Determine whether the invitation is valid.
   *
   * @param Invitation|null $invitaion
   * @return boolean
   * @throws HttpException
   */
  private function isValid(?Invitation $invitaion)
  {
    if (!$invitaion) {
      throw new HttpException(Response::HTTP_NOT_FOUND, "The invitation not found");
    }
    $status = $invitaion->getStatus();
    if ($invitaion->getStatus() != InvitationConstant::PENDING) {
      throw new HttpException(
        Response::HTTP_NOT_FOUND,
        sprintf("The invitation has already been %s before", $status)
      );
    }

    return true;
  }
}
