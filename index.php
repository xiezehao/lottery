<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/07 0007
 * Time: 13:45
 */
global $authorization;
$authorization="Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjo1ODAzODkzMiwibmlja19uYW1lIjoi6LCi5rO96LGqIiwiYXZhdGFyIjoiaHR0cHM6Ly93eC5xbG9nby5jbi9tbW9wZW4vdmlfMzIvdGxNQUNBcElpYjZreko1OFBiUnJBZjZ5WjJMNGNSc3Q5QmFWUEV1dDV4UkNHRkx1NmtWbHliNmxtZ1pXVElDUHJ2Z0dzbFdvaFBGZHZYdXJpYWRIUHFUQS8xMzIiLCJwcm92aW5jZSI6Ikd1YW5nZG9uZyIsImNpdHkiOiJHdWFuZ3pob3UiLCJnZW5kZXIiOiIxIiwiaWF0IjoxNTQwNzE2MzIzLCJleHAiOjE1NDEzMjExMjN9.0O5uGWBJR9qMyNWho4s9hdOp5Vhzc5-0sRMihvY38Do";
$public_lottery_url="https://lucky.nocode.com/public_lottery?page=1&size=5";
$square_url="https://lucky.nocode.com/square";

getGoodsList($public_lottery_url,"每日福利");
getGoodsList($square_url,"自助福利");
function getGoodsList($url,$keyword){
    global $authorization;
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $header=array("authorization: ".$authorization);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output=curl_exec($ch);
    if($error=curl_error($ch)){
        var_dump("---".$error);
    }
    curl_close($ch);
//    var_dump($output);die();
    if($output!="Unauthorized"){
        $output=json_decode($output,true);
        file_put_contents(date("Y-m-d").".txt",date("Y-m-d H:i:s")."     ".$keyword."\r\n",FILE_APPEND);
        for($i=0;$i<count($output["data"]);$i++){
            if(!$output["data"][$i]["joined"]) {
                $res=joined($output["data"][$i]["id"]);
                if(strstr($res,"true")){
                    file_put_contents(date("Y-m-d").".txt",$output["data"][$i]["prizes"]["data"][0]["name"]."\r\n",FILE_APPEND);
                }
//                echo $output["data"][$i]["prizes"]["data"][0]["name"]."<br>";
            }
        }
        file_put_contents(date("Y-m-d").".txt","\r\n",FILE_APPEND);
    }else{
        file_put_contents(date("Y-m-d").".txt",date("Y-m-d H:i:s")."\r\n"."Unauthorized"."\r\n"."\r\n",FILE_APPEND);
    }

}

function joined($id){
    global $authorization;
    $url="https://lucky.nocode.com/lottery/".$id."/join";
    $c=curl_init();
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($c,CURLOPT_POST,1);
    $header=array("authorization: ".$authorization);
    curl_setopt($c,CURLOPT_HEADER,0);
    curl_setopt($c,CURLOPT_HTTPHEADER,$header);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    $res=curl_exec($c);
    curl_close($c);
//    print_r($res);
    return $res;
}