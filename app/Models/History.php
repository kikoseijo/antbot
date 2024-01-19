<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stringable;

class History extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'changes' => 'array',
        'original' => 'array',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function historicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Create a new action event instance for a resource creation.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forResourceCreate($user, $model)
    {
        return new static([
            'user_id' => $user->getAuthIdentifier(),
            'name' => 'Create',
            'historicable_type' => $model->getMorphClass(),
            'historicable_id' => $model->getKey(),
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->getKey(),
            'original' => null,
            'changes' => array_diff_key($model->attributesToArray(), array_flip($model->getHidden())),
            'status' => 'finished',
            'exception' => '',
        ]);
    }

    /**
     * Create a new action event instance for a resource update.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forResourceUpdate($user, $model)
    {
        return new static([
            'user_id' => $user->getAuthIdentifier(),
            'name' => 'Update',
            'historicable_type' => $model->getMorphClass(),
            'historicable_id' => $model->getKey(),
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->getKey(),
            'changes' => static::hydrateChangesPayload(
                $changes = array_diff_key($model->getDirty(), array_flip($model->getHidden()))
            ),
            'original' => static::hydrateChangesPayload(
                array_intersect_key($model->newInstance()->setRawAttributes($model->getRawOriginal())->attributesToArray(), $changes)
            ),
            'status' => 'finished',
            'exception' => '',
        ]);
    }

    /**
     * Hydrate the changes payuload.
     *
     * @param  array  $attributes
     * @return array
     */
    protected static function hydrateChangesPayload(array $attributes)
    {
        return collect($attributes)
                ->transform(function ($value) {
                    if (is_object($value) && ($value instanceof Stringable || method_exists($value, '__toString'))) {
                        return (string) $value;
                    } elseif (is_object($value) || is_array($value)) {
                        return rescue(function () use ($value) {
                            return json_encode($value);
                        }, $value);
                    }

                    return $value;
                })->all();
    }
}
