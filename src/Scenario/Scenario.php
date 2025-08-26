<?php

namespace Fitenson\TestLab\Scenario;

use FunctionalTester;


class Scenario {
    public FunctionalTester $tester;
    private ApiScenarioBuilder $apiScenarioBuilder;
    protected ?string $authToken = null;

    /**     @var callable | null    */
    protected $successHandler = null;

    /**     @var callable | null    */
    protected $errorHandler = null;


    public function __construct(FunctionalTester $tester)
    {
        $this->tester = $tester;
        $this->apiScenarioBuilder = new ApiScenarioBuilder($tester);
    }


    public function login(string $account, string $username, string $password): self 
    {
        $this->tester->sendPOST('/site/api/site/login', [
            'account' => $account,
            'username' => $username,
            'password' => $password
        ]);

        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $response = json_decode($this->tester->grabResponse(), true);

        if(!isset($response['data']['accessToken'])) {
            if(!empty($this->errorHandler)) {
                call_user_func($this->errorHandler, $response, $this->tester);
            } else {
                $this->tester->fail('Login Failed. Cannot get access token');
            }
        }

        $authToken = $response['data']['accessToken'];
        $this->authToken = $authToken;

        $header = base64_encode($username . ':' . $authToken);
        $this->tester->haveHttpHeader('Authorization', 'Basic ' . $header);

        if(!empty($this->successHandler)) {
            call_user_func($this->successHandler, $this);
        }

        return $this;
    }


    public function getToken(): ?string
    {
        return $this->authToken;
    }


    public function sendPOST(string $url, array $data = []): ApiScenarioBuilder
    {
        return $this->apiScenarioBuilder->sendPOST($url, $data, $this->apiScenarioBuilder->expectError);
    }


    public function sendGET(string $url, mixed $params): ApiScenarioBuilder
    {
        return $this->apiScenarioBuilder->sendGET($url, $params, $this->apiScenarioBuilder->expectError);
    }


    public function checkDatabase(string $table, array $criteria): self
    {
        $this->tester->seeInDatabase($table, $criteria);
        return $this;
    }


    public function checkResponse(array $criteria): self
    {
        $this->apiScenarioBuilder->checkResponse($criteria);
        return $this;
    }


    public function expectError(bool $expectError = true): self
    {
        $this->apiScenarioBuilder->expectError = $expectError;
        return $this;
    }
}
