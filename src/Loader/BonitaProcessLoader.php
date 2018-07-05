<?php

namespace Bpm\Loader;

use Bpm\Model\Process;
use Bpm\Model\Actor;
use Bpm\Model\Task;
use Bpm\Model\Transition;

use SimpleXMLElement;

class BonitaProcessLoader
{
    public function loadFile($filename)
    {
       if (!file_exists($filename)) {
           throw new RuntimeException("File not found: " . $filename);
       }
       $basePath = dirname($filename);
       $xml = file_get_contents($filename);
       $root = simplexml_load_string($xml);
       $process = $this->loadXml($root);
       return $process;
    }

    public function loadXml(SimpleXMLElement $root)
    {
        $process = new Process();
        $process->setName((string)$root['name']);
        $process->setVersion((string)$root['version']);
        $process->setDescription((string)$root->description);
        $process->setDisplayDescription((string)$root->displayDescription);

        foreach ($root->actors->actor as $actorNode) {
            $actor = new Actor();
            $actor->setId((string)$actorNode['id']);
            $actor->setName((string)$actorNode['name']);
            if ($actorNode['initiator']=='true') {
                $actor->setIsInitiator(true);
            }
            $actor->setDescription((string)$actorNode->description);
            $process->addElement($actor);
        }

        foreach ($root->flowElements->userTask as $taskNode) {
            $this->loadTaskNode($process, $taskNode, $taskNode->getName());
        }
        foreach ($root->flowElements->gateway as $taskNode) {
            $this->loadTaskNode($process, $taskNode, $taskNode->getName());
        }
        foreach ($root->flowElements->startEvent as $taskNode) {
            $this->loadTaskNode($process, $taskNode, $taskNode->getName());
        }
        foreach ($root->flowElements->endEvent as $taskNode) {
            $this->loadTaskNode($process, $taskNode, $taskNode->getName());
        }
        foreach ($root->flowElements->callActivity as $taskNode) {
            $this->loadTaskNode($process, $taskNode, $taskNode->getName());
        }
        foreach ($root->flowElements->transitions->transition as $transitionNode) {
            $this->loadTransitionNode($process, $transitionNode);
        }


        return $process;
    }

    public function loadTransitionNode(Process $process, $transitionNode, $type='Undefined')
    {
        $transition = new Transition();
        $source = $process->getElementById((string)$transitionNode['source']);
        $transition->setSource($source);

        $target = $process->getElementById((string)$transitionNode['target']);
        $transition->setTarget($target);

        $transition->setId((string)$transitionNode['id']);
        $transition->setName((string)$transitionNode['name']);
        $process->addElement($transition);
    }

    public function loadTaskNode(Process $process, $taskNode, $type='Undefined')
    {
        $task = new Task();
        $task->setType($type);
        $task->setId((string)$taskNode['id']);
        $task->setName((string)$taskNode['name']);
        $task->setDescription((string)$taskNode->description);

        if ((string)$taskNode->displayDescription['expressionType'] == 'TYPE_CONSTANT') {
            $task->setDisplayDescription((string)$taskNode->displayDescription->content);
        }


        $actorName = (string)$taskNode['actorName'];
        if ($actorName) {
            $actor = $process->getActorByName($actorName);
            $task->setActor($actor);
        }
        $process->addElement($task);
    }
}
