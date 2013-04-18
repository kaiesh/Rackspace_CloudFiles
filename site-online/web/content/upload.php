<?
//Configure Rackspace relevant variables
define('RAXSDK_OBJSTORE_NAME','cloudFiles');
define('RAXSDK_OBJSTORE_REGION','DFW');
define('RAXSDK_TIMEOUT',600);
//Load the Rackspace libraries
require_once('../lib/php-opencloud/lib/rackspace.php');

/**
 * Configure the server variables
 **/
define("AUTHURL", RACKSPACE_US); //This will differ based on which country your account is in
//Your Rackspace username
define("USERNAME", "your-username");
//Your rackspace API key generated from http://mycloud.rackspace.com/a/<account-name>/api-keys
define("APIKEY", "your-key");
//Your container name as defined during creation
define("CONTAINERNAME", "your-container-name");
/** END OF SERVER VARS CONFIG **/


//establish the connection
$connection = new \OpenCloud\Rackspace(AUTHURL,
							array('username' =>USERNAME,
								  'apiKey' => APIKEY));

//create the object store, explictly specify location
$ostore = $connection->ObjectStore('cloudFiles', 'DFW'); //This will differ depending on where your container is geographically located

//test a file upload
$testData = array("container_name"=>CONTAINERNAME,
				  "filename"=>sha1("./sample.pdf"),
				  "filepath"=>"./sample.pdf");

$result = upload_file_get_url($testData, $ostore);
if ($result["status"]){
	echo "Success! Your file can be found at ".$result["url"]." -OR- on the CDN at: ".$result["cdn"];
}else{
	echo "FAIL! Error returned: ".$result["err"];
}


/**
 * Process the necessary upload request, by using an array with necessary info
 * Array format should be:
 * array("container_name" => "<your container name>",
 *       "filename" => "<name of file to be stored>",
 *       "filepath" => "<location on disk of file to be uploaded>")
 * 
 * Returns array with following structure:
 * array("status" => true | false, //true if upload success, false otherwise
 *       "url" => "<URL>", //static URL only returned if successful
 *       "cdn" => "<URL>", //CDN URL returned if successful
 *       "err" => "<Exception details>") //only returned if failed
 **/
function upload_file_get_url($detailsArr, $ostore){
	try{
		$container = $ostore->Container($detailsArr["container_name"]);
		$obj = $container->DataObject();
		$obj->Create(array('name'=>$detailsArr["filename"],
						   'content_type'=>mime_content_type($detailsArr["filepath"])),
					 $detailsArr["filepath"]);
		
		return array("status"=>true,
					 "url"=>$obj->PublicURL(),
					 "cdn"=>$obj->CDNUrl());
	}catch (Exception $e){
		return array("status"=>false,
					 "err"=>$e->getMessage());
	}
}
?>