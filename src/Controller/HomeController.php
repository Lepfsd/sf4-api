<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\InstagramApi;
use App\Service\SimpleImageApi;
use Twilio\Rest\Client;
use App\Service\SmsTwiloGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\FacebookApi;
use Facebook\Authentication\AccessToken;
use Facebook\FacebookClient;
use Facebook\FacebookSession;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use Facebook\Exceptions\FacebookAuthenticationException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\HttpClients\FacebookHttpClientInterface;

class HomeController extends Controller

{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     *@Route("/api/login_instagram", name="login_instagram")
     */
    public function loginInstagramAction(InstagramApi $instagram)
    {
        return new Response('<html><body><a href='. $instagram->getLoginUrl().'>iniciar sesion</a></body></html>');
    }

    /**
     *@Route("/api/instagram_callback", name="callback_instagram")
    */
    public function callbackAction(Request $request, InstagramApi $instagram)
    {   
        $code = $request->query->get('code');
        $data = $instagram->manageCallback($code);
        
    }

    /**
     *@Route("/api/instagram_auto_post", name="auto_post_instagram", methods={"POST"})
    */
    public function autoPostAction(Request $request, InstagramApi $insta, SimpleImageApi $image)
    {   
        $username = $request->request->get('username');   // your username
        $password = $request->request->get('password');   // your password
        $filename = $request->request->get('filename');   // your sample photo
        $caption = $request->request->get('caption');   // your caption
        var_dump($request->getSchemeAndHttpHost());die;
        $product_image= __DIR__.'/../../public/original/'.$filename;
        $square = __DIR__.'/../../public/resize/'.$filename;
        var_dump($product_image);die;
        $image->load($product_image); 
        $image->resize(480,600); 						
        $image->save($square, IMAGETYPE_JPEG);  
        unset($image);

        $response = $insta->Login($username, $password);
        
        if(strpos($response[1], "Sorry")) {
            echo "Request failed, there's a chance that this proxy/ip is blocked";
            print_r($response);
            exit();
        }         
        if(empty($response[1])) {
            echo "Empty response received from the server while trying to login";
            print_r($response);	
            exit();	
        }
        var_dump($insta->Post($square, $caption));die;
        $insta->Post($square, $caption);
    }

    /**
     * @Route("/api_sms/")
     * @Method({"GET", "POST"})
     */

    public function smsAction(Request $request, SmsTwiloGenerator $smsTwiloGenerator)
    {
        $from = "+19564460889";
        $to = $request->get('to');
        $body = $request->get('body');
        
        if ($request->isMethod("POST")) {
            
            $mensaje = $smsTwiloGenerator->getSendsms($from, $to, $body);
            return JsonResponse::create(['status' => Response::HTTP_OK, 'mensaje' => $mensaje]);
        } else {
            return JsonResponse::create(['status' => Response::HTTP_BAD_REQUEST]);
        }
    }

    /**
     * @Route("/api_facebook_login", name="facebooklogin")
     * @Method({"GET", "POST"})
     */
    public function facebookAction(Request $request, FacebookApi $fb) {
        $loginUrl = $fb->getLogin();
        return new Response('<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>');
    }

    /**
     * @Route("/api_facebook_callback", name="fb-callback")
     */
    public function facebookTokenkAction(Request $request, FacebookApi $fb) {
        try{
            $accessToken = $fb->getHelper()->getAccessToken();
            
        }  catch (\Facebook\Exceptions\FacebookResponseException $e ) {
            echo "Response Exception: ". $e->getMessage();
            exit();
        } catch (\Facebook\Exceptions\FacebookSDKException $e){
            echo "SDK Response Exception: ". $e->getMessage();
            exit();
        }
        
        if(!$accessToken) {
            return $this->redirectToRoute('facebooklogin');
        }
        
        $oAuth2Client = $fb->fb->getOAuth2Client();
        
        if(!$accessToken->isLongLived()){
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        }
        
        $response = $fb->fb->get("me?fields=id, name, email", $accessToken);
        
        $userData = $response->getGraphNode()->asArray();
        dump($userData);die;
    }
}


