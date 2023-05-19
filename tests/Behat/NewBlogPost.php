<?php

namespace App\Tests\Behat;

use App\Entity\Post;
use App\Service\BlogService;
use App\Service\ElasticService;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
USE Mockery as m;

class NewBlogPost implements Context
{
    private BlogService $blogService;
    private EntityManagerInterface|m\MockInterface $entityManager;
    private ElasticService|m\MockInterface $elasticService;
    private Post $post;

  /**
   * @Given I have an instance of BlogService
   */
  public function iHaveAnInstanceOfBlogService()
  {
      $this->entityManager = m::mock(EntityManagerInterface::class);
      $this->elasticService = m::mock(ElasticService::class);

      $this->entityManager->allows('persist');
      $this->entityManager->allows('isOpen')->andReturn(true);
      $this->entityManager->allows('flush');
      $this->elasticService->allows('index');

      $this->blogService = new BlogService($this->entityManager, $this->elasticService);
  }

      /**
       * @Given a mock instance of EntityManagerInterface
       */
      public function aMockInstanceOfEntityManagerInterface()
      {
          // No need to implement anything for the mock instance
      }

      /**
       * @Given a mock instance of ElasticService
       */
      public function aMockInstanceOfElasticService()
      {
          // No need to implement anything for the mock instance
      }

      /**
       * @When I call the :method method with a Post object
       */
      public function iCallTheMethodWithAPostObject($method)
      {
        $this->post = new Post();
        $this->blogService->$method($this->post);
      }

      /**
       * @Then the Post object should be persisted :arg1 time
       */
      public function thePostObjectShouldBePersisted(int $arg1)
      {
        $this->entityManager->shouldHaveReceived('persist')->times($arg1)->with($this->post);
        $this->entityManager->shouldNotHaveReceived('isOpen');
      }

      /**
       * @Then the EntityManager should be flushed :arg1 time
       */
      public function theEntityManagerShouldBeFlushed(int $arg1)
      {
        $this->entityManager->shouldHaveReceived('flush')->times($arg1);
      }

      /**
       * @Then the Post object should be indexed in ElasticSearch :arg1 time
       */
      public function thePostObjectShouldBeIndexedInElasticSearch(int $arg1)
      {
        $this->elasticService->shouldHaveReceived('index')->times($arg1)->with($this->post);
      }

}
