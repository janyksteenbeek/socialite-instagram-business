<?php

namespace JanykSteenbeek\SocialiteInstagramBusiness\Two;

use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class InstagramBusinessProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'INSTAGRAM_BUSINESS';

    /**
     * {@inheritdoc}
     */
    public $scopeSeparator = ' ';

    /**
     * The user fields being requested.
     *
     * @var array
     */
    public $fields = ['account_type', 'id', 'user_id', 'username', 'name',
        'account_type', 'profile_picture_url', 'followers_count',
        'follows_count', 'media_count'];

    /**
     * {@inheritdoc}
     */
    public $scopes = ['instagram_business_basic'];

    /**
     * {@inheritdoc}
     */
    public function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://www.instagram.com/oauth/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenUrl()
    {
        return 'https://api.instagram.com/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $queryParameters = [
            'access_token' => $token,
            'fields' => implode(',', $this->fields),
        ];

        if (! empty($this->clientSecret)) {
            $queryParameters['appsecret_proof'] = hash_hmac('sha256', $token, $this->clientSecret);
        }

        $response = $this->getHttpClient()->get('https://graph.instagram.com/v24.0/me', [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            RequestOptions::QUERY => $queryParameters,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['user_id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'account_type' => $user['account_type'],
            'media_count' => $user['media_count'] ?? null,
            'followers_count' => $user['followers_count'] ?? null,
            'follows_count' => $user['follows_count'] ?? null,
            'avatar' => $user['profile_picture_url'] ?? null,
        ]);
    }

    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode((string) $response->getBody(), true);

        return $this->parseAccessToken($response->getBody());
    }
}
