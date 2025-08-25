<?php

namespace Fitenson\TestLab\Strategy;


interface GenerationStrategy {
    public function generate(string $className, $db, ?string $formData = null): array;
}
