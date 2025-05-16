<?php

require_once 'ArticleController.php';

$articleController = new ArticleController(getPDO());

$articles = $articleController->getAll();

echo($articles[0]['content']);