<?php
    return[
      'blog.prefix' => '/blog',
        \App\Blog\BlogModule::class => \DI\object()->constructorParameter('prefix', \DI\get('blog.prefix'))
    ];
