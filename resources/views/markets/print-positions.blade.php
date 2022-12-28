<pre>
    @php

    try {
        $result=$bybit->privates()->getWalletBalance();
        echo "<h2>Wallet Balance</h2>" . PHP_EOL;
        print_r($result);
    } catch (\Exception $e){
        print_r($e->getMessage());
    }

    try {
        $result=$bybit->privates()->getFundingPrev([
            'symbol'=>'XRPUSDT',
        ]);
        echo "<h2>getFundingPrev</h2>" . PHP_EOL;
        print_r($result);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    try {
        $result=$bybit->privates()->getFundingPredicted([
            'symbol'=>'XRPUSDT',
        ]);
        echo "<h2>getFundingPredicted</h2>" . PHP_EOL;
        print_r($result);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    // try {
    //     $result=$bybit->privates()->getFundingPrevRate([
    //         'symbol'=>'BTCUSD',
    //     ]);
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    try {
        $result=$bybit->privates()->getPositionList();
        echo "<h2>getPositionList</h2>" . PHP_EOL;
        print_r($result);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    // try { // No funciona
    //     $result=$bybit->privates()->postChangePositionMargin([
    //         'symbol'=>'BTCUSDT',
    //         'margin'=>'1'
    //     ]);
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    try {
        $result=$bybit->privates()->postPositionTradingStop([
            'symbol'=>'BTCUSDT',
        ]);
        echo "<h2>postPositionTradingStop</h2>" . PHP_EOL;
        print_r($result);
    }catch (\Exception $e){
        print_r($e->getMessage());
    }

    // try {  // No funciona.
    //     $result=$bybit->privates()->getUserLeverage();
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    // try {
    //     $result=$bybit->privates()->postUserLeverageSave([
    //         'symbol'=>'BTCUSDT',
    //         'leverage'=>'1'
    //     ]);
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    // try {
    //     $result=$bybit->privates()->getExecutionList([
    //         'symbol'=>'BTCUSDT',
    //     ]);
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }
    @endphp
</pre>
