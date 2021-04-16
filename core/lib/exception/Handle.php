<?php

declare(strict_types=1);


namespace Anng\lib\exception;

use Throwable;

class Handle
{
    private array $ignoreReport = [
        RouteException::class
    ];


    public function default(Throwable $th)
    {
        return $th->getMessage();
    }

    public function render(Throwable $th)
    {
        foreach ($this->ignoreReport as $report) {
            if ($th instanceof $report) {
                return $th->getMessage();
            }
        }

        return $this->default($th);
    }
}
