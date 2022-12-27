import dayjs from "dayjs";

const base = "https://api.binance.com";
const klineUri = "/api/v3/klines";
// const tickerPriceUri = '/api/v3/ticker/price'

// response example
// [
//   1499040000000,      // Open time
//   "0.01634790",       // Open
//   "0.80000000",       // High
//   "0.01575800",       // Low
//   "0.01577100",       // Close
//   "148976.11427815",  // Volume
//   1499644799999,      // Close time
//   "2434.19055334",    // Quote asset volume
//   308,                // Number of trades
//   "1756.87402397",    // Taker buy base asset volume
//   "28.46694368",      // Taker buy quote asset volume
//   "17928899.62484339" // Ignore.
// ]

// type TimeFrame = '5m' | '15m' | '30m' | '1h' | '4h' | '1d' | '1w' | '1m'

/**
 *
 * @param {string} timeframe eg. 1m, 5m, 30m, 1h, 4h etc.
 * @param {number} date number of date in milli second
 * @returns new date that is added 1 time frame to it then return in milli second
 */

/**
 *
 * @param {string} timeframe 1m, 5m, 15m 1h, 1d, 1w etc.
 * @returns {number} milli second of 1 time frame
 */
export function getMilliSecondFromTimeFrame(timeframe) {
  switch (timeframe) {
    case "5m":
      return 5 * 60 * 1000;
    case "15m":
      return 15 * 60 * 1000;
    case "30m":
      return 30 * 60 * 1000;
    case "1h":
      return 1 * 60 * 60 * 1000;
    case "4h":
      return 4 * 60 * 60 * 1000;
    case "1d":
      return 24 * 60 * 60 * 1000;
    case "1w":
      return 24 * 7 * 60 * 60 * 1000;
    case "1m":
    default:
      return 1 * 60 * 1000;
  }
}

/**
 *
 * @param {string} interval 1m, 5m, 15m, 30m, 1h, 4h, 1d, 1w
 * @param {number} start number of milli second of date
 * @param {number} limit default is 1000 limit should be number between 500 - 1000
 * @returns new date that added 1000 time frame from start date
 */
export function getEndDateFromStartDateForLimit1000(
  interval,
  start,
  limit = 1000
) {
  const startDate = new Date(start);

  switch (interval) {
    case "1m":
      startDate.setMinutes(startDate.getMinutes() - 1 * limit);
      break;
    case "5m":
      startDate.setMinutes(startDate.getMinutes() - 5 * limit);
      break;
    case "15m":
      startDate.setMinutes(startDate.getMinutes() - 15 * limit);
      break;
    case "30m":
      startDate.setMinutes(startDate.getMinutes() - 30 * limit);
      break;
    case "1h":
      startDate.setHours(startDate.getHours() - 1 * limit);
      break;
    case "4h":
      startDate.setHours(startDate.getHours() - 4 * limit);
      break;
    case "1d":
      startDate.setHours(startDate.getHours() - 24 * limit);
      break;
    case "1w":
      startDate.setHours(startDate.getHours() - 24 * 7 * limit);
      break;

    default:
      startDate.setMinutes(startDate.getMinutes() - 1 * limit);
      break;
  }

  return startDate.getTime();
}

/**
 * to be calculate number of request by limit 1000 candle of data from binance
 * @param {string} interval eg. 1m, 5m, 15m 30m, 1h, 4h, 1d, 1w
 * @param {number} start start date in milli second
 * @param {number} end end date in milli second
 * @returns {number} number of requests
 */
function numberOfRequest(interval, start, end) {
  let numberOfCandle = 0;
  const dateDiff = (end - start) / 1000 / 60; // now the unit of dateDiff is minute
  switch (interval) {
    case "5m":
      numberOfCandle = dateDiff / 5;
      break;
    case "15m":
      numberOfCandle = dateDiff / 15;
      break;
    case "30m":
      numberOfCandle = dateDiff / 30;
      break;
    case "1h":
      numberOfCandle = dateDiff / 60;
      break;
    case "4h":
      numberOfCandle = dateDiff / (60 * 4);
      break;
    case "1d":
      numberOfCandle = dateDiff / (60 * 24);
      break;
    case "1w":
      numberOfCandle = dateDiff / (60 * 24 * 7);
      break;
    case "1m":
    default:
      numberOfCandle = dateDiff;
      break;
  }
  let numberOfReq = numberOfCandle / 1000;
  numberOfReq = Math.ceil(numberOfReq);
  return numberOfReq;
}

/**
 *
 * @param {string} symbol in UPPER case
 * @param {string} interval 1m, 15m, 30m, 1h, 4h, 1d, 1w
 * @param {number} start in milli second
 * @param {number} end in milli second
 * @param {number} limit default value is 500 - 1000
 * @returns array of object of kline data
 */
export async function getKline(symbol, interval, start, end, limit = 1000) {
  symbol = symbol.toUpperCase();
  try {
    const params = `?symbol=${symbol}&interval=${interval}&startTime=${start}&endTime=${end}&limit=${limit}`;
    const response = await fetch(`${base}${klineUri}${params}`);
    const data = await response.json();
    const responseData = data.map((m) => {
      return {
        open: m[1],
        high: m[2],
        low: m[3],
        close: m[4],
        time: m[0] / 1000
      };
    });
    return responseData;
    } catch (error) {
      console.error(error);
    }
}
