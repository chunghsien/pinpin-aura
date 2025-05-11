<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\Node;

/**
 * @desc 新增Command或Livewirem元件時更新
 */
class PackageClassMapperManger
{
    protected $package_org;
    protected $package_name;
    protected $basePath;

    public function __construct($package_org, $package_name)
    {
        $this->package_org = $package_org;
        $this->package_name = $package_name;
        $this->basePath = base_path("packages/{$package_org}/{$package_name}");
        if (!is_dir($this->basePath)) {
            File::makeDirectory($this->basePath, 0755, true);
        }
    }
    protected function getClassFullNameFromFile(string $filePath): ?string
    {
        $code = file_get_contents($filePath);
        $parser = (new ParserFactory)->createForHostVersion();
        $ast = $parser->parse($code);

        $traverser = new NodeTraverser();
        $visitor = new class extends NodeVisitorAbstract {
            public string $namespace = '';
            public string $className = '';

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Stmt\Namespace_) {
                    $this->namespace = implode('\\', $node->name->getParts());
                }

                if ($node instanceof Node\Stmt\Class_) {
                    $this->className = $node->name->name;
                }
            }
        };

        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        if ($visitor->className) {
            return $visitor->namespace
                ? $visitor->namespace . '\\' . $visitor->className
                : $visitor->className;
        }

        return null;
    }
    public function scanAndSave(string $type)
    {
        $filesMapper = [
            'command' => 'src/Commands/*.php',
        ];
        $fileOri = $filesMapper[$type];
        $phpFiles = File::glob("{$this->basePath}/{$fileOri}");
        //$map = [];
        $arrayBody = "";
        $useClass = [null];
        foreach ($phpFiles as $filePath) {
            $class = $this->getClassFullNameFromFile($filePath);

            if ($class) {
                $mapVal = $filePath;
                $useClass[] = "use {$class};";
                if ($type == 'livewire') {
                    $mapVal = explode("\\", $class);
                    $mapVal = array_filter($mapVal, function ($part) {
                        return false === array_search(
                            $part,
                            [Str::studly($this->package_org), 'Http', 'Livewire']
                        );
                    });
                    $mapVal = array_map(function ($part) {
                        $part = preg_replace('/^themes/i', '', $part);
                        return strtolower(Str::kebab($part));
                    }, $mapVal);
                    $mapVal = array_values($mapVal);
                    $mapVal = implode('.', $mapVal);
                }
                $classArr = explode("\\", $class);
                $baseClass = end($classArr);
                $arrayBody .= "    {$baseClass}::class => \"{$mapVal}\",\n";
            }
        }
        $useClass[] = null;
        $useClassString = implode("\n", $useClass);
        $arrayBody = preg_replace('/\,$/', '', $arrayBody);
        $output = "<?php\n{$useClassString}\nreturn [\n{$arrayBody}];\n";
        $savePath = "{$this->basePath}/config/class_mapper/{$type}.php";
        File::put($savePath, $output);
        return $this;
    }
}
