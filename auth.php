<?php
// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class auth_plugin_authrest extends DokuWiki_Auth_Plugin {
    /**
     * Constructor.
     */
    public function __construct(){
        parent::__construct();

        // Plugin capabilities
        $this->cando['logout'] = true;


        $this->success = true;
    }

    /**
     * Check user+password
     *
     * @param   string $user the user name
     * @param   string $pass the clear text password
     * @return  bool
     *
     * @uses PasswordHash::CheckPassword WordPress password hasher
     */
    public function checkPass($user, $pass) {
        $url = $this->getConf('url');

        $request = curl_init($url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, array("username" => $user, "password" => $pass));
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($request);
        if (curl_errno($request) != 0) {
            msg("Some error occured during connecting to django. Login not possible");
            curl_close($request);
            return false;
        }
        curl_close($request);
        $data = json_decode($response, true);
        if($data['user']){
            $this->users[$user] =  $data;
            return true;
        }
        return false;
    }

    /**
     * Returns info about the given user
     *
     * @param   string $user the user name
     * @return  array containing user data or false
     */
    public function getUserData($user, $requireGroups = true) {
       return $this->users[$user];
    }

}


?>