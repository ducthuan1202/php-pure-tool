<?php


namespace Src;


use GuzzleHttp\Client;

class AhrefService
{

    const
        URL_LOGIN_AUTH = 'https://auth.ahrefs.com/auth/login',
        URL_APP = 'https://app.ahrefs.com',
        URL_APP_V4 = self::URL_APP . '/v4',
        URI_LOGIN_CHECK_COMPLETION = 'loginCheckCompletion',
        URI_DASHBOARD = 'dashboard',
        URI_AS_GET_WORKSPACES = 'asGetWorkspaces',
        URL_SE_GET_TOP_PAGES_EXPORT = 'seGetTopPagesExport',
        URI_SE_GET_ORGANIC_KEYWORDS_EXPORT = 'seGetOrganicKeywordsExport',
        URI_SE_GET_PAGES_BY_TRAFFIC = 'seGetPagesByTraffic',
        URI_SE_GET_SITE_STRUCTURE = 'seGetSiteStructure',
        URI_SE_GET_POSITIONS_MOVEMENTS = 'seGetPositionsMovements',
        URI_SE_GET_PAGES_MOVEMENTS = 'seGetPagesMovements',
        URI_KE_GET_SEARCH_VOLUME_GOOGLE = 'keGetSearchVolumeGoogle',
        URI_KE_GET_TOP_POSITIONS_HISTORY = 'keGetTopPositionsHistory',
        URI_SE_BACKLINKS_EXPORT = 'seBacklinksExport',
        URI_SE_BACKLINKS = 'seBacklinks',
        URI_SE_BACKLINKS_GROUP = 'seBacklinksGroup',
        URI_SE_REF_DOMAINS = 'seRefdomains',
        URI_SA_PROJECTS = 'saProjects',
        URI_KE_SERP_OVERVIEW = 'keSerpOverview',
        URI_KE_IDEAS_EXPORT = 'keIdeasExport',
        URI_KE_PLAN = 'kePlan',
        URI_PM_ADD_PROJECT = 'pmAddProject',
        URI_BATCH_ANALYSIS = 'batch-analysis';

    const
        TITLE_LOGIN = 'Ahrefs user login';

    private function buildUrlFromUri($uri)
    {
        return sprintf('%s/%s', self::URL_APP_V4, $uri);
    }

    private function commonOptionsCurl()
    {
        $cookieFile = ROOT_PATH.'./cc.txt';
        return [
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_VERBOSE => true,
            CURLOPT_REFERER => $this->buildUrlFromUri(self::URI_DASHBOARD),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Origin: ' . self::URL_APP,
                'Referer: ' . $this->buildUrlFromUri(self::URI_DASHBOARD),
            ],
            CURLOPT_USERAGENT => Request::USERAGENT_EDGE,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_COOKIEJAR => $cookieFile,
        ];
    }

    private function post($url, $payload = [], $optionsExtra = [])
    {
        $options = $this->commonOptionsCurl();

        if (is_array($payload) && count($payload) > 0) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        if(is_array($optionsExtra) && count($optionsExtra) > 0){
            foreach ($optionsExtra as $key => $value){
                $options[$key] = $value;
            }
        }

        return $this->run($url, $options);
    }

    private function run($url, $options)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, $options);

        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return [
            'data' => json_decode($data, true),
            'info' => $info,
            'error' => $error,
        ];
    }

    public function login()
    {
        $payload = [
            'remember_me' => true,
            'auth' => [
                'password' => config('accounts.ahrefs.password'),
                'login' => config('accounts.ahrefs.email')
            ]
        ];

        $loginResponse = $this->post(self::URL_LOGIN_AUTH, $payload);
        dump($loginResponse);

        $sessionId = arr_get($loginResponse, 'data.result.session_id');

        if (!empty($sessionId)) {
            $checkCompletionResponse = $this->loginCheckCompletion($sessionId);
            dump($checkCompletionResponse);
        }
    }

    public function loginCheckCompletion($sessionId)
    {
        dump($sessionId);

        $url = $this->buildUrlFromUri(self::URI_LOGIN_CHECK_COMPLETION);

        return $this->post($url, [], []);
    }

    public function getWorkspace($sessionId)
    {

        dump($sessionId);

        $url = $this->buildUrlFromUri(self::URI_AS_GET_WORKSPACES);
        $data = $this->post($url);

        return $data;
    }

    public function getTitle($url)
    {
        $client = new Client([
            'timeout' => 3,
        ]);

        $response = $client->request(Request::METHOD_GET, $url);

        if ($response->getStatusCode() === 200) {
            $stringBody = (string)$response->getBody();
            dump(get_title_from_txt($stringBody));
        }
    }
}