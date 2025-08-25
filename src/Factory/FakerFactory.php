<?php

namespace Fitenson\TestLab\Factory;

use Yii;
use Faker\Generator;
use Fitenson\TestLab\Constant\Scenario;
use Fitenson\TestLab\Strategy\GenerationStrategy;
use Fitenson\TestLab\Strategy\RandomValueStrategy;
use yii\db\Schema;


abstract class FakerFactory {
    public $db;


    public function __construct($db)
    {
        $this->db = $db;
    }


    public function createStrategy(string $className, string $scenario): GenerationStrategy
    {
        $strategy = null;
        $db = $this->db;

        switch($scenario) {
            default:
                $strategy = new RandomValueStrategy($className, $db);
        }


        return $strategy;
    }


    public function generate(string $className, string $scenario): array
    {
        $strategy = $this->createStrategy($className, $scenario);
        return $strategy->generate($className, $this->db);
    }


    public function generateRandomData(string $className, Generator $faker, ?string $scenario): array
    {
        $model = new $className;
        $db = Yii::$app->getDb();
        $modelSchema = $db->getTableSchema($model::tableName());

        $fields = [];
        $formData = $model::tableName();

        // Initialize nested structure
        $fields[$formData] = [];

        foreach ($modelSchema->columns as $column) {
            $name = $column->name;

            switch ($column->type) {
                case Schema::TYPE_STRING:
                case Schema::TYPE_TEXT:
                    $maxLength = $column->size ?? 255;
                    $randomLength = $faker->numberBetween(1, $maxLength);

                    $fields[$formData][$name] = $faker->boolean(20)
                        ? $faker->lexify('Hello World' . str_repeat('?', $faker->numberBetween(1, 5)))
                        : $faker->text($randomLength);
                    break;

                case Schema::TYPE_INTEGER:
                    $fields[$formData][$name] = $faker->numberBetween(1, $column->size ?? 1000);
                    break;

                case Schema::TYPE_BOOLEAN:
                    $fields[$formData][$name] = $faker->boolean();
                    break;

                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                    $fields[$formData][$name] = $faker->randomFloat(2, 0, $column->size ?? 1000);
                    break;

                default:
                    $fields[$formData][$name] = null; // fallback
                    break;
            }
        }

        return $fields;
    }


    public function generateNullData(string $className)
    {
        $model = new $className;

        $db = Yii::$app->getDb();

        $modelSchema = $db->getTableSchema($model::tableName());
        $fields = [];


        foreach($modelSchema->columns as $column) {
            $name = $column->name;
            $fields[$name] = null;
        }


        return $fields;
    }
}
