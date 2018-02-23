<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Filesystem\File;


class ImageDecodeController extends AppController
{
	function beforeFilter(Event $event){
			parent::beforeFilter($event);				
	}
	
	function getImage($data = null){
		try{
			$base64String = $data;                          //  Your base64 encoded image string
			$imageData = $this->decodeBase64Image($base64String);			// get decoded image	
			file_put_contents($imageData['path'], $imageData['decodedData']);      //Write decoded image at the given path

		}
		catch ( \Exception $e ) {
			$arr = array("Message"=>$e->getMessage());
			echo json_encode($arr);
		}
	}
	
	function decodeBase64Image($base64String){
		try{
			$data = explode(',', $base64String);                // exploding the string to get the mime datatype
			$decodedData = base64_decode($data[1]);             // decoding data, $data[1] is image string in exploded data
			$result = getimagesize($base64String);              // Get image size
			$mime = $result['mime'];                            // Get MIME type
			$ext = explode('/',$mime);                          // Get File extension
			$imageName = 'img'.'_'.time().'.'.$ext[1];          // Set unique image name, here appending timestamp to the imagename
			$path = WWW_ROOT.'img/'.$imageName;                 // Set path to /webroot/img folder
			$file = new File($path, true, 0644);                // Set write permissions
			return array('imageName'=>$imageName,'decodedData'=>$decodedData,'path'=>$path);
		}
		catch (Exception $e) {		
			echo json_encode(['status'=>'false','error'=>$e->getMessage()]);

		}

	}
}
