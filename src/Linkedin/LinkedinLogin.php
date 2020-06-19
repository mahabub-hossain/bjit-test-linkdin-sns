<?php
namespace BJIT\Linkedin;


class LinkedinLogin
{
    protected $config;
    protected $client_id;
    protected $client_secret;
    Protected $redirect_url;
    protected $auth_url = "https://www.linkedin.com/oauth/v2/authorization";
    protected $accesstoken_url = "https://www.linkedin.com/oauth/v2/accessToken";
    public function __construct($config)
    {
        $this->config = $config;
        $this->client_id = $config['linkedin']['app_id'];
        $this->client_secret = $config['linkedin']['app_secret'];
        $this->redirect_url = $config['linkedin']['callback'];
    }

    //Getting url for user permission
    public function getUrl(){
        $url = $this->auth_url.'?response_type=code&client_id='.$this->client_id.'&redirect_uri='.$this->redirect_url.'&scope=r_liteprofile%20r_emailaddress';
        return $url;
    }
    //This will provide  access tokent with expiry time
    public function getAccesstoken($code){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->accesstoken_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=authorization_code&client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code."&redirect_uri=".$this->redirect_url,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }
}