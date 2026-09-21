<?php

namespace Nexly\Events;

use Closure;
use ReflectionFunction;
use WeakReference;

readonly class EventReflected
{
    /**
     * @param WeakReference<object>|null $instanceRef
     */
    public function __construct(
        private Closure           $closure,
        private ReflectionFunction $ref,
        private bool               $isStatic,
        private ?WeakReference     $instanceRef,
        private ?int               $instanceId,
        private int                $priority,
        private bool               $handleCancelled,
    ) {
    }

    /**
     * @return Closure
     */
    public function getClosure(): Closure
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
