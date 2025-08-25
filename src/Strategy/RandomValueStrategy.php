<?php

namespace Fitenson\TestLab\Strategy;

use Faker\Generator;
use Faker\Factory;
use yii\db\Schema;


class RandomValueStrategy implements GenerationStrategy {
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }


    public function generate(string $className, $db, $formData = null): array
    {
        $model = new $className;
        $faker = $this->faker;

        $modelSchema = $db->getTableSchema($model::tableName());

        $fields = [];
        $formData = isset($formData) ? $model::tableName() : null;

        // Initialize nested structure

        $isNested = true;

        if(!is_null($formData)) {
            $fields[$formData] = [];
        } else {
            $fields = [];
            $isNested = false;
        }


        foreach ($modelSchema->columns as $column) {
            $name = $column->name;

            switch ($column->type) {
                case Schema::TYPE_STRING:
                case Schema::TYPE_TEXT:
                    $data = $faker->boolean(20)
                    ? $faker->words(2, true)
                    : $faker->text(5);

                    if($isNested) {
                        $fields[$formData][$name] = $data;
                    } else {
                        $fields[$name] = $data;
                    }
                    break;

                case Schema::TYPE_INTEGER:
                    $data = $faker->numberBetween(1, $column->size ?? 1000);

                    if($isNested) {
                        $fields[$formData][$name] = $data;
                    } else {
                        $fields[$name] = $data;
                    }
                    break;

                case Schema::TYPE_BOOLEAN:
                    $data = $faker->boolean();

                    if($isNested) {
                        $fields[$formData][$name] = $data;
                    } else {
                        $fields[$name] = $data;
                    }
                    break;

                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                    $data = $faker->randomFloat(2, 0, $column->size ?? 1000);

                    if($isNested) {
                        $fields[$formData][$name] = $data;
                    } else {
                        $fields[$name] = $data;
                    }
                    break;

                default:
                    $data = null; // fallback

                    if($isNested) {
                        $fields[$formData][$name] = $data;
                    } else {
                        $fields[$name] = $data;
                    }
                    break;
            }
        }

        
        return $fields;
    }
}
