<?php

namespace Nexly\Events;

use ReflectionFunction;
use WeakReference;

class EventReflected
{
    /**
     * @param WeakReference<object>|null $instanceRef
     */
    public function __construct(
        private readonly \Closure           $closure,
        private readonly ReflectionFunction $ref,
        private readonly bool               $isStatic,
        private readonly ?WeakReference     $instanceRef,
        private readonly ?int               $instanceId,
        private readonly int                $priority,
        private readonly bool               $handleCancelled,
    ) {
    }

    /**
     * @return \Closure
     */
    public function getClosure(): \Closure
    {
        return $this->closure;
    }

    /**
     * @return ReflectionFunction
     */
    public function getRef(): ReflectionFunction
    {
        return $this->ref;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @return WeakReference<object>|null
     */
    public function getInstanceRef(): ?WeakReference
    {
        return $this->instanceRef;
    }

    /**
     * @return int|null
     */
    public function getInstanceId(): ?int
    {
        return $this->instanceId;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function isHandleCancelled(): bool
    {
        return $this->handleCancelled;
    }
}
