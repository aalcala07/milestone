<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DocumentGroup extends Model
{

    protected $table = "milestone_document_groups";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_order',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function templates()
    {
        return $this->hasMany(\Milestone\DocumentTemplate::class);
    }

    public function documents()
    {
        return $this->hasMany(\Milestone\Document::class);
    }

    public static function groupDocumentsByYear()
    {
        $groups = self::where('user_id', auth()->user()->id)->with('documents', 'documents.template')->get();

        $groupsWithYears = collect();

        foreach ($groups as $group) {
            $years = [];
            foreach ($group->documents as $document) {
                $year = (new Carbon($document->display_date))->year;
                if (isset($years[$year])) {
                    $years[$year]['documents'][] = $document;
                } else {
                    $years[$year] = [
                        'year' => $year,
                        'documents' => [$document]
                    ];
                }
            }
            krsort($years);
            $group->years = array_values($years);
            $groupsWithYears->push($group);
        }

        return $groupsWithYears;
    }

}
