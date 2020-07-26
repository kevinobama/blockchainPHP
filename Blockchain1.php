<?php
/**
 * Created by PhpStorm.
 * User: kevingates
 * Date: 7/26/20
 * Time: 10:14 AM
 *
 *
 */
class BlockChain {
    function read_all() {
        try {
            $jsondata = file_get_contents("chain.json");

            $arr_data = json_decode($jsondata, true);

            return $arr_data;
        }
        catch(Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }

    function get_previous_hashid($chain){
        $lastEl = array_values(array_slice($chain, -1))[0];
        return $lastEl["hashid"];
    }

    function get_previous_index($chain){
        $lastEl = array_values(array_slice($chain, -1))[0];
        return $lastEl["index"];
    }

    function get_new_hashid($previous_hashid,$index,$timestamp,$content){
        $full_string = $previous_hashid.$index.$timestamp.$content;
        $hash  = hash('sha256',$full_string);
        return $hash;
    }

    function read_content($content) {
        $arr_content = json_decode($content);
        return $arr_content;
    }
}


$eth = new BlockChain();
$eth->createNewBlock('','');


$full_chain = $eth->read_all();

echo "full blockchain loaded:<br />";
echo '<pre>',print_r($full_chain["chain"],1),'</pre>';
echo "<hr />";


$previous_hashid = $eth->get_previous_hashid($full_chain["chain"]);
echo "reading last block's hash id:<br />";
echo $previous_hashid;
echo "<hr />";

// reading last block's index to calculate next index
$previous_index = $eth->get_previous_index($full_chain["chain"]);
$next_index = $previous_index+1;
echo "reading last block's index to calculate next index:<br />";
echo "Last: " .$previous_index. " | Next: ".$next_index;
echo "<hr />";


echo "New hashid:<br />";
$timestamp = round(microtime(true) * 1000);
// example content
// $content = '{"from": "network","to": "simone","amount": 1000}';
$content = '{"from": "obama","to": "billgates","amount": 1000}';
$new_hashid = $eth->get_new_hashid($previous_hashid,$next_index,$timestamp,$content);
echo $new_hashid;
echo "<hr />";