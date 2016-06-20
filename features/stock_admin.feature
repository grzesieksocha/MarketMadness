Feature: Stock Admin Area
  In order to maintain the stocks which are tradeable in the app
  As an admin user
  I need to be able to add/edit/delete stocks

  Scenario: List available stocks
    Given I am on "/admin/home"
    And there are 15 stocks
    When I click "List Yahoo Stock"
    Then I should see 15 stocks

  Scenario: Add a new stock
    Given I am on "/admin/home"
    When I click "Add Yahoo Stock"
    And I fill in "Symbol" with "Stocker"
    And I fill in "Description" with "Stocker Valuable Stock"
    And I press "Add"
    Then I should see "New Stock Added"

