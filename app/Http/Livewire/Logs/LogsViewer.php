<?php

namespace App\Http\Livewire\Logs;

use App\Models\Bot;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;
use SplFileInfo;

class LogsViewer extends Component
{
    public Bot $bot;

    public $file=0;
    public $page=1;
    public $total;
    public $perPage = 100;
    public $paginator;
    public $title = 'Logs viewer';

    protected $queryString=['page'];

    public function render()
    {
        if ($this->bot->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        $files = $this->getLogFiles();

        $log=collect(file($files[$this->file]->getPathname(), FILE_IGNORE_NEW_LINES));

        $this->total = intval(floor($log->count() / $this->perPage)) + 1;

        $log = $log->slice(($this->page - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.logs.logs-viewer')
              ->layoutData([
                  'title' => $this->title,
              ])
              ->withFiles($files)
              ->withLog($log);
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
        $this->page = $page;
    }

    public function updatingFile()
    {
        $this->page = $this->total;
    }
}
