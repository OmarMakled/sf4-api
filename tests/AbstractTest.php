<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractTest extends WebTestCase
{
  /** @var Client $client */
  protected $client;
  /** @var EntityManagerInterface */
  protected $em;

  public static function setUpBeforeClass(): void
  {
    $kernel = static::createKernel();
    $kernel->boot();
    $em = $kernel->getContainer()->get('doctrine')->getManager();

    $loader = new Loader();
    foreach (self::getFixtures() as $fixture) {
      $loader->addFixture($fixture);
    }

    $purger = new ORMPurger();
    $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
    $executor = new ORMExecutor($em, $purger);
    $executor->execute($loader->getFixtures());
  }

  protected function setUp(): void
  {
    $this->client = static::createClient();
    $this->client->disableReboot();

    $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    $this->em->beginTransaction();
    $this->em->getConnection()->setAutoCommit(false);
  }

  protected function tearDown(): void
  {
    if ($this->em->getConnection()->isTransactionActive()) {
      $this->em->rollback();
    }
  }

  private static function getFixtures(): iterable
  {
    return [
      new AppFixtures(),
    ];
  }
}
