<?php
namespace Bpjs\Core;

class Response
{
    protected mixed $content;
    protected int $status;

    public function __construct(mixed $content, int $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        echo $this->content;
    }

    public static function view(string $path, int $status = 200): static
    {
        ob_start();
        include $path;
        $content = ob_get_clean();

        return new static($content, $status);
    }
}
