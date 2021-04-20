<?php

declare(strict_types=1);


namespace Anng\lib;

use Throwable;

class Exception
{
    private array $ignoreReport = [
        RouteException::class
    ];


    public function default(Throwable $th)
    {
        return $th->getFile() . '|' . $th->getLine() . '|' . $th->getMessage();
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
