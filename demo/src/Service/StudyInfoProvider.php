<?php

namespace App\Service;

final class StudyInfoProvider
{
    public function __construct(
        private string $studyTitle,
        private int $studyDay,
        private string $appEnv,
    ) {
    }

    public function getTitle(): string
    {
        return $this->studyTitle;
    }

    public function getDay(): int
    {
        return $this->studyDay;
    }

    public function getEnvironment(): string
    {
        return $this->appEnv;
    }

    /**
     * @return array{title: string, day: int, environment: string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->studyTitle,
            'day' => $this->studyDay,
            'environment' => $this->appEnv,
        ];
    }
}
