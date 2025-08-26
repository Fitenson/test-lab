<?php

namespace Fitenson\TestLab\Scenario;

use FunctionalTester;
use RuntimeException;
use PHPUnit\Framework\AssertionFailedError;


abstract class ScenarioBuilder {
    protected FunctionalTester $tester;

    /** @var array|null */
    protected $lastResponse = null;

    /** @var callable|null */
    protected $successCallback = null;

    /** @var callable|null */
    protected $errorCallback = null;


    public function __construct(FunctionalTester $tester)
    {
        $this->tester = $tester;
    }
    
    
    public function whenSuccess(callable $callback): self
    {
        $this->successCallback = $callback;

        if ($this->lastResponse && $this->isSuccessCode($this->lastResponse['code'])) {
            $this->triggerSuccess();
        }

        return $this;
    }

    public function whenError(callable $callback): self
    {
        $this->errorCallback = $callback;

        if ($this->lastResponse && !$this->isSuccessCode($this->lastResponse['code'])) {
            $this->triggerError(new \RuntimeException("HTTP {$this->lastResponse['code']}"));
        }

        return $this;
    }


    protected function triggerSuccess(): void
    {
        if ($this->successCallback) {
            $callback = $this->successCallback;
            $this->successCallback = null; // reset
            $callback($this);
        }
    }


    protected function isSuccessCode(int $code): bool
    {
        return $code >= 200 && $code < 300;
    }


    protected function triggerError(\Throwable $error): void
    {
        codecept_debug("❌ Scenario Error: " . $error->getMessage());
        if ($this->errorCallback) {
            $callback = $this->errorCallback;
            $this->errorCallback = null; // reset
            $callback($this, $error);
        }

        throw new AssertionFailedError($error->getMessage());
    }


    public function checkResponse(array $criteria): bool
    {
        return $this->tester->seeResponseContainsJson($criteria);
    }
}
