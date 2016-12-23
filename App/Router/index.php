<?php
Router::get('/', ['call'=>'App\Controller\Index@Index']);
Router::any('/test', ['call'=>'App\Controller\Index@test']);
