<?php
date_default_timezone_set("Asia/Taipei");
$o_cassandra = Cassandra::cluster()
        ->withContactPoints('192.168.2.208')
        ->withConnectionsPerHost(6, 8)
        ->withIOThreads(25)
        ->build();
$s_server_keyspace = 'cassandra_tests';

$session = $o_cassandra->connect($s_server_keyspace);

$s_date_time = date('Y-m-d H:i:s');

$statement = "CREATE TABLE if not exists test_table (s_thekey text, s_column1 text, s_column2 text,PRIMARY KEY (s_thekey));";
$session->execute(new \Cassandra\SimpleStatement($statement));

if($argv[1] > 0) {
        $maximum = $argv[1]; echo 'Maximum Opertions: ' . $maximum . "\n";
}else {
        $maximum = 50000; echo 'Maximum Opertions: ' . $maximum . "\n";   
}

for($loop = 0; $loop < $argv[2]; $loop++){
    $i_start_time = microtime(true);
    //warm up the testing
    if($loop == 0) {
        $maximum = 50000;
        $op = $loop+1;
        echo 'Opertion '.$op.': ' . $maximum . "\n";
    }else{
        $maximum = $argv[1];
        $op = $loop+1;
        echo "\n".'Opertion '.$op.': ' . $maximum . "\n";
    }
    echo 'Inserting...';
    
    for ($i_bucle = 0; $i_bucle < $maximum; $i_bucle++) {
        // Add a sample text, let's use time for example
        set_time_limit(0);
        $s_time = strval(time());

        $statement = "INSERT INTO test_table (s_thekey, s_column1, s_column2)
    VALUES ('$i_bucle', '$s_time', 'www.npartnertech.com');";

        $session->execute(new \Cassandra\SimpleStatement($statement));
        //$i_pending_time = microtime(true);
        //$avg=$i_bucle / ($i_pending_time - $i_start_time);
        //echo $statement.'Avg Execution time: ' . $avg . "\n";
    }

$i_finish_time = microtime(true);

$i_execution_time = $i_finish_time - $i_start_time;
$avg= $maximum / ($i_execution_time);

echo "\n" . 'Execution time: ' . $i_execution_time . ' sec'. "\n" . 'Avg Execution time: ' . $avg. ' per sec' . "\n";
echo "\n";
}

$session->close();

