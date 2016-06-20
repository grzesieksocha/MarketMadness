Feature: Create Portfolio
  In order to create a new portfolio
  As a web user
  I need to be able to set up a name, choose initial value and a difficulty

  @mink:symfony2
  Scenario: Creating a valid portfolio
    Given I am on "/portfolio/new"
    When I fill in "portfolio_form_name" with "BehatPortfolio"
    And I select "10,000$" from "portfolio_form_initialCashAmount"
    And I select "easy" from "portfolio_form_difficulty"
    And I click "Add"
    Then I should see "Manage BehatPortfolio"