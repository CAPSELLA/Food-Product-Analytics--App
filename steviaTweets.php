<?php
error_reporting(1);

$key=0;

while(1)
{

    $curl_body="{
        \"configuration\":\"stevia\",
        \"key\": \"".$key."\"
    }";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,            "http://dataservices.ilsp.gr:29926/getdata/agroknow" );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST,           1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $curl_body ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json')); 

    $result=curl_exec ($ch);

//          echo $result;

    $err = curl_error($ch);

//echo $err;
    curl_close($ch);
    echo '.';

    if ($err)
    {
        echo "cURL Error #:" . $err;
    } 
    else
    {
        //echo $response;
        $result=json_decode($result);
//        print_r($result);
        
        for($i=0;$i<count($result->raw_data);$i++)

        {

                     $id=$result->raw_data[$i]->id;
            
        
                
                        $realText = $result->raw_data[$i]->text ;
                        $created_at = date('Y/m/d', strtotime($result->raw_data[$i]->created_at));;
                        $user_id = $result->raw_data[$i]->user_id ;

                 //       print_r($realText);
                        
        
            $empty_opinions=new stdClass();
            $empty_opinions->aspect_category='';
            $empty_opinions->polarity='';
            $empty_opinions->{'start:end'}='';
            $empty_opinions->target='';





                       $user=new stdClass();
                        $user->user_id=$user_id;

            /*
            "aspect_category": "STEVIA#GENERAL",
            "polarity": "negative",
            "target"            
            */
            
            $json_already=(array)$realtext;
                        $json_already['text']=$realText;
                        $json_already['created_at']=$created_at;
                        $json_already['id']=$id;
                        $json_already['user']=$user;
            $json_already['opinions']=$empty_opinions;
            $json_already=(object)$json_already;






            
            
//            print_r($json_already);
            
            $fp = fopen('jsons/'.$id.'.json', 'w');
            fwrite($fp, json_encode($json_already));
            fclose($fp);
            
            //exit;
        }
        
    }
    
    $key=$result->key;
    if(!isset($key) || $key=='')
        break;
}    
?>