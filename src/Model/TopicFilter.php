<?php

namespace App\Model;

class TopicFilter
{
    public function __construct(array $data)
    {
        if(array_key_exists('type', $data)) {
            $this->type = $data['type'];
        }
    }

    public string $type;
}
