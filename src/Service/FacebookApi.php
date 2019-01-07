<?php
namespace App\Service;

use Facebook\Facebook;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use Facebook\Exceptions\FacebookAuthenticationException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\HttpClients\FacebookHttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FacebookApi 
{
	

	public function __construct() {
		$this->fb = new Facebook([
			'app_id' => '175824203121129', // Replace {app-id} with your app id
			'app_secret' => '267cc5fbce96c55f7adde9b6d004503a',
			'default_graph_version' => 'v2.10',
		]);
	}

	public function getHelper(){
		 $helper = $this->fb->getRedirectLoginHelper();
		 return $helper;
	}

	public function getLogin() {

		$helper = $this->getHelper();
		
		$redirectURL = "http://localhost:8000/api_facebook_callback";
		$permissions = ['email'];
		$loginURL = $helper->getLoginUrl($redirectURL, $permissions);
		
		return $loginURL;
	}
}