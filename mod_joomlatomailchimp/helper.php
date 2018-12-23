<?php
defined('_JEXEC') or die;

class ModJoomlaToMailchimpHelper
{
   
    public static function getAjax()
    {
      $input = JFactory::getApplication()->input;
		  $email  = $input->get('email', '', 'raw');		  
		  $email = rawurldecode($email);
		  
        
		  
	    $module = JModuleHelper::getModule('mod_joomlatomailchimp');
      $params = json_decode($module->params);
		 

		  $mailchimp_return = '';
	   
		  if(empty($email)){
			  $status = "error";
			  $message = "You did not enter an email address!";
		  }
		  else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //validate email address - check if is a valid email address
			  $status = "error";
			  $message = "You have entered an invalid email address!";
		  }
 
	    else if( !isset($params->api_key)){
		    $status = "error";
		    $message = "API Key missing. Please contact webmaster.";
	    }
	    else if( !isset($params->server_id)){
		    $status = "error";
		    $message = "Server ID missing. Please contact webmaster.";
	    }
	    else if( !isset($params->list_id)){
		    $status = "error";
		    $message = "List ID missing. Please contact webmaster.";
	    }
		  else {		  
        date_default_timezone_set('Europe/Vienna'); 
        $date = date('Y-m-d');
        $time = date('H:i:s');
		    
        $apiKey = $params->api_key;
        $server = $params->server_id;
        $listId = $params->list_id;
        
        try {              
          $url = 'https://'. $server .'.api.mailchimp.com/3.0/lists/'. $listId .'/members';
          $data = '{"email_address":"'. $email .'", "status":"subscribed", "merge_fields": {
           "OPTIN": "'. date('d-m-Y') .'"
          }
          }';
          $mailchimp_return = json_decode(ModJoomlaToMailchimpHelper::CallAPI('POST', $url, $apiKey, $data));
          
	
          if($mailchimp_return->status != 200):
            $status = "error";
            $message = json_decode($mailchimp_return->body)->detail;
          else:
            $status = "success";
            $message = "You have been signed up!";
          endif;
         
        } catch(PDOException $e) {
         $status = "error";
         $message = "This email address has already been registered!";
         //$message = $e->getMessage();
        } catch(Exception $e) {
        $status = "error";
        $message = "Server Error.";
        }
		  }
	   
		  //return json response 
		  $data = array(
			  'status' => $status,
			  'message' => $message
		  );
	   
		  echo json_encode($data);
	  }
	  


	  function CallAPI($method, $url, $apiKey, $data = false)
	  {
		  $curl = curl_init();

		  switch ($method)
		  {
			  case "POST":
				  curl_setopt($curl, CURLOPT_POST, 1);

				  if ($data)
					  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				  break;
			  case "PUT":
				  curl_setopt($curl, CURLOPT_PUT, 1);
				  break;
			  default:
				  if ($data)
					  $url = sprintf("%s?%s", $url, http_build_query($data));
		  }

		  // Optional Authentication:
		  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		  curl_setopt($curl, CURLOPT_USERPWD, "anystring:".$apiKey);

		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		  $result = curl_exec($curl);
		  $statuscode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
		  curl_close($curl);
		  
      $data = array(
			  'status' => $statuscode,
			  'body' => $result
		  );

		  return json_encode($data);
    }
}

?>
