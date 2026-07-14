<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Čte Vite manifest — den 9 (build pipeline assets → public/build).
 */
final class ViteAssetMapper
{
    /** @var array<string, array{file: string, css?: list<string>}>|null */
    private ?array $manifest = null;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        #[Autowire('%kernel.environment%')]
        private string $environment,
        #[Autowire('%env(bool:VITE_DEV_SERVER)%')]
        private bool $devServer,
    ) {
    }

    public function isDevServer(): bool
    {
        return $this->devServer && $this->environment === 'dev';
    }

    public function js(string $entry): string
    {
        if ($this->isDevServer()) {
            return 'http://localhost:5173/'.$entry;
        }

        $item = $this->entry($entry);

        return '/build/'.$item['file'];
    }

    /**
     * @return list<string>
     */
    public function css(string $entry): array
    {
        if ($this->isDevServer()) {
            return [];
        }

        $item = $this->entry($entry);
        $css = $item['css'] ?? [];

        return array_map(static fn (string $file): string => '/build/'.$file, $css);
    }

    /**
     * @return array{file: string, css?: list<string>}
     */
    private function entry(string $entry): array
    {
        $manifest = $this->loadManifest();
        if (!isset($manifest[$entry])) {
            throw new \RuntimeException(sprintf(
                'Vite entry "%s" not found. Spusť: npm run build',
                $entry,
            ));
        }

        return $manifest[$entry];
    }

    /**
     * @return array<string, array{file: string, css?: list<string>}>
     */
    private function loadManifest(): array
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        $candidates = [
            $this->projectDir.'/public/build/.vite/manifest.json',
            $this->projectDir.'/public/build/manifest.json',
        ];

        foreach ($candidates as $path) {
            if (!is_file($path)) {
                continue;
            }

            /** @var array<string, array{file: string, css?: list<string>}> $data */
            $data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            $this->manifest = $data;

            return $this->manifest;
        }

        throw new \RuntimeException('Vite manifest chybí. Spusť: npm run build v demo/');
    }
}
