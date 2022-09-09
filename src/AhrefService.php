<?php

namespace Src;

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

    const TITLE_LOGIN = 'Ahrefs user login';

    private function buildUrlFromUri($uri)
    {
        return sprintf('%s/%s', self::URL_APP_V4, $uri);
    }

    public function login()
    {
        $cookieFile = ROOT_PATH . './cc.txt';

        $options = [
            CURLOPT_REFERER => $this->buildUrlFromUri(self::URI_DASHBOARD),
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/plain;charset=UTF-8',
                'Host: https://app.ahrefs.com',
                'Origin: https://app.ahrefs.com',
                'Referer: https://app.ahrefs.com/',
            ],
        ];

        $payload = [
            'remember_me' => true,
            'auth' => [
                'password' => config('accounts.ahrefs.password'),
                'login' => config('accounts.ahrefs.email')
            ]
        ];

        $curl = new Curl();
        $loginResponse = $curl->post(self::URL_LOGIN_AUTH, $options, $payload);
        if ($loginResponse->getHttpCode() !== Response::HTTP_OK) {
            dump($loginResponse);
            exit('login fail'. $loginResponse->getError());
        }

        $data = $loginResponse->getData();
        if (!is_array($data)) {
            exit('login data invalid');
        }

        $sessionId = arr_get($loginResponse, $data);
        if (!empty($sessionId)) {
            $checkCompletionResponse = $this->loginCheckCompletion();
            dump($checkCompletionResponse);
        }
    }

    public function loginCheckCompletion()
    {
        $url = $this->buildUrlFromUri(self::URI_LOGIN_CHECK_COMPLETION);
        $curl = new Curl();
        return $curl->post($url, [], []);
    }
}
