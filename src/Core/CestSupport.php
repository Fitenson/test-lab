<?php

namespace Fitenson\TestLab\Core;

use FunctionalTester;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Faker\Provider\zh_CN\Person;


abstract class CestSupport {
    protected Generator $faker;


    public function __construct()
    {
        $this->faker = FakerFactory::create();
        $this->faker->addProvider(new Person($this->faker));
    }


    // public function __before(FunctionalTester $tester) {
    //     $tester->amOnPage();
    // }


    /**
     *  Generate fake data
     * 
     *  @param string $className    The model class name
    */
    protected function faker(string $className, ?string $scenario)
    {
        $modelFaker = new $className;

        switch($scenario) {
            default:
                return $modelFaker->generateRandomData($className, $this->faker, $scenario);
        }
    }
}
