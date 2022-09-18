<?php

namespace App\DataFixtures;

use App\Constants\InvitationConstant;
use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $user1 = new User();
    $user1->setName('user1');
    $user1->setEmail('user1@example.com');
    $user1->setToken('user1_token');
    $manager->persist($user1);

    $user2 = new User();
    $user2->setName('user2');
    $user2->setEmail('user2@example.com');
    $user2->setToken('user2_token');
    $manager->persist($user2);

    $invitation = new Invitation();
    $invitation->setStatus(InvitationConstant::PENDING);
    $invitation->setSender($user1);
    $invitation->setReceiver($user2);
    $manager->persist($invitation);

    $manager->flush();
  }
}
