<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    
    protected $table = "milestone_document_templates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'auto_title',
        'use_publish_date',
        'document_group_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function group()
    {
        return $this->belongsTo(\Milestone\DocumentGroup::class);
    }

    public function sections()
    {
        return $this->hasMany(\Milestone\DocumentTemplateSection::class);
    }
}
