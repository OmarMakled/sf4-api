<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Invitation;
use App\Tests\AbstractTest;
use App\Tests\RollBackTrait;
use App\Constants\InvitationConstant;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 * @author Omar Makled <omar.makled@gmail.com>
 */
class InvitationControllerTest extends AbstractTest
{  
  public function testCreateOnSuccess(): void
  {
    $this->client->request(
      'POST', 
      '/api/invitation', 
      ['email' => 'user2@example.com'],
      [], 
      ['HTTP_X-API-TOKEN' => 'user1_token']
    );
    $this->assertResponseIsSuccessful();
  }

  public function testCancelOnSuccess(): void
  {
    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->client->request(
      'DELETE', 
      "/api/invitation/{$invitation->getId()}", 
      [''],
      [], 
      ['HTTP_X-API-TOKEN' => 'user1_token']
    );
    $this->assertResponseIsSuccessful();
  }

  public function testAcceptOnSuccess(): void
  {
    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->client->request(
      'PUT', 
      "/api/invitation/{$invitation->getId()}/status",
      ['status' => InvitationConstant::ACCEPTED],
      [], 
      ['HTTP_X-API-TOKEN' => 'user2_token']
    );
    $this->assertResponseIsSuccessful();
  }

  public function testDeclinedOnSuccess(): void
  {
    $invitation = $this->em->getRepository(Invitation::class)->findOneBy([]);
    $this->client->request(
      'PUT', 
      "/api/invitation/{$invitation->getId()}/status",
      ['status' => InvitationConstant::DECLINED],
      [], 
      ['HTTP_X-API-TOKEN' => 'user2_token']
    );
    $this->assertResponseIsSuccessful();
  }
}