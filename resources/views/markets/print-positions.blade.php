<pre>
    @php

    try {
        $result=$bybit->privates()->getWalletBalance();
        $filtered = collect($result['result'])->filter(function ($value, $key) {
            return $value['wallet_balance'] > 0;
        });
        echo "<h2>Wallet Balance</h2>" . PHP_EOL;
        print_r($filtered);
    } catch (\Exception $e){
        print_r($e->getMessage());
    }

    // try {
    //     $result=$bybit->privates()->getFundingPrev([
    //         'symbol'=>'XRPUSDT',
    //     ]);
    //     echo "<h2>getFundingPrev</h2>" . PHP_EOL;
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }
    //
    // try {
    //     $result=$bybit->privates()->getFundingPredicted([
    //         'symbol'=>'XRPUSDT',
    //     ]);
    //     echo "<h2>getFundingPredicted</h2>" . PHP_EOL;
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }999.99987471

    try {
        $result=$bybit->privates()->getTradeExecutionList([
            'symbol' => 'KDAUSDT',
        ]);
        echo "<h2>getTradeExecutionList</h2>" . PHP_EOL;
        $filtered = collect($result['result'])->filter(function ($value, $key) {
            return 1;// $value['data']['*.order_status'] != 'Cancelled';
        });
        print_r($filtered);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    try {
        $result=$bybit->privates()->getStopOrderList([
            'symbol' => 'KDAUSDT',
        ]);
        echo "<h2>getStopOrderList</h2>" . PHP_EOL;
        $filtered = collect($result['result'])->filter(function ($value, $key) {
            return 1;// $value['data']['*.order_status'] != 'Cancelled';
        });
        print_r($filtered);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    try {
        $result=$bybit->privates()->getOrderList([
            'symbol' => 'KDAUSDT',
            'order_status' => 'Created,New'
        ]);
        echo "<h2>getOrderList</h2>" . PHP_EOL;
        $filtered = collect($result['result'])->filter(function ($value, $key) {
            return 1;// $value['data']['*.order_status'] != 'Cancelled';
        });
        print_r($filtered);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    try {
        $result=$bybit->privates()->getPositionList();
        echo "<h2>getPositionList</h2>" . PHP_EOL;
        $records = collect($result['result'])->filter(function ($value, $key) {
            return $value['data']['size'] > 0;
        });
        print_r($records);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }


    try {
        $result=$bybit->privates()->postPositionTradingStop([
            'symbol'=>'KDAUSDT',
        ]);
        echo "<h2>postPositionTradingStop</h2>" . PHP_EOL;
        print_r($result);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }


    @endphp
</pre>
