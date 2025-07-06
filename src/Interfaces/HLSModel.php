<?php

namespace AchyutN\LaravelHLS\Interfaces;

interface HLSModel
{
    public function getVideoPath(): ?string;

    public function setVideoPath(?string $path = null): void;

    public function getHlsPath(): ?string;

    public function setHlsPath(?string $path = null): void;

    public function getProgress(): int;

    public function setProgress(int $progress = 0): void;

    public function getVideoColumn(): string;

    public function getHlsColumn(): string;

    public function getProgressColumn(): string;

    public function getVideoDisk(): string;

    public function getHlsDisk(): string;

    public function getSecretsDisk(): string;

    public function getHLSOutputPath(): string;

    public function getHLSSecretsOutputPath(): string;
}
