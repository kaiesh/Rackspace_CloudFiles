<?
/**
 * Rackspace object that maintains all necessary settings, 
 * and connections to the cloud service
 **/
//Configure Rackspace relevant variables
define('RAXSDK_OBJSTORE_NAME','cloudFiles');
define('RAXSDK_OBJSTORE_REGION','DFW');
define('RAXSDK_TIMEOUT',600);

class RackspaceHelper{
	//Maintain the connection to the cloud service
	private $connection;
	//Maintain the object store
	private $ostore;

	/**
	 * Constructor
	 * WARNING: This constructor will throw an Exception if AUTH fails
	 **/
	function RackspaceHelper($auth_url, $username, $apikey){
		//establish the connection
		$this->connection = new \OpenCloud\Rackspace($auth_url,
											   array('username' =>$username,
													 'apiKey' => $apikey));
		
		//create the object store using the defaults defined above
		$this->ostore = $this->connection->ObjectStore(); 
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
	 *       "hash" => "<hash>", //Hash name of file, useful if using custom CNAME
	 *       "err" => "<Exception details>") //only returned if failed
	 **/
	function upload_file_get_url($detailsArr){
		try{
			$container = $this->ostore->Container($detailsArr["container_name"]);
			$obj = $container->DataObject();
			$obj->Create(array('name'=>$detailsArr["filename"],
							   'content_type'=>mime_content_type($detailsArr["filepath"])),
						 $detailsArr["filepath"]);
			
			return array("status"=>true,
						 "url"=>$obj->PublicURL(),
						 "cdn"=>$obj->CDNUrl(),
						 "hash"=>$obj->hash);
		}catch (Exception $e){
			return array("status"=>false,
						 "err"=>$e->getMessage());
		}
	}
}
?>