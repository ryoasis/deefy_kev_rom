<?php

namespace loader;

class ClassLoader
{
    private string $prefix;
    private string $dir;

    public function __construct(string $pre, string $dir)
    {
        $this->prefix = rtrim($pre, '\\') . '\\';
        $this->dir = rtrim($dir, '/') . '/';
    }

    public function loadClass(string $classname): void
    {
        $len = strlen($this->prefix);
        if (strpos($classname, $this->prefix) !== 0) {
            return;
        }
        $relativeClass = substr($classname, $len);
        $file = $this->dir . str_replace('\\', '/', $relativeClass) . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }
}