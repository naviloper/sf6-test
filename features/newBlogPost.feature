Feature: Create a new blog post
  In order to add a new blog post
  As a user
  I want to use the BlogService to create a post

  Scenario: Creating a new blog post
    Given I have an instance of BlogService
    And a mock instance of EntityManagerInterface
    And a mock instance of ElasticService
    When I call the "newPost" method with a Post object
    Then the Post object should be persisted 1 time
    And the EntityManager should be flushed 1 time
    And the Post object should be indexed in ElasticSearch 1 time
