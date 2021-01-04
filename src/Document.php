<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{

    protected $table = "milestone_documents";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'document_template_id',
        'publish_date',
        'document_group_id',
        'user_id'
    ];

    protected $appends = ['display_title', 'display_date', 'display_date_relative', 'text_preview'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function template()
    {
        return $this->belongsTo(\Milestone\DocumentTemplate::class, 'document_template_id');
    }

    public function sections()
    {
        // return ['one'];
        return $this->hasMany(\Milestone\DocumentSection::class);
    }

    protected function getDisplayTitleAttribute()
    {
        return $this->template->auto_title ? $this->getAutoTitle() : $this->title;
    }

    protected function getDisplayDatetime()
    {
        return $this->publish_date ? $this->publish_date : $this->created_at;
    }

    protected function getDisplayDateAttribute()
    {
        
        return (new Carbon($this->getDisplayDatetime()))->toDateString();
    }

    protected function getDisplayDateRelativeAttribute()
    {
        $minutes = Carbon::now()->diffInMinutes($this->getDisplayDatetime());
        if (Carbon::now() > new Carbon($this->getDisplayDatetime())) {
            return Carbon::now()->subMinutes($minutes)->diffForHumans();  
        } else {
            return Carbon::now()->addMinutes($minutes)->diffForHumans();  
        }
    }

    protected function getTextPreviewAttribute()
    {
        return "This is a placeholder for the preview text...";
    }

    protected function getAutoTitle()
    {
        if (!$this->template->auto_title) {
            return '';
        }

        $title = str_replace('{date}', $this->display_date, $this->template->auto_title);
        return $title;
    }
}
