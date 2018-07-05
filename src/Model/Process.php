<?php

namespace Bpm\Model;

use RuntimeException;

class Process extends Element
{
    protected $version;
    protected $elements = [];

    public function getElementsByClassName($className)
    {
        $res = [];
        foreach ($this->elements as $element) {
            if (is_a($element, $className)) {
                $res[] = $element;
            }
        }
        return $res;
    }

    public function getActors()
    {
        return $this->getElementsByClassName(Actor::class);
    }

    public function getElementById($id)
    {
        if (!isset($this->elements[$id])) {
            throw new RuntimeException("No such element id: " . $id);
        }
        return $this->elements[$id];
    }

    public function getActorByName($name)
    {
        foreach ($this->getActors() as $actor) {
            if ($actor->getName()==$name) {
                return $actor;
            }
        }
        throw new RuntimeException("No such actor name: " . $name);
    }

    public function addElement(Element $element)
    {
        $this->elements[$element->getId()] = $element;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function getTransitions()
    {
        return $this->getElementsByClassName(Transition::class);
    }

    public function getTasks()
    {
        return $this->getElementsByClassName(Task::class);
    }
}
