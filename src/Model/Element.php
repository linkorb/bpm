<?php

namespace Bpm\Model;

use Boost\BoostTrait;
use Boost\Accessors\ProtectedAccessorsTrait;

abstract class Element
{
    use BoostTrait;
    use ProtectedAccessorsTrait;

    protected $id;
    protected $name;
    protected $description;
    protected $displayName;
    protected $displayDescription;
}
