<?php
namespace App\Services;

use phpDocumentor\Reflection\DocBlockFactory;

class PhpDocScanner
{
    protected $factory;

    public function __construct()
    {
        $this->factory = DocBlockFactory::createInstance();
    }

    public function scanDirectory(string $path): array
    {
        $results = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                preg_match_all('#/\*\*(.*?)\*/#s', $content, $matches);

                foreach ($matches[1] as $docBlock) {
                    $doc = $this->factory->create('/**' . $docBlock . '*/');

                    $tags = [];
                    foreach ($doc->getTags() as $tag) {
                        $tags[] = [
                            'name' => $tag->getName(),
                            'content' => (string) $tag,
                        ];
                    }

                    $results[] = [
                        'file' => $file->getFilename(),
                        'summary' => $doc->getSummary(),
                        'description' => (string) $doc->getDescription(),
                        'tags' => $tags,
                    ];
                }


            }
        }

        return $results;
    }

}
