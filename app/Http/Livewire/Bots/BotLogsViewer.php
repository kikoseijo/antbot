<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;
use SplFileInfo;

class BotLogsViewer extends Component
{
    public Bot $bot;

    public $file=0;
    public $page=1;
    public $total;
    public $perPage = 200;
    public $paginator;
    public $name;
    public $title = 'Logs viewer';

    protected $queryString=['page'];

    public function mount($name = '')
    {
        $this->name = $name;
    }

    public function render()
    {
        if ($this->bot->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized');
        }
        $files = $this->getLogFiles();

        $log = collect(file($files[$this->file]->getPathname(), FILE_IGNORE_NEW_LINES));

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
        $logs_path = config('antbot.paths.passivbot_logs');
        $directory =  "{$logs_path}/{$this->bot->exchange_id}/";

        return collect(File::allFiles($directory))
            ->sortBy(function (SplFileInfo $file) {
                return $file->getFilename();
            })->values();
    }

    public function deleteLogsFile()
    {
        $logs_path = config('antbot.paths.passivbot_logs');
        $files = $this->getLogFiles();
        $file_name = $files[$this->file]->getFilename();
        $file_path =  "{$logs_path}/{$this->bot->exchange_id}/";

        $deletedFile = File::delete($file_path . $file_name);
        if ($deletedFile == null) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => $file_name . ' deleted succesfully.'
            ]);;
        }
        $this->page = 0;
    }

    public function truncateAllFiles()
    {
        $logs_path = config('antbot.paths.passivbot_logs');
        $files = $this->getLogFiles();
        foreach ($files as $file) {
            $this->exeTruncateFile($logs_path, $file->getFilename());
        }

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'All logs truncated succesfully.'
        ]);;
    }

    public function truncateFile()
    {
        $logs_path = config('antbot.paths.passivbot_logs');
        $files = $this->getLogFiles();
        $file_name = $files[$this->file]->getFilename();
        $this->exeTruncateFile($logs_path, $file_name);

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => $file_name . ' truncated succesfully.'
        ]);;
    }

    protected function exeTruncateFile($logs_path, $file_name)
    {
        $file_path =  "{$logs_path}/{$this->bot->exchange_id}/{$file_name}";
        $cmd = "echo \"\" > {$file_path}";

        exec($cmd, $op);
    }

    public function refreshLog($url)
    {
        return redirect($url);
    }

    public function goto($page)
    {
        $this->page = $page;
    }

    public function updatingFile()
    {
        $this->page = 1;
    }
}
