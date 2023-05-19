<?php

namespace App\Tests\Behat;

use App\Entity\Post;
use App\Service\BlogService;
use App\Service\ElasticService;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \Mockery as m;

class BlogServiceContext extends m\Adapter\Phpunit\MockeryTestCase implements Context
{
    private EntityManagerInterface|m\MockInterface $entityManager;
    private ElasticService|m\MockInterface $elasticService;
    private BlogService $blogService;
    private Post $post;
    private m\Expectation $persistExpectation;

    /**
     * @Given a mock of EntityManagerInterface
     */
    public function aMockOfEntityManagerInterface()
    {
        $this->entityManager = m::mock(EntityManagerInterface::class);
    }

    /**
     * @Given a mock of ElasticService
     */
    public function aMockOfElasticService()
    {
        $this->elasticService = m::mock(ElasticService::class);
    }

    /**
     * @Given an instance of Post
     */
    public function anInstanceOfPost()
    {
        $this->post = new Post();
        $this->post->setTile('post title');
        $this->post->setBody('post body');
        $this->post->setPublished(true);
    }

    /**
     * @When newPost is Called
     */
    public function newPostIsCalled()
    {
        //$this->entityManager->expects($this->exactly(1))->method('persist');

        $this->entityManager->expects('persist')->once();
        $this->entityManager->expects('flush')->once();
        $this->elasticService->expects('index')->once();

        $this->blogService = new BlogService($this->entityManager, $this->elasticService);
        $this->blogService->newPost($this->post);
    }

    /**
     * @Then executing persist method :arg1 time
     */
    public function executingPersistMethodTime($arg1)
    {
    }

    /**
     * @Then executing flush method :arg1 time
     */
    public function executingFlushMethodTime($arg1)
    {
        //$this->entityManager->expects($this->once())->method('flush');
    }

    /**
     * @Then executing index method :arg1 time
     */
    public function executingIndexMethodTime($arg1)
    {
        m::close();
    }
}
