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
                        <li class="breadcrumb-item"><a href="javascript:void(0);" v-on:click="selectedType = null; selectedYear = null;">Documents</a></li>
                        <li v-if="selectedType" class="breadcrumb-item"><a href="javascript:void(0);" v-on:click="selectedYear = null;">{{ selectedType }}</a></li>
                        <li v-if="selectedYear" class="breadcrumb-item">{{ selectedYear }}</li>
                    </ol>
                </nav>
                <ul v-if="selectedType && selectedYear" class="documents-list flex-fill">
                    <li v-for="document in documentsInSideNav" class="documents-list-item">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ document.title }}</h5>
                                <p class="card-text">{{ document.body }}</p>
                                <p class="card-text"><small>{{ document.date }}</small></p>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul v-else class="list-unstyled p-0">
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
                    <li class="panel-tab active">Document 1 <i class="bi bi-x"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
</svg></li>
                    <li class="panel-tab">Document 2 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
</svg></li>
                    <li class="panel-tab">Document 3 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
</svg></li>
                    <li class="panel-tab"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg></li>
                </ul>
            </nav>
            <div class="document-view flex-grow">
                <h1>Document Title</h1>

                <p>Document content goes here. Document content goes here. Document content goes here. Document content goes here. Document content goes here. </p>

                <p>Document content goes here. Document content goes here. Document content goes here. </p>

                <p>Document content goes here. Document content goes here. Document content goes here. Document content goes here. Document content goes here. Document content goes here. Document content goes here. Document content goes here. </p>
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
    </div>
</template>

<script>
export default {
    props: ['documents'],
    data() {
        return {
            selectedType: 'Journal Entries',
            selectedYear: 2020
        }
    },
    computed: {
        documentsInSideNav: function() {
            // return this.documents[0].years[0].documents
            for (let group of this.documents) {
                if (group.type === this.selectedType) {
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
            if (this.selectedType) {
                // show years in type
                for (let group of this.documents) {
                    if (group.type === this.selectedType) {
                        return group.years.map( (year) => {
                            return { name: year.year }
                        }) 
                    }
                }
            }

            // return types
            return this.documents.map( (group) => {
                return { name: group.type }
            })
        }
    },
    methods: {
        handleSideNavItemClick(item) {
            if (this.selectedType) {
                this.selectedYear = item.name
            } else {
                this.selectedType = item.name
            }
        }
    }
}
</script>

<style>

</style>