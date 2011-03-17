<?php
//suissa
//> db.createCollection("mycoll", {capped:true, size:100000})
$mongo = new Mongo();
$db = $mongo->selectDB('my_db');
$coll = $db->selectCollection('my_collection');
$cursor = $coll->find()->tailable(true);
while (true) {
    if ($cursor->hasNext()) {
        $doc = $cursor->getNext();
        print_r($doc);
    } else {
        sleep(1);
    }
}

?>