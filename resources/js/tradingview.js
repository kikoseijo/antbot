import { createChart, CrosshairMode, LineStyle} from 'lightweight-charts';
import { getEndDateFromStartDateForLimit1000, getKline} from "./binance";

var container = document.getElementById('tv-grid-edit');
var my_symbol = container.getAttribute('symbol');
const my_timeframe = '1h';
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
        text: my_symbol + ' ' + my_timeframe,
        fontSize: 100,
        color: "rgba(256, 256, 256, 0.1)",
        visible: true
    }
});

const candlestickSeries = chart.addCandlestickSeries();
const limit = 1000;
const startDate = getEndDateFromStartDateForLimit1000(
    my_timeframe,
    new Date(),
    limit
);

getKline(my_symbol, my_timeframe, startDate, new Date().getTime(), limit)
.then((data) => {
    candlestickSeries.setData(data);
    addLinesToChart();
    configureChartPriceBar(data);
});

function addLinesToChart()
{

    const grids = [
        [1.0, 0.3886, "long_ientry_normal", 1.0, 0.3886, 0.003886],
        [2.0, 0.3724, "long_rentry", 3.0, 0.3778, 0.011334],
        [6.0, 0.3621, "long_rentry", 9.0, 0.3673333333333333, 0.03306],
        [18.0, 0.3521, "long_rentry", 27.0, 0.35717777777777776, 0.09643799999999998],
        [53.0, 0.3423, "long_rentry", 80.0, 0.34732125, 0.277857],
        [156.0, 0.3329, "long_rentry", 236.0, 0.3377885593220339, 0.7971809999999999],
        [63.0, 0.3237, "long_rentry", 299.0, 0.33482006688963206, 1.0011119999999998]
    ];


    var color, text;
    for (var i = 0; i < grids.length; i++) {
        const grid = grids[i]
        if (grid[2] === 'long_ientry_normal') {
            color = '#0D9488';
            text = 'Initial entry';
        } else {
            color = '#FFD152';
            text = 'Re entry ' + i;
        }
        createLine(parseFloat(grid[1]), color, text);
    }
}

function createLine(price, color, text)
{
    console.log(price, color, text);
    var minPriceLine = {
        price: price,
        color: color,
        lineWidth: 2,
        lineStyle: LineStyle.Solid,
        axisLabelVisible: true,
        title: text,
    };
    candlestickSeries.createPriceLine(minPriceLine);
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
    const precision = calculatePrecision(data[data.length - 1].close);
    const minMove = calculateMinMove(precision);
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
