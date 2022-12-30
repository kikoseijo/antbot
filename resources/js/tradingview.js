import { createChart, CrosshairMode, LineStyle} from 'lightweight-charts';
import { getEndDateFromStartDateForLimit1000, getKline} from "./binance";

var container = document.getElementById('tv-grid-edit');
var my_symbol = container.getAttribute('symbol');
const interval = container.getAttribute('interval');
const orders = container.getAttribute('orders');
const entryOrder = container.getAttribute('entry-order');
const precision = container.getAttribute('precision');

console.log('[-- precision --]', precision);
const ordersObj =  JSON.parse(orders);
const entryOrderObj =  JSON.parse(entryOrder);



const chart = createChart(container, {
    layout: {
		textColor: '#D1D5DB',
		backgroundColor: '#111827',
	},
    grid: {
		vertLines: {
			color: "#334158",
		},
		horzLines: {
			color: "#334158",
		},
	},
	crosshair: {
		mode: CrosshairMode.Normal,
	},
    watermark: {
        text: my_symbol + ' ' + interval,
        fontSize: 100,
        color: "rgba(256, 256, 256, 0.1)",
        visible: true
    }
});

const candlestickSeries = chart.addCandlestickSeries();
const limit = 1000;
const startDate = getEndDateFromStartDateForLimit1000(
    interval,
    new Date(),
    limit
);

getKline(my_symbol, interval, startDate, new Date().getTime(), limit)
.then((data) => {
    candlestickSeries.setData(data);
    addLinesToChart();
    configureChartPriceBar(data);
});

function addLinesToChart()
{
    var color, text;
    for (var i = 0; i < ordersObj.length; i++) {
        const order = ordersObj[i]
        if (order['side'] === 'Buy') {
            color = '#FFD152';
            text = order['qty'] + ' Buy';
        } else {
            color = '#F1843E';
            text = order['qty'] + ' Sell';
        }
        createLine(parseFloat(order['price']), color, text);

    }
    const blue = '#3F83F8';
    const entryText = 'Avg. entry (' + entryOrderObj.qty + ')';
    createLine(parseFloat(entryOrderObj.price), blue, entryText);
}

function createLine(price, color, text)
{
    var minPriceLine = {
        price: price,
        color: color,
        lineWidth: 1,
        lineStyle: 1, // LineStyle.Dotted
        axisLabelVisible: true,
        title: text,
    };
    candlestickSeries.createPriceLine(minPriceLine);
}

function calculateMinMove(){
    var res = 1;
    for (var i = 0; i < precision; i++) {
        res = res / 10;
    }
    console.log('[-- res --]', res);
    return res;
}

function configureChartPriceBar(data)
{
    var indexOfMinPrice = 0;
    var indexOfMaxPrice = 0;
    for (var i = 1; i < data.length; i++) {
    	if (data[i].low < data[indexOfMinPrice].low) {
    		indexOfMinPrice = i;
    	}
    	if (data[i].high > data[indexOfMaxPrice].high) {
    		indexOfMaxPrice = i;
    	}
    }
    candlestickSeries.priceScale().applyOptions({
        autoScale: true, // disables auto scaling based on visible content
        mode: 0,
        scaleMargins: {
            top: data[indexOfMaxPrice].high * 1.2,
            bottom: data[indexOfMinPrice].low * 1.2,
        },
    });

    const minMove = calculateMinMove();
    candlestickSeries.applyOptions({
        priceFormat: {
            type: 'price',
            precision: precision,
            minMove: minMove,
        },
    });
    chart.timeScale().fitContent();
}




// const myPriceFormatter = p => Number.parseFloat(p).toFixed(2);
// chart.applyOptions({
//     localization: {
//         priceFormatter: myPriceFormatter,
//     },
// });
