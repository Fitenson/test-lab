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

            if (!$this->expectError) {
                $this->tester->seeResponseCodeIsSuccessful();
                $this->triggerSuccess();
            } else {
                // Assert it's NOT successful (e.g. 400, 422, 500, etc.)
                $this->tester->seeResponseCodeIsBetween(400, 599);
                $this->triggerError(new \Exception("Expected error response received"));
            }
        } catch (Throwable $error) {
            $this->triggerError($error);
        }

        return $this;
    }


    public function sendGET(string $url): self
    {
        try {
            $this->tester->sendGET($url);

            if (!$this->expectError) {
                $this->tester->seeResponseCodeIsSuccessful();
                $this->triggerSuccess();
            } else {
                $this->tester->seeResponseCodeIsBetween(400, 599);
                $this->triggerError(new \Exception("Expected error response received"));
            }
        } catch (Throwable $error) {
            $this->triggerError($error);
        }

        return $this;
    }
}
