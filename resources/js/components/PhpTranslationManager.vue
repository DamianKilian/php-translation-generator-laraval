<template>
    <nav>
        <div class="nav nav-tabs" id="nav-tab">
            <button v-for="(langCode, index) in Object.keys(transFilesContents)" class="nav-link"
                :class="{ 'active': index === 0 }" :set="code = langCode.replace('.json', '')" :id="'nav-' + code + '-tab'"
                data-bs-toggle="tab" :data-bs-target="'#nav-' + code">{{ langCode }}</button>
        </div>
    </nav>
    <div @click="confirmSaveClose" class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show pt-5" v-for="(transFileContent, langCode) in transFilesContents"
            :class="{ 'active': langCode === Object.keys(transFilesContents)[0] }"
            :id="'nav-' + langCode.replace('.json', '')">
            <div class="d-flex">
                <div :class="{ open: sidePanelOpen }" class="side-panel d-flex">
                    <div class="settings flex-grow-1">
                        <div class="sticky-top">
                            <h2 class="text-white bg-dark text-center">{{ langCode }}</h2>
                        </div>
                    </div>
                    <div class="btns px-3">
                        <div class="sticky-top">
                            <div @click="sidePanelOpen = !sidePanelOpen" class="openSettings btn-svg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                    fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg>
                            </div>
                            <div class="save-wrapper">
                                <div @click="confirmSaveOpen = !confirmSaveOpen" class="save btn-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                        fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                </div>
                                <div :class="{ open: confirmSaveOpen }" class="confirm-save text-bg-dark p-2">
                                    <button type="button" class="btn btn-primary float-end">
                                        Save for "{{ langCode }}"
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-light rounded flex-grow-1">
                    <form>
                        <div v-for="(val, key) in transFileContent" class="row g-3 mb-3">
                            <div class="col">
                                <textarea class="form-control" rows="3" :value="key"
                                    @input="e => translationModified(e, key, 'key')"></textarea>
                            </div>
                            <div class="col">
                                <textarea class="form-control" rows="3" :value="val['val']"
                                    @input="e => translationModified(e, key, 'val')"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        transFilesContentsProp: Object,
    },
    data() {
        return {
            sidePanelOpen: false,
            confirmSaveOpen: false,
            transFilesContents: this.getTransFilesContents(),
        }
    },
    methods: {
        getTransFilesContents: function () {
            var transFilesContents = {};
            var meta = {
                new: false,
                modified: false,
                deleted: false,
            };
            for (const prop in this.transFilesContentsProp) {
                transFilesContents[prop] = {};
                for (const prop2 in this.transFilesContentsProp[prop]) {
                    transFilesContents[prop][prop2] = {
                        meta: meta,
                        val: this.transFilesContentsProp[prop][prop2]
                    };
                }
            }
            return transFilesContents;
        },
        confirmSaveClose: function (e) {
            if (!e.target.closest(".save-wrapper")) {
                this.confirmSaveOpen = false;
            }
        },
        translationModified: function (e) {
            console.debug(e.target.value);//mmmyyy
        },

    },
    mounted() {
    }
}
</script>
