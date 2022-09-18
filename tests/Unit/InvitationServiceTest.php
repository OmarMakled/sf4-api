<?php
namespace App\Tests\Unit;

use App\Constants\InvitationConstant;
use App\Entity\Invitation;
use App\Entity\User;
use App\Service\InvitationService;
use App\Tests\AbstractTest;

/**
 * @group unit
 * @author Omar Makled <omar.makled@gmail.com>
 */
class InvitationServiceTest extends AbstractTest
{
  private $invitationService;

  private $invitationRepo;

  private $userRepo;

  protected function setUp(): void
  {
    parent::setUp();
    $this->invitationRepo = $this->em->getRepository(Invitation::class);
    $this->userRepo = $this->em->getRepository(User::class);

    $this->invitationService = (new InvitationService(
      $this->em,
      $this->invitationRepo,
      $this->userRepo
    ));
  }

  public function testCreateInvitation(): void
  {
    $this->invitationService->create(
      $this->userRepo->findOneBy(['email' => 'user1@example.com']),
      'user2@example.com'
    );

    $this->assertEquals($this->invitationRepo->Count([]), 2);
  }

  public function testAccepteInvitation(): void
  {
    $this->invitationService->update(
      $this->userRepo->findOneBy(['email' => 'user2@example.com']),
      $this->invitationRepo->findOneBy([])->getId(),
      InvitationConstant::ACCEPTED
    );

    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->assertEquals($invitation->getStatus(), InvitationConstant::ACCEPTED);
  }

  public function testDeclineInvitation(): void
  {
    $this->invitationService->update(
      $this->userRepo->findOneBy(['email' => 'user2@example.com']),
      $this->invitationRepo->findOneBy([])->getId(),
      InvitationConstant::DECLINED
    );

    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->assertEquals($invitation->getStatus(), InvitationConstant::DECLINED);
  }

  public function testCancelInvitation(): void
  {
    $this->invitationService->cancel(
      $this->userRepo->findOneBy(['email' => 'user1@example.com']),
      $this->invitationRepo->findOneBy([])->getId(),
      InvitationConstant::DECLINED
    );

    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->assertEquals($invitation->getStatus(), InvitationConstant::CANCELED);
  }
}
