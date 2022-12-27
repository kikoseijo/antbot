import { createChart, CrosshairMode, LineStyle} from 'lightweight-charts';
import { getEndDateFromStartDateForLimit1000, getKline} from "./binance";

var container = document.getElementById('tv-grid-edit');
var my_symbol = container.getAttribute('symbol');
console.log(my_symbol);

// https://github.com/enarjord/passivbot/blob/master/docs/passivbot_modes.md

function calculatePrecision(price)
{
    if (price > 0.09) {
        return 2;
    } else if (price > 0.01) {
        return 3;
    } else if (price > 0.001) {
        return 4;
    } else if (price > 0.0001) {
        return 5;
    } else if (price > 0.00001) {
        return 6;
    } else if (price > 0.000001) {
        return 7;
    }
}

function calculateMinMove(precision){
    var res = 10;
    for (var i = 0; i < precision; i++) {
        res *= res;
    }
    return 1 / res;
}


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
        text: "XYZ",
        fontSize: 256,
        color: "rgba(256, 256, 256, 0.1)",
        visible: true
    }
});

const candlestickSeries = chart.addCandlestickSeries();
const limit = 1000;
const startDate = getEndDateFromStartDateForLimit1000(
    '1h',
    new Date(),
    limit
);
const data = getKline(
    my_symbol,
    '1h',
    startDate,
    new Date().getTime(),
    limit
);
candlestickSeries.setData(data);

var series = chart.addLineSeries({
	color: 'rgb(0, 120, 255)',
	lineWidth: 2,
	crosshairMarkerVisible: false,
	lastValueVisible: true,
	priceLineVisible: true,
});

var minPriceLine = {
	price: data[data.length - 1].close,
	color: '#0D9488',
	lineWidth: 2,
	lineStyle: LineStyle.Solid,
	axisLabelVisible: true,
	title: 'Position entry',
};

candlestickSeries.createPriceLine(minPriceLine);

// const myPriceFormatter = p => Number.parseFloat(p).toFixed(2);
// chart.applyOptions({
//     localization: {
//         priceFormatter: myPriceFormatter,
//     },
// });




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

const precision = calculatePrecision(data[data.length - 1].close);
const minMove = calculateMinMove(precision);

console.log(precision);
console.log(data[0].close);
console.log(data[data.length - 1].close);

candlestickSeries.applyOptions({
    priceFormat: {
        type: 'price',
        precision: precision,
        minMove: minMove,
    },
});

chart.timeScale().fitContent();
