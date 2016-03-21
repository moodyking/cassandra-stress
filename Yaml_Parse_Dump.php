<?php
/**
 * Parsing callback for yaml tag.
 * @param mixed $value Data from yaml file
 * @param string $tag Tag that triggered callback
 * @param int $flags Scalar entity style (see YAML_*_SCALAR_STYLE)
 * @return mixed Value that YAML parser should emit for the given value
 */
//date_default_timezone_set("Asia/Taipei");
function lookup_array($array, $in_counter,$in_key) {
    for ($i = 0; $i < $in_counter; $i++) $dash = $dash . ' -';
    foreach ($array as $in_key => &$in_result){
        if((string)$in_key == 'seeds') $in_result ='192.168.2.209'; //修改seeds IP
        echo $dash.'  key'.$in_counter.'='.$in_key.' ; value'.$in_counter.'='.$in_result.'<br>';
        if(is_array($in_result)){
            $in_counter++; 
            lookup_array($in_result, $in_counter, $in_key);
        }
    }
}
$result = yaml_parse_file("cassandra.yaml");

foreach ($result as $key => &$value) {
    $counter = 1;
    echo 'key='.$key.' ; value='.$value.'<br>';
    if(is_array($value)){
        lookup_array($value, $counter, $key);
    }
    /*echo 'key='.$key.' ; value='.$value.'<br>';
    if($key == 'seed_provider'){  //<blockquote>
        foreach($value as $key => &$value2)
            echo '- key2='.$key.' ; value2='.$value2.'<br>';
            foreach($value2 as $key => &$value3)
                echo '--  key3='.$key.' ; value3='.$value3.'<br>';
                if(is_array($value3)){
                    foreach($value3 as $key => &$value4)
                        echo '---   key4='.$key.' ; value4='.$value4.'<br>';
                        if(is_array($value4)){
                            foreach($value4 as $key => &$value5){
                                echo '----   key5='.$key.' ; value5='.$value5.'<br>';
                                $value4[$key] ='192.168.2.209';
                                echo '----   key5='.$key.' ; value5='.$value5.'<br>';
                            } 
                        }
                }
    }*/
}
yaml_emit_file("cassandra2.yaml",$result);
echo "<pre>",var_dump($result),"</pre>";
?>