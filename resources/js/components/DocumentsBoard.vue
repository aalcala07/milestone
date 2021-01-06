<template>
    <div class="d-flex flex-row panel-board">
        <div class="documents-panel documents-left-panel flex-shrink-0 d-flex flex-column">
            <form>
                <div class="form-group">
                    <input name="search" class="form-control" placeholder="Search" >
                </div>
            </form>
            <div class="document-list-view d-flex flex-column">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);" v-on:click="selectedGroup = null; selectedYear = null;">Documents</a></li>
                        <li v-if="selectedGroup" class="breadcrumb-item"><a href="javascript:void(0);" v-on:click="selectedYear = null;">{{ selectedGroup }}</a></li>
                        <li v-if="selectedYear" class="breadcrumb-item">{{ selectedYear }}</li>
                    </ol>
                </nav>
                <ul v-if="selectedGroup && selectedYear" class="documents-list flex-fill">
                    <li v-for="document in documentsInSideNav" class="documents-list-item">
                        <div class="card" v-on:click="openDocument(document)">
                            <div class="card-body">
                                <h5 class="card-title">{{ document.display_title }}</h5>
                                <p class="card-text">{{ document.text_preview }}</p>
                                <p class="card-text"><small>{{ document.display_date_relative }}</small></p>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul v-else class="list-unstyled p-3">
                    <li v-for="item in itemsInSideNav">
                        <a href="javascript:void(0);" v-on:click="handleSideNavItemClick(item)">{{ item.name }}</a></li>
                </ul>
            </div>
            <div class="search-results-view">

            </div>
        </div>
        <div class="documents-panel documents-center-panel flex-grow-1 d-flex flex-column">
            <nav>
                <ul class="panel-tabs">
                    <li v-for="(tab, tabIndex) in openTabs" class="panel-tab" @click.self="activateTab(tabIndex)" v-bind:class="{ active: tab.isActive }">{{ getTabTitle(tab) }} 
                        <a href="javascript:void(0)" @click="closeTab(tabIndex)">
                            <i class="bi bi-x"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg></a>
                    </li>
                    <li class="panel-tab">
                        <a href="javascript:void(0)" @click="showCreateDocumentModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="document-view flex-grow pr-3">
                <div v-if="activeTab && activeTab.type ==='document'">
                    <div class="d-flex flex-row">
                        <h3>{{ activeTab.content.display_title }}</h3>
                        <div class="ml-auto">
                            <div class="dropdown">
                                <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a v-if="!activeTab.content.template.auto_title" class="dropdown-item" href="javascript:void(0)" @click="showRenameDocumentModal = true">Rename</a>
                                    <a class="dropdown-item" href="javascript:void(0)" @click="showChangeDateDocumentModal = true">Change Date</a>
                                    <a class="dropdown-item" href="javascript:void(0)" @click="showDeleteDocumentModal = true">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-for="(section, index) in activeTab.content.sections" :key="section.id">
                        <div v-if="section.template_section.document_template_section_type === 'text'" class="mb-3">
                            <p class="small">{{ section.template_section.name }}</p>
                            <textarea :readonly="activeDocumentSection !== index" @click="activeDocumentSection = index" style="width: 100%; min-height: 300px;" v-model="section.fields[0].content" @input="updateField(section.fields[0])"></textarea>
                        </div>
                        <div v-else-if="section.template_section.document_template_section_type === 'markdown'" class="mb-3">
                            <p class="small">{{ section.template_section.name }}</p>
                            <MarkdownEditor v-model="section.fields[0].content" @input="updateField(section.fields[0])" style="background: #222; padding: 15px" />
                        </div>
                        <div v-else-if="section.template_section.document_template_section_type === 'agenda'" class="mt-3 mb-4">
                            <h3 class="mb-3">{{ section.template_section.name }}</h3>
                            <table v-if="fieldItems(section.fields[0]) && fieldItems(section.fields[0]).length" class="table table-sm table-borderless table-hover mb-3">
                                <tbody>
                                    <tr v-for="(item, itemIndex) in fieldItems(section.fields[0])">
                                        <td style="width: 30px;">{{ itemIndex + 1 }}.</td>
                                        <td><input type="text" v-model="item.name" style="border: none; padding: 2px 10px; width: 100%;" @input="updateDataField(section.fields[0]); $forceUpdate()"></td>
                                        <td align="right" style="width: 30px;">
                                            <div v-if="item.showDelete" style="white-space: nowrap;">
                                                <span class="mr-3">Delete?</span>
                                                <button type="button" class="btn btn-sm btn-danger mr-2" @click="deleteListItem(section.fields[0], itemIndex); item.showDelete = false; $forceUpdate()">Yes</button>
                                                <button type="button" class="btn btn-sm" @click="item.showDelete = false; $forceUpdate()">No</button>
                                            </div>
                                            <a v-else href="javascript:void(0)" @click="item.showDelete = true; $forceUpdate()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>
                                <p>No agenda items.</p>
                            </div>
                            <form class="form-inline mb-4" @submit.prevent>
                                <div class="form-group mr-2">
                                    <input type="text" ref="addAgendaItemInput" class="form-control" placeholder="New agenda item...">
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" @click="addAgendaItem(section.fields[0])">Add</button>
                            </form>
                            <div v-if="fieldItems(section.fields[0])">
                                <div v-for="(item, itemIndex) in fieldItems(section.fields[0])" class="form-group">
                                    <h4>{{ section.fields[0].data.items[itemIndex].name }}</h4>
                                    <textarea class="form-control" :readonly="activeTab.focusFieldIndex !== itemIndex" @click="activeTab.focusFieldIndex = itemIndex" style="min-height: 300px;" v-model="section.fields[0].data.items[itemIndex].content" @input="updateDataField(section.fields[0])"></textarea>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="section.template_section.document_template_section_type === 'links'" class="mb-3">
                            <h3 class="mb-3">{{ section.template_section.name }}</h3>
                            <table v-if="fieldItems(section.fields[0]) && fieldItems(section.fields[0]).length" class="table table-sm table-borderless table-hover mb-3">
                                <tbody>
                                    <tr v-for="(item, itemIndex) in fieldItems(section.fields[0])">
                                        <td style="width: 30px;">{{ itemIndex + 1 }}.</td>
                                        <td>
                                            <div v-if="getItemUIState(item) === 'edit'">
                                                <input type="text" v-model="item.name" style="border: none; padding: 2px 10px; width: 100%;" placeholder="Link name" @input="updateDataField(section.fields[0])">
                                                <input type="text" v-model="item.url" style="border: none; padding: 2px 10px; width: 100%;" placeholder="Link URL" @input="updateDataField(section.fields[0])">
                                                <button type="button" class="btn btn-primary btn-sm" @click="item.uiState = null; $forceUpdate()">Done</button>
                                            </div>
                                            <div v-else>
                                                <a :href="item.url" target="_blank" class="mr-3">{{ item.name }}</a>
                                                <a href="javascript:void(0)" @click="item.uiState = 'edit'; $forceUpdate()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td align="right" style="width: 30px;">
                                            <div v-if="item.uiState === 'delete'" style="white-space: nowrap;">
                                                <span class="mr-3">Delete?</span>
                                                <button type="button" class="btn btn-sm btn-danger mr-2" @click="deleteListItem(section.fields[0], itemIndex); item.uiState = 'delete'; $forceUpdate()">Yes</button>
                                                <button type="button" class="btn btn-sm" @click="item.uiState = null; $forceUpdate()">No</button>
                                            </div>
                                            <a v-else href="javascript:void(0)" @click="item.uiState = 'delete'; $forceUpdate()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>
                                <p>No links.</p>
                            </div>
                            <form class="form-inline mb-4" @submit.prevent>
                                <div class="form-group mr-2">
                                    <input type="text" ref="addLinkItemNameInput" class="form-control mr-2" placeholder="Link name">
                                    <input type="text" ref="addLinkItemUrlInput" class="form-control" placeholder="Link URL">
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" @click="addLinkItem(section.fields[0])">Add</button>
                            </form>
                        </div>
                        <div v-else-if="section.template_section.document_template_section_type === 'list'" class="mb-3">
                            <h3 class="mb-3">{{ section.template_section.name }}</h3>
                            <table v-if="fieldItems(section.fields[0]) && fieldItems(section.fields[0]).length" class="table table-sm table-borderless table-hover mb-3">
                                <tbody>
                                    <tr v-for="(item, itemIndex) in fieldItems(section.fields[0])">
                                        <td style="width: 30px;">{{ itemIndex + 1 }}.</td>
                                        <td><input type="text" v-model="item.name" style="border: none; padding: 2px 10px; width: 100%;" @input="updateDataField(section.fields[0]); $forceUpdate()"></td>
                                        <td align="right" style="width: 30px;">
                                            <div v-if="item.showDelete" style="white-space: nowrap;">
                                                <span class="mr-3">Delete?</span>
                                                <button type="button" class="btn btn-sm btn-danger mr-2" @click="deleteListItem(section.fields[0], itemIndex); item.showDelete = false; $forceUpdate()">Yes</button>
                                                <button type="button" class="btn btn-sm" @click="item.showDelete = false; $forceUpdate()">No</button>
                                            </div>
                                            <a v-else href="javascript:void(0)" @click="item.showDelete = true; $forceUpdate()">
                                                <i class="bi bi-x"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>
                                <p>No list items.</p>
                            </div>
                            <form class="form-inline mb-4" @submit.prevent>
                                <div class="form-group mr-2">
                                    <input type="text" ref="addListItemInput" class="form-control" placeholder="New list item...">
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" @click="addListItem(section.fields[0])">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="documents-panel documents-right-panel flex-shrink-0 d-flex flex-column">
            <nav class="flex-shrink-0 flex-grow-0">
                <ul class="panel-tabs">
                    <li class="panel-tab active">Annotations</li>
                    <li class="panel-tab">Audits</li>
                </ul>
            </nav>
            <ul class="annotations-list flex-grow-1">
                <li class="annotations-list-item">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">The annotation text goes here. The annotation text goes here. <br>The annotation text goes here. </p>
                            <p class="card-text"><small>3 days ago</small></p>
                        </div>
                    </div>
                </li>
                <li class="annotations-list-item">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">The annotation text goes here. The annotation text goes here. <br>The annotation text goes here. </p>
                            <p class="card-text"><small>3 days ago</small></p>
                        </div>
                    </div>
                </li>
                <li class="annotations-list-item">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">The annotation text goes here. The annotation text goes here. <br>The annotation text goes here. </p>
                            <p class="card-text"><small>3 days ago</small></p>
                        </div>
                    </div>
                </li>
                <li class="annotations-list-item">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">The annotation text goes here. The annotation text goes here. <br>The annotation text goes here. </p>
                            <p class="card-text"><small>3 days ago</small></p>
                        </div>
                    </div>
                </li>
                <li class="annotations-list-item">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">The annotation text goes here. The annotation text goes here. <br>The annotation text goes here. </p>
                            <p class="card-text"><small>3 days ago</small></p>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="flex-shrink-0 flex-grow-0 mt-auto">
                <form>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Type new annotation..." style="resize: none; height: 120px;"></textarea>
                    </div>
                    <button class="btn btn-secondary btn-block">Create Annotation</button>
                </form>
            </div>
        </div>
        <div v-if="showCreateDocumentModal" class="modal" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="showCreateDocumentModal = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Choose a template for creating a new document:</p>
                        <div class="form-group">
                            <select class="form-control" ref="createDocumentTemplateId">
                                <option v-for="template in documentTemplates" :value="template.id">{{ template.name }}</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" @click="createDocument()">Create Document</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showRenameDocumentModal" class="modal" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rename Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="showRenameDocumentModal = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Rename the document to:</p>
                        <div class="form-group">
                            <input class="form-control" type="text" ref="renameDocumentTitleInput" :value="activeTab.content.title" />
                        </div>
                        <button type="button" class="btn btn-primary" @click="updateDocumentTitle()">Rename Document</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showChangeDateDocumentModal" class="modal" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Date</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="showChangeDateDocumentModal = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Change the date for this document.</p>
                        <div class="form-group">
                            <input class="form-control" type="date" ref="changeDateDocumentInput" :value="activeTab.content.publish_date ? activeTab.content.publish_date.slice(0,10) : activeTab.content.created_at.slice(0,10)">
                        </div>
                        <button type="button" class="btn btn-primary" @click="updateDocumentDate()">Update Date</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showDeleteDocumentModal" class="modal" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="showDeleteDocumentModal = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this document?</p>
                        <button type="button" class="btn btn-primary" @click="deleteDocument(activeTab.content.id)">Delete Document</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MarkdownEditor from '@voraciousdev/vue-markdown-editor'

export default {
    props: ['groups'],
    components: {
        MarkdownEditor
    },
    data() {
        return {
            mutableGroups: this.groups,
            selectedGroup: null,
            selectedYear: null,
            openTabs: [],
            activeDocument: null,
            activeDocumentSection: null,
            showCreateDocumentModal: false,
            documentTemplates: null,
            showRenameDocumentModal: false,
            showChangeDateDocumentModal: false,
            showDeleteDocumentModal: false
        }
    },
    created() {
        console.log('created')
        var self = this

        axios.get(this.$root.getPath(`documents/templates`))
            .then( response => {
                if (response.data) {
                    self.documentTemplates = response.data
                }
            })
    },
    computed: {
        documentsInSideNav: function() {
            // return this.documents[0].years[0].documents
            // return []
            for (let group of this.mutableGroups) {
                if (group.name === this.selectedGroup) {
                    for (let year of group.years) {
                        if (year.year === this.selectedYear) {
                            return year.documents
                        }
                    }
                }
            }
            return []
        },
        itemsInSideNav: function() {
            // return [];
            if (this.selectedGroup) {
                // show years in type
                for (let group of this.mutableGroups) {
                    if (group.name === this.selectedGroup) {
                        return group.years.map( (year) => {
                            return { name: year.year }
                        }) 
                    }
                }
            }

            // return types
            return this.mutableGroups.map( (group) => {
                return { name: group.name }
            })
        },
        activeTab: function() {
            return this.getActiveTab()
        },
        
    },
    methods: {
        handleSideNavItemClick(item) {
            if (this.selectedGroup) {
                this.selectedYear = item.name
            } else {
                this.selectedGroup = item.name
            }
        },
        getTabTitle(tab) {
            if (tab.type === 'document') {
                return tab.content.display_title
            }
        },
        async openDocument(document) {

            console.log(`opening document id ${document.id}`)
            if (this.openTabs.length) {
                this.openTabs.forEach( (tab) => {
                    tab.isActive = false
                })
            }

            for (let tab of this.openTabs) {
                if (tab.type === 'document' && tab.content.id === document.id) {
                    console.log('document is already open. making active')
                    tab.isActive = true
                    return
                }
            }

            document.sections = await this.getDocumentSections(document.id)

            this.openTabs.push({
                isActive: true,
                type: 'document',
                content: document,
                focusFieldIndex: 0
            });
        },
        async getDocumentSections(documentId) {
            const response = await axios.get(this.$root.getPath(`documents/${documentId}/sections`))

            if (response.data) {
                console.log(response.data.sections);
                return response.data.sections
            }
        },
        activateTab(tabIndex) {
            this.openTabs.forEach( (tab) => {
                tab.isActive = false
            })
            this.openTabs[tabIndex].isActive = true
            this.activeDocumentSection = null
        },
        closeTab(tabIndex) {
            let newActiveIndex = tabIndex === 0 ? 1 : tabIndex - 1
            if (typeof this.openTabs[newActiveIndex] !== 'undefined') {
                this.openTabs[newActiveIndex].isActive = true
            }
            this.openTabs.splice(tabIndex, 1)
        },
        updateField(field) {
            console.log(field)
            axios.patch(this.$root.getPath(`documents/field/${field.id}`), {content: field.content })
                .then( response => {
                    console.log('Updated field')
                    console.log(response)
                })
        },
        clearFieldUIState(field) {
            var clone = Object.assign({}, field)
            if ('items' in clone.data) {
                clone.data.items.forEach( (item) => {
                    item.uiState = null
                })
            }
            return clone
        },
        updateDataField(field) {
            field = this.clearFieldUIState(field);

            axios.patch(this.$root.getPath(`documents/field/${field.id}`), {data: field.data })
                .then( response => {
                    console.log('Updated field')
                    console.log(response)
                })
        },
        addAgendaItem(field) {
            console.log('addAgendaItem')

            let item = {
                name: this.$refs.addAgendaItemInput[0].value,
                content: ''
            }
            if (field.data && 'items' in field.data) {
                field.data.items.push(item)
            } else {
                field.data = {
                    items: [item]
                }
            }

            this.$refs.addAgendaItemInput[0].value = ''
            
            this.updateDataField(field)
            this.$forceUpdate();
        },
        addListItem(field) {
            console.log('addListItem')

            let item = {
                name: this.$refs.addListItemInput[0].value
            }
            if (field.data && 'items' in field.data) {
                field.data.items.push(item)
            } else {
                field.data = {
                    items: [item]
                }
            }

            this.$refs.addListItemInput[0].value = ''
            
            this.updateDataField(field)
            this.$forceUpdate();
        },
        addLinkItem(field) {
            console.log('addLinkItem')

            let item = {
                name: this.$refs.addLinkItemNameInput[0].value,
                url: this.$refs.addLinkItemUrlInput[0].value
            }
            if (field.data && 'items' in field.data) {
                field.data.items.push(item)
            } else {
                field.data = {
                    items: [item]
                }
            }

            this.$refs.addLinkItemNameInput[0].value = ''
            this.$refs.addLinkItemUrlInput[0].value = ''
            
            this.updateDataField(field)
            this.$forceUpdate();
        },
        deleteListItem(field, itemIndex) {
            console.log('deleteListItem')
            console.log({field, itemIndex})
            field.data.items.splice(itemIndex, 1)
            this.updateDataField(field);
        },
        createDocument() {
            let templateId = this.$refs.createDocumentTemplateId.value

            var self = this
            
            axios.post(this.$root.getPath('documents/create'), {template_id: templateId })
                .then( response => {
                    console.log(response)

                    if (response.data) {

                        self.openDocument(response.data)

                        // Add document to side nav
                        let documentGroupId = response.data.document_group_id
                        let documentYear = parseInt(response.data.display_date.slice(0,4))
                        let documentAdded = false
                        let documentGroup = null

                        for (let group of this.mutableGroups) {
                            if (group.id === documentGroupId) {
                                documentGroup = group
                            }
                        }

                        for (let year of documentGroup.years) {
                            if (year.year === documentYear) {
                                year.documents.push(response.data)
                                documentAdded = true
                            }
                        }

                        // If there's no year, add the year and the document
                        if (!documentAdded) {
                            documentGroup.years.push({
                                year: documentYear,
                                documents: [response.data]
                            })
                            documentGroup.years.sort((a,b) => {
                                return b.year - a.year
                            })
                        }

                        self.showCreateDocumentModal = false
                    }
                })
        },
        getActiveTab() {
            if (!this.openTabs.length) {
                return null
            }
            for (let i = 0; i < this.openTabs.length; i++) {
                if (this.openTabs[i].isActive) {
                    return this.openTabs[i]
                }
            }
            return null
        },
        updateDocumentTitle() {
            console.log('update document title')
            let newTitle = this.$refs.renameDocumentTitleInput.value
            let document = this.getActiveTab().content

            if (newTitle === document.title) {
                this.showRenameDocumentModal = false
                return
            }

            axios.patch(this.$root.getPath(`documents/${document.id}/updateTitle`), {title: newTitle })
                .then( response => {
                    if (response.data) {
                        document.title = newTitle
                        document.display_title = response.data.display_title
                        this.showRenameDocumentModal = false
                    }
                })
        },
        updateDocumentDate() {
            console.log('update document date')
            let newDate = this.$refs.changeDateDocumentInput.value
            let document = this.getActiveTab().content

            if (document.publish_date && newDate === document.publish_date.slice(0,10)) {
                this.showChangeDateDocumentModal = false
                return
            }

            axios.patch(this.$root.getPath(`documents/${document.id}/updateDate`), {publish_date: newDate })
                .then( response => {
                    if (response.data) {
                        document.publish_date = newDate
                        document.display_date = response.data.display_date
                        document.display_date_relative = response.data.display_date_relative
                        document.display_title = response.data.display_title
                        this.showChangeDateDocumentModal = false
                    }
                })
        },
        deleteDocument(documentId) {
            let document = this.getActiveTab().content

            axios.delete(this.$root.getPath(`documents/${documentId}`))
                .then( response => {
                    if (response.data) {

                        // remove document from tabs
                        for (let i = 0; i < this.openTabs.length; i++) {
                            if (this.openTabs[i].content.id === documentId) {
                                this.closeTab(i)
                            }
                        }

                        // remove document from side nav
                        for (let group of this.mutableGroups) {
                            for (let year of group.years) {
                                for (let i = 0; i < year.documents.length; i++) {
                                    if (year.documents[i].id === documentId) {
                                        console.log(`Delete document`)
                                        year.documents.splice(i, 1)
                                    }
                                }
                            }
                        }

                        this.showDeleteDocumentModal = false
                    }
                })
        },
        fieldItems(field) {
            return (field.data && 'items' in field.data) ? field.data.items : null
        },
        getItemUIState(item) {
            return 'uiState' in item ? item.uiState : null
        },
        compileMarkdown(input) {
            console.log(input)
            let markdown = marked(input, { sanitize: true });
            console.log(markdown)
            return markdown
        }
    }
}
</script>

<style>

.document-view {
    overflow-y: auto;
}

</style>