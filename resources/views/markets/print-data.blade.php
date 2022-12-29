<pre>
    @php


    // try {
    //     $result=$bybit->publics()->getOrderBookL2([
    //         'symbol'=>'BTCUSDT'
    //     ]);
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    // try {
    //     $result=$bybit->publics()->getKline([
    //         'symbol'=>'BTCUSDT',
    //         'interval'=>'15',
    //         'from'=>time()-20,
    //     ]);
    //     echo "<h2>Kline</h2>" . PHP_EOL;
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    // try {
    //     $result=$bybit->publics()->getTickers();
    //     echo "<h2>getTickers</h2>" . PHP_EOL;
    //     print_r($result);
    // }catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    // try {
    //     $result=$bybit->publics()->getRecentTradingRecords([
    //         'symbol'=>'BTCUSDT',
    //         'limit'=>'5',
    //     ]);
    //     echo "<h2>getRecentTradingRecords</h2>" . PHP_EOL;
    //     print_r($result);
    // } catch (\Exception $e){
    //     print_r($e->getMessage());
    // }

    try {
        $result=$bybit->publics()->getSymbols();
        echo "<h2>getSymbols</h2>" . PHP_EOL;
        $records = collect($result['result']);
        $xrp = $records->where('name', 'BTCUSDT');
        print_r($xrp);
        // print_r(\Arr::get($result['result'],'XRP');
    } catch (\Exception $e){
        print_r($e->getMessage());
    }
@endphp

</pre>
