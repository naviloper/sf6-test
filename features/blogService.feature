Feature: BlogService
  In order to ...


  Scenario: adding new post
    Given a mock of EntityManagerInterface
    And a mock of ElasticService
    And an instance of Post
    When newPost is Called
    Then executing persist method 1 time
    And executing flush method 1 time
    And executing index method 1 time
