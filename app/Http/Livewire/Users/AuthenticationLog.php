<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Agent\Agent;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as Log;

class AuthenticationLog extends DataTableComponent
{
    // public string $defaultSortColumn = 'login_at';
    // public string $defaultSortDirection = 'desc';
    public string $tableName = 'authentication-log-table';

    protected $model = User::class;
    public User $user;

    public function configure(): void
    {
        $this->setDefaultSort('login_at', 'desc');
    }

    public function mount(User $user)
    {
        if (! auth()->user() || ! auth()->user()->isAdmin()) {
            $this->redirectRoute('dashboard');
        }

        $this->user = $user;
    }

    public function columns(): array
    {
        return [
            Column::make('IP Address', 'ip_address')
                ->searchable(),
            Column::make('Browser', 'user_agent')
                ->searchable()
                ->format(function($value) {
                    $agent = tap(new Agent, fn($agent) => $agent->setUserAgent($value));
                    return $agent->platform() . ' - ' . $agent->browser();
                }),
            Column::make('Location')
                ->searchable(function (Builder $query, $searchTerm) {
                    $query->orWhere('location->city', 'like', '%'.$searchTerm.'%')
                        ->orWhere('location->state', 'like', '%'.$searchTerm.'%')
                        ->orWhere('location->state_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('location->postal_code', 'like', '%'.$searchTerm.'%');
                })
                ->format(fn ($value) => $value && $value['default'] === false ? $value['city'] . ', ' . $value['state'] : '-'),
            Column::make('Login At')
                ->sortable()
                ->format(fn($value) => $value ? timezone()->convertToLocal($value) : '-'),
            Column::make('Login Successful')
                ->sortable()
                ->format(fn($value) => $value === true ? 'Yes' : 'No'),
            Column::make('Logout At')
                ->sortable()
                ->format(fn($value) => $value ? timezone()->convertToLocal($value) : '-'),
            Column::make('Cleared By User')
                ->sortable()
                ->format(fn($value) => $value === true ? 'Yes' : 'No'),
        ];
    }

    public function query(): Builder
    {
        return Log::query()
            ->where('authenticatable_type', User::class)
            ->where('authenticatable_id', $this->user->id);
    }
}
