<?php

namespace Fitenson\TestLab\Scenario;

use Throwable;
use FunctionalTester;


class ApiScenarioBuilder extends ScenarioBuilder
{
    protected FunctionalTester $tester;

    public function __construct(\FunctionalTester $tester)
    {
        $this->tester = $tester;
    }

    public function sendPOST(string $url, array $data = []): self
    {
        try {
            $this->tester->sendPOST($url, $data);
            $this->tester->seeResponseCodeIsSuccessful();
            $this->triggerSuccess();
        } catch(Throwable $error) {
            $this->triggerError($error);
        }

        return $this;
    }


    public function sendGET(string $url): self
    {
        try {
            $this->tester->sendGET($url);
            $this->tester->seeResponseCodeIsSuccessful();
        } catch(Throwable $error) {
            $this->triggerError($error);
        }

        return $this;
    }
}
