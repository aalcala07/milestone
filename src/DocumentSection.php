<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;

class DocumentSection extends Model
{

    protected $table = "milestone_document_sections";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'document_template_section_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function document()
    {
        return $this->belongsTo(\Milestone\Document::class);
    }

    public function templateSection()
    {
        return $this->belongsTo(\Milestone\DocumentTemplateSection::class, 'document_template_section_id');
    }

    public function fields()
    {
        return $this->hasMany(\Milestone\DocumentSectionField::class);
    }
}
