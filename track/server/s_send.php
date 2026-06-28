<?php
			$now = new DateTime();
			$timeStamp = $now->getTimestamp(); 
			$body = substr(file_get_contents("data.json"), 0, -1);
			file_put_contents("data.json",'', LOCK_EX);
			$iparr = explode("},", $body);
			$newBody = '[';
			for ($x = 0; $x < count($iparr); $x++) {
				$item = str_replace('}}','}}',$iparr[$x].'}');
			if(json_validator($item)){
				$newBody .= $item .','; 
			}
			}
			$newBody .= substr($item, 0, -1) .']'; 
			if(json_validator($newBody)){
				$body=$newBody;
			}
			
			$headers = array(
				'Content-Type: text/plain',
				'Authorization:Bearer 310315eaf1a7578c75c09ae0630bb83d'
			 );
			 
			 if($ch = curl_init()) {
				curl_setopt($ch, CURLOPT_URL, 'http://ingest01.loconav.com/api/v1/coordinates/publish/');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
				$content  = curl_exec($ch);
    			curl_close($ch);
				$body = str_replace("[","",$body);
				$body = str_replace("]","",$body);
				$body = explode("},{", $body); 
				error_log(count($body).' - '.$content);
				file_put_contents("log.json",str_replace("<br />","",nl2br("\r\n".date("Y-m-d H:i:s").' - '.count($body).' - '.$content)), FILE_APPEND | LOCK_EX);
			 }

function json_validator($data=NULL) {

  if (!empty($data)) {

                @json_decode($data);

                return (json_last_error() === JSON_ERROR_NONE);

        }
        return false;
}

?>