<?php
namespace Milestone\Database\Seeders;

use Illuminate\Database\Seeder;
use Milestone\Document;
use Milestone\DocumentGroup;
use Milestone\DocumentSection;
use Milestone\DocumentSectionField;
use Milestone\DocumentTemplate;
use Milestone\DocumentTemplateSection;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 
         * 1. Create document group
         * 2. Create document template
         * 3. Create document template sections
         * 4. Create document
         * 5. Create document sections
         * 6. Create document section fields
         * 
         */

        DocumentGroup::insert([
            [
                'name' => 'Journals',
                'user_id' => 1
            ]
        ]);

        DocumentTemplate::insert([
            [
                'name' => 'Markdown Journal Template',
                'auto_title' => 'Journal {date}',
                'use_publish_date' => false,
                'document_group_id' => 1,
                'user_id' => 1
            ],
            [
                'name' => 'Sectioned Journal Template (2021)',
                'auto_title' => 'Journal {date}',
                'use_publish_date' => false,
                'document_group_id' => 1,
                'user_id' => 1
            ]
        ]);

        DocumentTemplateSection::insert([
            [
                'name' => 'Markdown',
                'description' => 'Free-form markdown journal section.',
                'document_template_id' => 1,
                'document_template_section_type' => 'markdown',
                'user_id' => 1
            ],
            [
                'name' => 'Text Section',
                'description' => 'Section 1 of journal.',
                'document_template_id' => 2,
                'document_template_section_type' => 'text',
                'user_id' => 1
            ],
            [
                'name' => 'Markdown Section',
                'description' => 'Section 2 of journal.',
                'document_template_id' => 2,
                'document_template_section_type' => 'markdown',
                'user_id' => 1
            ]
        ]);

        Document::insert([
            [
                'title' => null,
                'document_template_id' => 1,
                'publish_date' => null,
                'document_group_id' => 1,
                'user_id' => 1,
                'created_at' => '2019-12-01 00:00:00'
            ],
            [
                'title' => null,
                'document_template_id' => 1,
                'publish_date' => null,
                'document_group_id' => 1,
                'user_id' => 1,
                'created_at' => '2019-12-02 00:00:00'
            ],
            [
                'title' => null,
                'document_template_id' => 2,
                'publish_date' => null,
                'document_group_id' => 1,
                'user_id' => 1,
                'created_at' => '2020-12-03 00:00:00'
            ],
            [
                'title' => null,
                'document_template_id' => 2,
                'publish_date' => null,
                'document_group_id' => 1,
                'user_id' => 1,
                'created_at' => '2020-12-04 00:00:00'
            ]
        ]);

        DocumentSection::insert([
            [
                'document_id' => 1,
                'document_template_section_id' => 1,
                'user_id' => 1
            ],
            [
                'document_id' => 2,
                'document_template_section_id' => 1,
                'user_id' => 1
            ],
            [
                'document_id' => 3,
                'document_template_section_id' => 2,
                'user_id' => 1
            ],
            [
                'document_id' => 3,
                'document_template_section_id' => 3,
                'user_id' => 1
            ],
            [
                'document_id' => 4,
                'document_template_section_id' => 2,
                'user_id' => 1
            ],
            [
                'document_id' => 4,
                'document_template_section_id' => 3,
                'user_id' => 1
            ],
        ]);

        DocumentSectionField::insert([
            [
                'document_id' => 1,
                'document_section_id' => 1,
                'content' => "## Markdown Title Here \n\nText of the document goes here.",
                'user_id' => 1
            ],
            [
                'document_id' => 2,
                'document_section_id' => 2,
                'content' => "## Markdown Title Here \n\nText of the document goes here.",
                'user_id' => 1
            ],
            [
                'document_id' => 3,
                'document_section_id' => 3,
                'content' => "Plain text section content\n\nNew paragraph.",
                'user_id' => 1
            ],
            [
                'document_id' => 3,
                'document_section_id' => 4,
                'content' => "## Markdown Title Here \n\nText of the document goes here.",
                'user_id' => 1
            ],
            [
                'document_id' => 4,
                'document_section_id' => 5,
                'content' => "Plain text section content\n\nNew paragraph.",
                'user_id' => 1
            ],
            [
                'document_id' => 4,
                'document_section_id' => 6,
                'content' => "## Markdown Title Here \n\nText of the document goes here.",
                'user_id' => 1
            ],
        ]);
    }
}