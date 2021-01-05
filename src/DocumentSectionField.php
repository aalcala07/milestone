<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;

class DocumentSectionField extends Model
{

    protected $table = "milestone_document_section_fields";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'document_section_id',
        'content',
        'user_id'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function setDataAttribute($data)
	{
	    $this->attributes['data'] = json_encode($data);
	}

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function document()
    {
        return $this->belongsTo(\Milestone\Document::class);
    }

    public function section()
    {
        return $this->hasMany(\Milestone\DocumentSection::class);
    }
}
