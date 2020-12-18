<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplateSection extends Model
{

    protected $table = "milestone_document_template_sections";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'document_template_id',
        'document_template_section_type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function template()
    {
        return $this->belongsTo(\Milestone\DocumentTemplate::class);
    }
}
