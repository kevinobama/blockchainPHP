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
  public static $_chain = array();
  function  createNewBlock($proof, $sender, $recipient, $amount, $previousHash = null) {
      $index = count(self::$_chain);
      $block=array(
          'index'=> $index,
          'timestamp'=> round(microtime(true) * 1000),
          'transactions'=> array(
              'Sender'=>$sender,
              'Recipient'=>$recipient,
              'Amount'=>$amount,
          ),
          'proof'=> $proof,
          'previous_hash'=> $previousHash?:$this->getPreviousHashId(self::$_chain),
      );

      $block['hashid']=$this->getNewHashId(0,$block['index'],$block['timestamp'],json_encode($block['transactions']),$block['timestamp']);
      self::$_chain[$index]=$block;
  }

    function getNewHashId($previous_hashid,$index,$timestamp,$content){
        $full_string = $previous_hashid.$index.$timestamp.$content;
        $hash  = hash('sha256',$full_string);
        return $hash;
    }

    function getPreviousHashId($chain) {
        $lastEl = array_values(array_slice($chain, -1))[0];
        return $lastEl["hashid"];
    }
}


$eth = new BlockChain();
$eth->createNewBlock(rand(0,100), 'obama', 'billgates', 100);
$eth->createNewBlock(rand(0,1000), 'billgates', 'bush', 200);
$eth->createNewBlock(rand(0,1000), 'bush', 'elon mask', 200);

print_r(BlockChain::$_chain);
file_put_contents('generateBlocksbyphp.json',json_encode(BlockChain::$_chain,JSON_PRETTY_PRINT));