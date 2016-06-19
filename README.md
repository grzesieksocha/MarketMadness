MarketMadness
=============

Check if you are a good investor with this stock exchange based game!
Buy and sell stock using your virtual money and compere your return rate with other users!

Installation
============
Install composer dependencies (with globally installed composer)

'''

composer install

'''

For page to work properly upload basic stocks to follow 

'''
php app/console doctrine:fixtures:load
'''

Update the database with the most recent Yahoo stock data

'''
php app/console mm:getData
'''

Example CRON job downloading stock data every 10 minutes:

'''
*/10    14,15,16,17,18,19,20,21,22,23   *       *       *       your/php/path your/symfony/console/path mm:getData
'''

Developement in progress
========================
###To implement:
**Most important - error handling**
**Next: security / validation**
**TESTS**
**Give Admin MOAR power!**

###Ideas:
**Commenting shared transactions**

###Yahoo downloadable data fields:
"change" : "-1.580002",
"chg_percent" : "-3.363853",
"day_high" : "45.599998",
"day_low" : "44.250000",
"name" : "Citigroup, Inc. Common Stock",
"price" : "45.389999",
"symbol" : "C",
"utctime" : "2016-06-03T20:00:18+0000",
"year_high" : "60.950000",
"year_low" : "34.520000"