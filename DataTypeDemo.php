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

$statement = "CREATE TABLE if not exists datatype_test_table (id bigint, date timestamp, name text, age_weight tuple<int, float> ,
        bk_data list<text>,office set<text>,events map<int, text>,PRIMARY KEY (id));";
$session->execute(new \Cassandra\SimpleStatement($statement));

$statement = "INSERT INTO datatype_test_table(id, date, name, age_weight, bk_data, office,events)
        VALUES (2016, $s_date_time,'kevin',(32,77.7),['single','no kids'],{'npartner','kevin@npartnertech.com'},{2018:'www.npartnertech.com'});";

$session->execute(new \Cassandra\SimpleStatement($statement));


