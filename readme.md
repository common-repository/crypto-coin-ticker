# crypto-coin-ticker

> Display a list of prices for all your favorite cryptocurrencies like Bitcoin, Ethereum, Litecoin and more!

## Description

Display a list of prices for all your favorite cryptocurrencies like Bitcoin, Ethereum, Litecoin and more! Reads from the CoinMarketCap.com API which provides updated prices for over 1300 cryptocurrencies and altcoins. This plugin will cache the results on your site for 2 minutes, to avoid unnecessary bandwidth usage.

Now the Crypto Coin Ticker includes an optional "Crypto Wallet Calculator" Dashboard Widget. Enter the amount of your cryptocurrencies in the Settings page, and the plugin will use the Currency setting to automatically calculate the value of all your coins. Quickly review the value of your cryptocurrency portfolio at a glance with this convenient WP Dashboard Widget.

Options:
* Color schemes: 'light' and 'dark'
* Currency: Choose from over 30 global currencies to display the coin prices
* Coin Icons: Show or Hide
* Time Interval for Percent Change: 1 Hour, 24 Hours, 7 Days
* Completely customize the CSS styles
* "Crypto Wallet Calculator" Dashboard Widget: Show or Hide
* "Crypto Wallet Calculator" Dashboard Widget: Enter coins and amounts to be calculated
* more coming soon!

## Installation

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/crypto-coin-ticker` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > Crypto Coin Ticker and enter some Cryptocurrency/Altcoin Market Symbols into the field. Hit 'Save' when finished.
4. To embed the price list in a page or post, use the shortcode: [ccticker]
5. To display the Dashboard Widget, ensure the Setting for "Show 'Crypto Wallet Calculator' on WP Dashboard" is set to "Yes". Enter a coin symbol and a coin amount as shown in the Example, and save your settings. You should see the Dashboard Widget when you go to your WP Dashboard.

## Frequently Asked Questions

### How do I get the ticker to show up?
Use the shortcode: [ccticker] and the Crypto Coin Ticker will appear in your post or page.

### Why don't I don't see a list of coin prices?
You need to assign some coins in the Settings area before they will appear in the Crypto Coin Ticker. Go to Settings > Crypto Coin Ticker and enter some Coin Index Symbols into the 'Coins to Track' field, and hit Save.

### Something else?
If you are having any other issues, please post in the Support Forum and I will respond as soon as possible.

### I love this plugin! How can I donate? Do you accept BTC, ETH, LTC? =
Thanks! Your donations help support the continued development and improvement of this plugin.

Donate Bitcoin:
1CqA7YFC5UuyUvtEnZGLnXk7oQEDWie1rq

Donate Etherium:
0x89995f7a6C11B279a7D4313613Ae90aB3CC8a5c1

Donate Litecoin:
LewdzRvRFxWNR9eLGE9Th6gvbfSMEfJ2uL

## Changelog

### 1.0.7 - (Nov 15, 2018) =
* Added Trade option
* Added a search coins box in the Widget

### 1.0.6 - (Feb 28, 2018)
* Fix: Fixed missing icon code showing in ticker list. Due to recent changes to the CoinMarketCap website, icons have temporarily been removed until a fix can be found.

### 1.0.5 - (Feb 2, 2018)
* Added new Currency: 'Satoshi'

### 1.0.4 - (Jan 19, 2018)
* Added new optional 'Crypto Wallet Calculator' Dashboard Widget. Enter the amount of your cryptocurrencies in the Settings page, and easily see the value of them all at a glance with this convenient WP Dashboard Widget. You'll be getting that Lambo in no time!

### 1.0.3 - (Jan 17, 2018)
* Added new 'Currency' option. Now coin prices can be displayed in any of the 32 supported global currencies, including: USD, EUR, GBP, CAD, JPY and more!
* Added new 'Show Icons' option. Now you can choose to Show or Hide the coin icons which are displayed next to each coin name.
* Added new 'Percent Change Interval' option. Now you can select the amount of time the percentage next to each coin indicates: 1 Hour, 24 Hours, or 7 Days.

### 1.0.2 - (Dec 28, 2017)
* Added message for when no Ticker options have been saved.

### 1.0.1 - (Dec 21, 2017)
* First release. Hello, World!
