<?php

namespace Adirsolomon\CoralogixPackage;

use Carbon\Carbon;

class Log
{
    /**
     * @var Carbon
     */
    private Carbon $time;

    /**
     * @var int
     */
    private int $severity;

    /**
     * @var string
     */
    private string $text;

    /**
     * @var string|null
     */
    private ?string $className;

    /**
     * @var string|null
     */
    private ?string $methodName;

    /**
     * @var string|null
     */
    private ?string $threadId;

    /**
     * @var string|null
     */
    private ?string $category;

    /**
     * @param Carbon $time
     * @param int $severity
     * @param string $text
     * @param string|null $className
     * @param string|null $methodName
     * @param string|null $threadId
     * @param string|null $category
     */
    public function __construct(
        Carbon  $time,
        int     $severity,
        string  $text,
        ?string $className = null,
        ?string $methodName = null,
        ?string $threadId = null,
        ?string $category = null
    )
    {
        $this->time = $time;
        $this->severity = $severity;
        $this->text = $text;
        $this->className = $className;
        $this->methodName = $methodName;
        $this->threadId = $threadId;
        $this->category = $category;
    }

    /**
     * @return Carbon
     */
    public function getTime(): Carbon
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getSeverity(): int
    {
        return $this->severity;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @return string|null
     */
    public function getMethodName(): ?string
    {
        return $this->methodName;
    }

    /**
     * @return string|null
     */
    public function getThreadId(): ?string
    {
        return $this->threadId;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }
}