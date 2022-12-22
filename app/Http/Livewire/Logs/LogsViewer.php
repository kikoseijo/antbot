<?php

namespace App\Http\Livewire\Logs;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use SplFileInfo;

class LogsViewer extends Component
{
    public Bot $bot;

    public $file=0;
    public $page=1;
    public $total;
    public $perPage = 500;
    public $paginator;

    protected $queryString=['page'];

    public function render()
    {
        $files = $this->getLogFiles();

        $log=collect(file($files[$this->file]->getPathname(), FILE_IGNORE_NEW_LINES));

        $this->total = intval(floor($log->count() / $this->perPage)) + 1;

        $log = $log->slice(($this->page - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.logs.logs-viewer')->withFiles($files)->withLog($log);
    }

    protected function getLogFiles()
    {
        $logs_path = config('antbot.paths.logs_path');
        $directory =  "{$logs_path}/{$this->bot->exchange_id}";

        return collect(File::allFiles($directory))
            ->sortByDesc(function (SplFileInfo $file) {
                return $file->getMTime();
            })->values();
    }

    public function goto($page)
    {
        $this->page=$page;
    }

    public function updatingFile()
    {
        $this->page=1;
    }
}