<?php

declare(strict_types=1);

namespace App;

use Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(Config::basePath('templates'));
        $this->smarty->setCompileDir(Config::basePath('var/compiled'));
        $this->smarty->setCacheDir(Config::basePath('var/cache'));
        $this->smarty->escape_html = true;
    }

    public function render(string $template, array $data = []): string
    {
        $this->smarty->assign($data);

        return $this->smarty->fetch($template);
    }
}
