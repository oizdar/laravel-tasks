<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

#[OA\Schema(
    required: ['title', 'completed'],
    properties: [
        new OA\Property(property: 'id', type: 'string', readOnly: true, example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Example task title'),
        new OA\Property(property: 'description', type: 'string', example: 'Example task description', nullable: true),
        new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2025-02-25', nullable: true),
        new OA\Property(property: 'completed', type: 'boolean', example: true, nullable: false),
        new OA\Property(property: 'created_at', ref: '#/components/schemas/BaseModel/properties/created_at'),
        new OA\Property(property: 'updated_at', ref: '#/components/schemas/BaseModel/properties/updated_at'),
        new OA\Property(property: 'deleted_at', ref: '#/components/schemas/BaseModel/properties/deleted_at'),
    ],
    xml: new OA\Xml(name: "Task")
)]
class Task extends Model
{
    use HasFactory;
    use LogsActivity;

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

    public function getActivitiesAttribute(): int
    {
        return Activity::where('subject_type', '=', Task::class)
            ->where('subject_id', '=', $this->id)
            ->count();
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
        if (!$filters) {
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

        if ($filters['due_date']['from'] ?? false) {
            $builder->where('due_date', '>=', new \DateTime($filters['due_date']['from']));
        }

        if ($filters['due_date']['to'] ?? false) {
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
        if (!$sort) {
            return $builder;
        }

        if ($sort['order_by']['completed'] ?? false) {
            $builder->orderBy('completed', $sort['order_by']['completed']);
        }

        if ($sort['order_by']['due_date'] ?? false) {
            $builder->orderBy('due_date', $sort['order_by']['completed']);
        }

        return $builder;
    }
}
