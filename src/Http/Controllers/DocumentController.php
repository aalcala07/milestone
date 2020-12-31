<?php

namespace Milestone\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Milestone\Document;
use Milestone\DocumentGroup;

class DocumentController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $documents = Document::where('user_id', auth()->user()->id)->with('template')->get();

        // $groups = DocumentGroup::where('user_id', auth()->user()->id)->with('documents', 'documents.template')->get();
        $groups = DocumentGroup::groupDocumentsByYear();
        // dd($documents->first()->template);
        // echo "<pre>" . $groups->toJson(JSON_PRETTY_PRINT) . "</pre>"; die;

        return view('milestone::documents.index', compact('groups'));

        $groups = collect(
            [
                [
                    'type' => 'Journals',
                    'years' => [
                        [
                            'year' => 2020,
                            'documents' => [
                                [
                                    'title' => 'Journal 1',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2020'
                                ],
                                [
                                    'title' => 'Journal 2',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2020'
                                ],
                                [
                                    'title' => 'Journal 3',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2020'
                                ],
                            ]
                        ],
                        [
                            'year' => 2019,
                            'documents' => [
                                [
                                    'title' => 'Journal 1',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2019'
                                ],
                                [
                                    'title' => 'Journal 2',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2019'
                                ],
                                [
                                    'title' => 'Journal 3',
                                    'body' => 'Journal body text',
                                    'date' => '12/12/2019'
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'type' => 'Notes',
                    'years' => [
                        [
                            'year' => 2020,
                            'documents' => [
                                [
                                    'title' => 'Note 1',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2020'
                                ],
                                [
                                    'title' => 'Note 2',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2020'
                                ],
                                [
                                    'title' => 'Note 3',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2020'
                                ],
                            ]
                        ],
                        [
                            'year' => 2019,
                            'documents' => [
                                [
                                    'title' => 'Note 1',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2019'
                                ],
                                [
                                    'title' => 'Note 2',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2019'
                                ],
                                [
                                    'title' => 'Note 3',
                                    'body' => 'Note body text',
                                    'date' => '12/12/2019'
                                ],
                            ]
                        ],
                    ]
                ]
            ]);
        return view('milestone::documents.index', compact('groups'));
    }

    public function sections(Request $request, Document $document)
    {
        return response()->json($document->load(['sections', 'sections.templateSection', 'sections.fields']));
    }

}
