<?php

spl_autoload_register(function ($class_name) {
    include "Model/" . $class_name . '.php';
});
