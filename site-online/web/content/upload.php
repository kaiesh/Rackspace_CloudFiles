<?
//Configure Rackspace relevant variables
define('RAXSDK_OBJSTORE_NAME','cloudFiles');
define('RAXSDK_OBJSTORE_REGION','LON');
define('RAXSDK_TIMEOUT',600);
//Load the Rackspace libraries
require_once('../lib/php-opencloud/lib/rackspace.php');
use OpenCloud;
/**
 * Configure the server variables
 **/
define("AUTHURL", "https://lon.identity.api.rackspacecloud.com/v2.0/");
define("USERNAME", "your-username");
define("APIKEY", "your-apikey");
define("CONTAINERNAME", "your-containername");
/** END OF SERVER VARS CONFIG **/

//establish the connection
$connection = new \OpenCloud\Rackspace(AUTHURL,
									   array('username' = >USERNAME,
											 'apiKey' => APIKEY));
//create the object store
$ostore = $connection->ObjectStore();

//test a file upload
$testData = array("container_name"=>CONTAINERNAME,
				  "filename"=>sha1("./sample.pdf"),
				  "filepath"=>"./sample.pdf");

$result = upload_file_get_url($testData);
if ($result["status"]){
	echo "Success! Your file can be found at ".$result["url"];
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
 *       "url" => "<URL>", //only returned if successful
 *       "err" => "<Exception details>") //only returned if failed
 **/
function upload_file_get_url($detailsArr){
	try{
		$container = $ostore->Container($detailsArr["container_name"]);
		$obj = $container->DataObject();
		$obj->Create(array('name'=>$detailsArr["filename"],
						   'content_type'=>mime_content_type($detailsArr["filepath"])),
					 $detailsArr["filepath"]);
		
		return array("status"=>true,
					 "url"=>$obj->PublicURL());
	}catch (Exception $e){
		return array("status"=>false,
					 "err"=>$e->getMessage());
	}
}
?>