<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @OA\Schema(
 *     @OA\Xml(name="Task"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="title", type="string",  example="Example task title"),
 *     @OA\Property(property="description", type="text",  example="Example task description", nullable=true),
 *     @OA\Property(property="due_date", type="string", format="date", example="2019-02-25"),
 *     @OA\Property(property="completed", type="boolean", example=false),
 *     @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *     @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 */
class Task extends Model
{
    use HasFactory, LogsActivity;

    public User $user;
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'completed'
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'description' => null,
        'due_date' => null,
        'completed' => false,
        'reminded' => false,
    ];


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * @param Builder $builder
     * @param array<string, mixed> $filters
     * @return Builder
     * @throws \Exception
     */
    public function scopeFilter(Builder $builder, array $filters = []): Builder
    {
        if(!$filters) {
            return $builder;
        }

        switch($filters['completed'] ?? false) {
            case 'false':
                $builder->where('completed', false);
                break;
            case 'true':
                 $builder->where('completed', true);
                 break;
        }

        if($filters['due_date']['from'] ?? false) {
            $builder->where('due_date', '>=', new \DateTime($filters['due_date']['from']));
        }

        if($filters['due_date']['to'] ?? false) {
            $builder->where('due_date', '<=', new \DateTime($filters['due_date']['to']));
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array<string, array<string, string>> $sort
     * @return Builder
     */
    public function scopeSort(Builder $builder, array $sort = []): Builder
    {
        if(!$sort) {
            return $builder;
        }

        if($sort['order_by']['completed'] ?? false) {
            $builder->orderBy('completed', $sort['order_by']['completed']);
        }

        if($sort['order_by']['due_date'] ?? false) {
            $builder->orderBy('due_date', $sort['order_by']['completed']);
        }

        if($sort['order_by']['activities'] ?? false) {
//TODO:            $builder->orderBy('activities', $sort['order_by']['activities']);
        }

        return $builder;
    }

}
