<?php

namespace Milestone\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Milestone\Document;
use Milestone\DocumentGroup;
use Milestone\DocumentTemplate;
use Milestone\DocumentSection;
use Milestone\DocumentSectionField;
use Milestone\UserSettings;

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

        // $ui = json_encode([
        //     'openTabs' => []
        // ]);
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        $ui = $settings->ui_state ? json_decode($settings->ui_state, 1) : [];
        $ui = isset($ui['documents']) ? $ui['documents'] : [];
        
        $ui['openTabs'] = isset($ui['openTabs']) ? $ui['openTabs'] : [];
        $ui['selectedGroup'] = isset($ui['selectedGroup']) ? $ui['selectedGroup'] : null;
        $ui['selectedYear'] = isset($ui['selectedYear']) ? $ui['selectedYear'] : null;

        // dd($ui);
        $ui = json_encode($ui);
        // $ui = json_encode([
        //     'openTabs' => []
        // ]);


        return view('milestone::documents.index', compact('groups', 'ui'));
    }

    public function sections(Request $request, Document $document)
    {
        return response()->json($document->load(['sections', 'sections.templateSection', 'sections.fields']));
    }

    public function templates(Request $request)
    {
        $templates = DocumentTemplate::where('user_id', auth()->user()->id)->get();
        return response()->json($templates);
    }

    public function create(Request $request)
    {
        $template = DocumentTemplate::find($request->input('template_id'));
        $document = new Document();
        $document->user_id = auth()->user()->id;
        $document->document_template_id = $template->id;
        $document->document_group_id = $template->group->id;
        $document->save();

        // Add sections
        foreach ($template->sections as $section) {
            $document->sections()->save(new DocumentSection([
                'document_template_section_id' => $section->id,
                'user_id' => auth()->user()->id
            ]));
        }

        // Add fields
        // TODO: Add correct fields for each section based on type
        $document->sections->each( function ($section) use ($document) {
            $section->fields()->save(new DocumentSectionField([
                'document_id' => $document->id,
                'content' => '',
                'data' => null,
                'user_id' => $document->user_id
            ]));
        });

        return response()->json($document);
    }

    public function updateTitle(Request $request, Document $document)
    {
        $document->title = $request->input('title');
        $result = $document->save();
        return response()->json($result ? $document : $result);
    }

    public function updateDate(Request $request, Document $document)
    {
        $document->publish_date = $request->input('publish_date');
        $result = $document->save();
        return response()->json($result ? $document : $result);
    }

    public function updateField(Request $request, DocumentSectionField $field)
    {
        if ($request->has('content')) {
            $field->content = $request->input('content');
        }
        if ($request->has('data')) {
            $field->data = $request->input('data');
        }
        $result = $field->save();

        return response()->json($result ? $field : $result);
    }

    public function destroy(Request $request, Document $document)
    {
        $result = $document->delete();
        return response()->json($result);
    }

    public function updateUi(Request $request)
    {
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        $uiState = $settings->ui_state ? json_decode($settings->ui_state, 1) : [];
        $uiState['documents'] = $request->input('ui');

        foreach ($uiState['documents']['openTabs'] as &$tab) {
            if ($tab['type'] === 'document') {
                unset($tab['content']['sections']);
            }
        }
        
        $settings->ui_state = json_encode($uiState);
        $result = $settings->save();

        return response()->json($result);
    }
}
