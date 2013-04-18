<?
/**
 * This file is to demonstrate usage of the rackspace helper object, specifically:
 * Uploading a local file to CloudFiles and retrieving the associated URLs
 **/

/**
 * Load our settings file (above doc root)
 * NOTE: YOU WILL NEED TO CONFIGURE YOUR ACCOUNT SETTINGS IN THIS FILE FOR THIS TO WORK
 **/
require_once('../Settings.php');
//Load our helper library
require_once('../lib/rackspace_helper/RackspaceHelper.php');
//Load the Rackspace libraries
require_once('../lib/php-opencloud/lib/rackspace.php');

//The container name (as defined during creation) that you want to upload to
define("CONTAINERNAME", "your-container");

//Create the rackspace helper object
$rackspace_helper = new RackspaceHelper(Settings::$Rackspace_AUTHURL, Settings::$Rackspace_USERNAME, Settings::$Rackspace_APIKEY);

//prepare data to test a file upload
$testData = array("container_name"=>CONTAINERNAME,
				  "filename"=>sha1("./sample.pdf"),
				  "filepath"=>"./sample.pdf");

//Upload the file and echo the results!
$result = $rackspace_helper->upload_file_get_url($testData);

if ($result["status"]){
	//Successful upload!
	echo "Success! Your file can be found at ".$result["url"]." -OR- on the CDN at: ".$result["cdn"];
}else{
	//Upload failed...
	echo "FAIL! Error returned: ".$result["err"];
}



?>