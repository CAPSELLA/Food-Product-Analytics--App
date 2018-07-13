<?php
error_reporting(1);

$key="";
while(1)
{

    $curl_body="{
        \"configuration\":\"stevia\",
        \"key\": \"".$key."\"
    }";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,            "http://dataservices.ilsp.gr:29926/getabsa/agroknow" );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST,           1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $curl_body ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json')); 

    $result=curl_exec ($ch);
    $err = curl_error($ch);
    curl_close($ch);
    echo 'test';

    if ($err)
    {
        echo "cURL Error #:" . $err;
    } 
    else
    {
        //echo $response;
        $result=json_decode($result);
        print_r($result);
        //exit;
        for($i=0;$i<count($result->opinions);$i++)
        {
            echo $id."\n";
            $id=$result->opinions[$i]->id;            
            $json_already=json_decode(file_get_contents('jsons/'.$id.'.json'));

            $json_already=(array)$json_already;
            $json_already['opinions']=$result->opinions[$i]->opinions;
            $json_already=(object)$json_already;
            
            //print_r($json_already);
            
            $fp = fopen('jsons/'.$id.'.json', 'w');
            fwrite($fp, json_encode($json_already));
            fclose($fp);
        }
        
    }
    //exit;
    $key=$result->key;
    if(!isset($key) || $key=='' || $key=='2531')
        break;
}    
?>