<?php

namespace App\Button;

class LinkToRoute
{
    public static $CLASS_BTN = 'btn';
    public static $CLASS_ADDITIONAL = 'btn-outline-primary';

    public function __construct(
        private readonly string $route,
        private readonly string $caption,
        private readonly string $type = 'primary',
        private readonly string $icon = '',
    )
    {
    }

    public function getClasses() : string
    {
        return sprintf("%s %s", self::$CLASS_BTN, self::$CLASS_ADDITIONAL);
    }

    public function getRoute() : string
    {
        return $this->route;
    }

    public function getCaption() : string
    {
        return $this->caption;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getIcon() : string
    {
        return $this->icon;
    }
}
