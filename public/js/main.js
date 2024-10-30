var crypto_coin_ticker_coins;

jQuery(document).ready(function ($) {
  crypto_coin_ticker_coins = crypto_coin_ticker.coins_list;
  var $searchInput = $('#search-coin');
  var $searchResult = $searchInput.parents('.crypto-coin-ticker-footer').find('.search-result');
  $searchInput.autocomplete({
    lookup: $.map(crypto_coin_ticker_coins, function (dataItem, index) {
      return {
        value: dataItem.name,
        data: index
      };
    }),
    onSelect: function (suggestion) {
      var $actualCoin = $searchResult.find('.coin')
      if ($actualCoin.length) {
        $actualCoin.fadeOut(100, function () {
          $actualCoin.remove();
          $coin = generateCoinElement(crypto_coin_ticker_coins[suggestion.data]).hide();
          $searchResult.append($coin);
          $coin.fadeIn(100);
        });
      } else {
        $coin = generateCoinElement(crypto_coin_ticker_coins[suggestion.data]).hide();
        $searchResult.append($coin);
        $coin.fadeIn(100);
      }


      console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
    }
  });
});

function generateCoinElement(coin) {

  var html = '';
  var changeSign = '+';
  var changeClass = 'positive';
  var negStr = '-';
  var coinLink = 'https://coinmarketcap.com/currencies/' + coin.id;
  var coinTradeLink = "https://zwaply.com/exchange/?source_coin=" + coin.symbol;



  affiliate_id = crypto_coin_ticker.zwaply_affiliate_id;
  if (affiliate_id) {
    coinTradeLink += "&affiliate_id=" + affiliate_id;
  }

  switch (crypto_coin_ticker.pctchangeinterval) {
    case 'percent_change_1h':
      coinPctChange = coin.percent_change_1h + '%';
      break;
    case 'percent_change_24h':
      coinPctChange = coin.percent_change_24h + '%';
      break;
    case 'percent_change_7d':
      coinPctChange = coin.percent_change_7d + '%';
      break;
    default:
      coinPctChange = coin.percent_change_1h + '%';
      break;
  }

  if (coinPctChange.indexOf(negStr) !== -1) {
    changeSign = '';
    changeClass = 'negative';
  }

  coinPriceData = '';
  if (crypto_coin_ticker.currency == 'USD') {
    coinPriceData = coin.price_usd;
    priceNum = parseFloat(coinPriceData);
    if (priceNum > 1.0) {
      // Round to 2 decimal places after zero
      priceNum = parseFloat(priceNum.toFixed(2));
    } else {
      // Round to 5 decimal places after zero
      priceNum = parseFloat(priceNum.toFixed(5));
    }
    coinPriceData = priceNum;
  } else if (crypto_coin_ticker.currency == 'Satoshi') {

    // 1 Bitcoin = 100m Satoshi
    coinPriceData = coin.price_btc;
    priceNum = parseFloat(coinPriceData);
    priceNum = priceNum * 100000000;
    if (priceNum > 1.0) {
      // Round to 2 decimal places after zero
      priceNum = parseFloat(priceNum.toFixed(2));
    } else {
      // Round to 5 decimal places after zero
      priceNum = parseFloat(priceNum.toFixed(5));
    }
    coinPriceData = priceNum;
  } else {
    lcCurrency = crypto_coin_ticker.currency.toLowerCase();
    coinPriceKey = 'price_'.lcCurrency; // should be: price_eur

    for (var key in coin) {
      if (key.indexOf(coinPriceKey) !== -1) {
        priceNum = parseFloat(coin[key]);
        if (priceNum > 1.0) {
          // Round to 2 decimal places after zero
          priceNum = parseFloat(priceNum.toFixed(2));
        } else {
          // Round to 5 decimal places after zero
          priceNum = parseFloat(priceNum.toFixed(5));
        }
        coinPriceData = priceNum;
      }
    }
  }

  currencySymbol = {
    USD: '$',
    AUD: 'A$',
    BRL: 'R$',
    CAD: 'C$',
    CHF: 'Fr',
    CLP: '$',
    CNY: '¥',
    CZK: 'Kč',
    DKK: 'kr',
    EUR: '€',
    GBP: '£',
    HKD: '$',
    HUF: 'Ft',
    IDR: 'Rp',
    ILS: '₪',
    INR: '₹',
    JPY: '¥',
    KRW: '₩',
    MXN: '$',
    MYR: 'RM',
    NOK: 'kr',
    NZD: '$',
    PHP: '₱',
    PKR: '₨',
    PLN: 'zł',
    RUB: '₽',
    SEK: 'kr',
    SGD: '$',
    THB: '฿',
    TRY: 'Kr',
    TWD: 'NT$',
    ZAR: 'R',
    Satoshi: 's',
  };

  // * Determine which currency symbol to use
  $currencyChar = '';
  if (currencySymbol[crypto_coin_ticker.currency]) {
    currencyChar = currencySymbol[crypto_coin_ticker.currency];
  }

  coinPrice = currencyChar + coinPriceData;


  html += '<div class="coin ' + coin.symbol + '"><a href="' + coinLink + '" target="_blank" rel="nofollow">';
  html += '<div class="name">' + coin.name + ' <span class="symbol">(' + coin.symbol + ')</span></div>';
  html += '<div class="price">';
  html += coinPrice + '<div class="changepct ' + changeClass + '">' + changeSign + coinPctChange + '</div>';

  if (crypto_coin_ticker.show_trade_button !== 'No') {
    tradeButtonText = crypto_coin_ticker.trade_button_text;
    tradeButtonText = tradeButtonText ? tradeButtonText : 'Trade';
    html += '<button class="trade" onclick="event.stopPropagation(); window.open(\'' + coinTradeLink + '\'); return false;">' + tradeButtonText + '</button>';
  }

  html += '</div>';
  html += '</a></div>' + "\n";

  return jQuery(html);

}