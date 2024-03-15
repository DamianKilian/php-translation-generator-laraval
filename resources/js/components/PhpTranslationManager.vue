<template>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab">
            <button v-for="(langCode, index) in Object.keys(transFilesContents)" class="nav-link"
                :class="{ 'active': index === 0 }" :set="code = langCode.replace('.json', '')"
                :id="'nav-' + code + '-tab'" data-bs-toggle="tab" :data-bs-target="'#nav-' + code"
                :data-lang-code="langCode">{{ langCode }}
                ({{ transFilesContents[langCode].length }})</button>
        </div>
    </nav>
    <div @click="confirmClose" class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show pt-3 pe-4" v-for="(transFileContent, langCode) in transFilesContents"
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
                            <div class="app-btn">
                                <div @click="sidePanelOpen = !sidePanelOpen" class="openSettings btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                        fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path
                                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="app-tooltip">Settings</div>
                            </div>
                            <div class="app-btn">
                                <div class="app-tooltip">Search</div>
                                <div class="btn-confirm-wrapper">
                                    <div @click="confirmClose($event, true); confirmSearchOpen = !confirmSearchOpen"
                                        class="search btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                            viewBox="0 0 24 24" fill="none" stroke="#417505" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                    </div>
                                    <div :class="{ open: confirmSearchOpen }" class="confirm text-bg-dark p-2">
                                        <button @click="searchTrans" type="button"
                                            class="btn btn-primary float-end">Search for "{{ langCode }}"</button>
                                    </div>
                                </div>
                            </div>
                            <div class="app-btn">
                                <div class="app-tooltip">Translate</div>
                                <div class="btn-confirm-wrapper">
                                    <div @click="confirmClose($event, true); confirmTranslateOpen = !confirmTranslateOpen"
                                        class="search btn-icon">
                                        <span class="text-primary">&#10002;</span>
                                    </div>
                                    <div :class="{ open: confirmTranslateOpen }" class="confirm text-bg-dark p-2">
                                        <button @click="translate" type="button"
                                            class="btn btn-primary float-end">Translate for
                                            "{{ langCode }}"</button>
                                    </div>
                                </div>
                            </div>
                            <div class="app-btn">
                                <div class="app-tooltip">Save</div>
                                <div class="btn-confirm-wrapper">
                                    <div @click="confirmClose($event, true); confirmSaveOpen = !confirmSaveOpen"
                                        class="save btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                            viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z">
                                            </path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                    </div>
                                    <div :class="{ open: confirmSaveOpen }" class="confirm text-bg-dark p-2">
                                        <button @click="saveTransFiles" type="button" class="btn btn-primary float-end">
                                            Save for "{{ langCode }}"
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="app-btn">
                                <div @click="addNewTrans" class="new btn-icon">
                                    <span class="text-primary">&plusb;</span>
                                </div>
                                <div class="app-tooltip">Add new trans</div>
                            </div>
                            <div class="app-btn">
                                <div @click="deselectTrans" class="btn-icon">
                                    <span class="text-secondary">
                                        &#9744;
                                    </span>
                                    <div class="numOnBtn">({{ countSelectedTransNum }})</div>
                                </div>
                                <div class="app-tooltip">Deselect all</div>
                            </div>
                            <div class="app-btn">
                                <div @click="deleteTrans()" class="btn-icon">
                                    <span>
                                        &#10005;
                                    </span>
                                </div>
                                <div class="app-tooltip">Delete</div>
                            </div>
                            <div class="app-btn" v-if="Object.keys(historyKeysGlobal).length && this.langCode">
                                <div @click="backForthHistory(true)" class="btn-icon">
                                    <span
                                        :class="{ 'text-secondary': historyKeysGlobal[this.langCode].historyCurrKeyGlobal < 1 }">
                                        &#8634;
                                    </span>
                                    <div class="numOnBtn">({{ historyKeysGlobal[this.langCode].historyCurrKeyGlobal }})
                                    </div>
                                </div>
                                <div class="app-tooltip">Back</div>
                            </div>
                            <div class="app-btn" v-if="Object.keys(historyKeysGlobal).length && this.langCode">
                                <div @click="backForthHistory(false)" class="btn-icon">
                                    <span
                                        :class="{ 'text-secondary': historyKeysGlobal[this.langCode].historyCurrKeyGlobal === historyKeysGlobal[this.langCode].historyLastKeyGlobal }">
                                        &#8635;
                                    </span>
                                    <div class="numOnBtn">({{ historyKeysGlobal[this.langCode].historyLastKeyGlobal -
        historyKeysGlobal[this.langCode].historyCurrKeyGlobal }})</div>
                                </div>
                                <div class="app-tooltip">Forth</div>
                            </div>
                            <div class="app-btn">
                                <div @click="filterErrors" class="error btn-icon red"
                                    v-if="duplicateKeyRecords.length !== 0 || filterErrorsOn">
                                    <span :class="{ 'text-success': duplicateKeyRecords.length === 0 }">
                                        &#9888;
                                    </span>
                                </div>
                                <div class="app-tooltip">Filter Errors</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 pb-5">
                    <div v-for="(val, arrkey) in transFileContent" class="row"
                        :class="{ 'd-none': !val.meta.visible, 'bg-primary': val.meta.new, 'bg-danger': val.meta.deleted }"
                        :key="val.meta.orginalKey">
                        <div class="col p-2"
                            :class="{ 'bg-warning': val.meta.modified.key, 'border border-danger bg-white border-3': '' !== val.meta.error }">
                            <b v-if="'' !== val.meta.error" class="text-danger">&#9888; {{ val.meta.error }}</b>
                            <div class="trans">
                                <div class="actions">
                                    <input @change="selectAction($event, arrkey)" v-model="val['meta'].selected"
                                        @click.shift="selectActionMulti($event, arrkey)" class="select-action"
                                        type="checkbox">
                                    <div class="d-flex flex-column btns-small">
                                        <div class="p-2 btn-small" @click="deleteTrans(e, false, { [arrkey]: val })">
                                            <div class="icon">&#10005;</div>
                                            <div class="app-tooltip">Delete</div>
                                        </div>
                                        <div class="p-2 btn-small" @click="resetTrans($event, arrkey)">
                                            <div class="icon">&#10560;</div>
                                            <div class="app-tooltip">Reset</div>
                                        </div>
                                    </div>
                                </div>
                                <textarea :class="{ 'text-danger': '' !== val.meta.error }"
                                    class="form-control key-textarea key-val-textarea p-3" rows="3"
                                    @focus="textareaInputBlocked = false"
                                    @blur="translationModifiedEntry($event, arrkey, 'key')"
                                    @input="translationModifiedEntry($event, arrkey, 'key')" :data-arrkey="arrkey"
                                    data-type="key">{{ val['key'] }}</textarea>
                            </div>
                        </div>
                        <div class="col p-2" :class="{ 'bg-warning': val.meta.modified.val }">
                            <div class="trans">
                                <textarea :id="'val-textarea' + arrkey"
                                    class="form-control val-textarea key-val-textarea p-3" rows="3"
                                    @focus="textareaInputBlocked = false"
                                    @blur="translationModifiedEntry($event, arrkey, 'val')"
                                    @input="translationModifiedEntry($event, arrkey, 'val')" :data-arrkey="arrkey"
                                    data-type="val">{{ val['val'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal v-if="modalMsg.msg || modalSearchOpen || numOfStrToTranslate" :searchResults="searchResults"
            :modalSearchOpen="modalSearchOpen" :closeModal="closeModal" :getTransFilesContents="getTransFilesContents"
            :modalMsg="modalMsg" :langCode="langCode" :getKeys="getKeys" :transFilesContents="transFilesContents"
            :numOfStrToTranslate="numOfStrToTranslate" />
    </div>
</template>

<script>
import { filterErrors } from './phpTranslationManager/filters.js'
import Modal from './Modal.vue'

export default {
    components: {
        Modal
    },
    props: {
        getTransFilesContentsDataUrl: String,
        saveTransFilesUrl: String,
        searchUrl: String,
        translateUrl: String,
    },
    data() {
        return {
            error: '',
            searchResults: {},
            modalSearchOpen: false,
            history: {
                transFilesContents: {},
            },
            historyStorageBlock: false,
            historyKeysGlobal: {},
            lastTransSelectedOrginalKey: '',
            textareaInputBlocked: true,
            modalMsg: {
                msg: '',
                type: '',
            },
            filterErrorsOn: false,
            langCode: null,
            sidePanelOpen: false,
            confirmSaveOpen: false,
            confirmSearchOpen: false,
            confirmTranslateOpen: false,
            transFilesContents: {},
            numOfStrToTranslate: 0,
        }
    },
    computed: {
        countSelectedTransNum() {
            return this.getSelectedTrans().length;
        },
        duplicateKeyRecords() {
            return this.getDuplicateKeyRecords();
        }
    },
    methods: {
        filterErrors,
        translate: function () {
            var selectedTrans = this.getSelectedTrans();
            this.numOfStrToTranslate = selectedTrans.length;
            if (!this.numOfStrToTranslate) {
                return;
            }
            for (const trans of selectedTrans) {
                axios
                    .post(this.translateUrl, { str: trans.key, langCode: this.langCode })
                    .then((response) => {
                        var strTrans = response.data.strTrans;
                        if (undefined !== strTrans[this.langCode]) {
                            const valTextarea = document.getElementById('val-textarea' + trans.currentKey);
                            valTextarea.value = strTrans[this.langCode];
                            this.textareaInputEvent(valTextarea);
                        }
                        this.numOfStrToTranslate--;
                    }).catch((error) => {
                        console.log(error);
                    });
            }
        },
        textareaInputEvent: function (textarea) {
            this.textareaInputBlocked = false;
            textarea.dispatchEvent(new Event("blur"));
        },
        resetTrans: function (e, arrkey) {
            var keyRecord = this.transFilesContents[this.langCode][arrkey];
            var orginalVal = keyRecord.meta.orginalVal;
            var orginalKey = keyRecord.meta.orginalKey;
            var row = e.target.closest('.row');
            var valTextarea = row.querySelector('.val-textarea');
            var keyTextarea = row.querySelector('.key-textarea');
            if (valTextarea.value !== orginalVal || keyTextarea.value !== orginalKey) {
                keyRecord.meta = this.getMeta(orginalVal, orginalKey);
                if (valTextarea.value !== orginalVal) {
                    this.historyStorageBlock = true;
                    valTextarea.value = orginalVal;
                    this.textareaInputEvent(valTextarea);
                }
                if (keyTextarea.value !== orginalKey) {
                    this.historyStorageBlock = true;
                    keyTextarea.value = orginalKey;
                    this.textareaInputEvent(keyTextarea);
                }
            }
        },
        backForthHistory: function (back) {
            var hkg = this.historyKeysGlobal[this.langCode];
            if ((back ? hkg.historyCurrKeyGlobal < 1 : hkg.historyCurrKeyGlobal === this.historyKeysGlobal[this.langCode].historyLastKeyGlobal)) {
                return;
            }
            this.historyStorageBlock = true;
            hkg.historyCurrKeyGlobal = hkg.historyCurrKeyGlobal + (back ? -1 : 1);
            for (const property in this.history) {
                var propertyStr = this.history[property][this.langCode][hkg.historyCurrKeyGlobal];
                if (propertyStr) {
                    this[property][this.langCode] = JSON.parse(propertyStr);
                }
            }
            this.syncTextareaValues();
        },
        syncTextareaValues: function () {
            document.querySelectorAll('.key-val-textarea').forEach(function (textarea) {
                var keyRecord = this.transFilesContents[this.langCode][textarea.dataset.arrkey];
                if (undefined !== keyRecord) {
                    textarea.value = keyRecord[textarea.dataset.type];
                }
            }, this);
        },
        storeHistory: function () {
            var hkg = this.historyKeysGlobal[this.langCode];
            if (this.historyStorageBlock) {
                this.historyStorageBlock = false;
                return;
            }
            var history = this.history;
            var historyKeyGlobalInc = false;
            for (const property in history) {
                if (typeof history[property][this.langCode] === 'undefined') {
                    history[property][this.langCode] = [];
                }
                var propertyStr = JSON.stringify(this[property][this.langCode]);
                if (hkg.historyCurrKeyGlobal < 0 || history[property][this.langCode][hkg.historyCurrKeyGlobal] !== propertyStr) {
                    if (this.historyKeysGlobal[this.langCode].historyLastKeyGlobal !== hkg.historyCurrKeyGlobal) {
                        for (const property in history) {
                            history[property][this.langCode] = history[property][this.langCode].slice(0, hkg.historyCurrKeyGlobal + 1);
                        }
                    }
                    history[property][this.langCode].push(propertyStr);
                    historyKeyGlobalInc = true;
                } else {
                    history[property][this.langCode].push(null);
                }
            }
            if (historyKeyGlobalInc) {
                this.historyKeysGlobal[this.langCode].historyLastKeyGlobal = ++hkg.historyCurrKeyGlobal;
            } else {
                for (const property in history) {
                    history[property][this.langCode].pop();
                }
            }
        },
        setHistoryKeysGlobal: function (langCodes) {
            for (const key in langCodes) {
                this.historyKeysGlobal[langCodes[key]] = {
                    historyLastKeyGlobal: -1,
                    historyCurrKeyGlobal: -1
                };
            }
        },
        deleteTrans: function (e, undelete = false, selectedTransProp = {}) {
            if (!Array.isArray(selectedTransProp) && Object.keys(selectedTransProp).length) {
                var selectedTrans = [];
                for (const key in selectedTransProp) {
                    selectedTransProp[key].meta.currentKey = key;
                    selectedTrans.push(selectedTransProp[key]);
                }
            } else if (!selectedTransProp.length) {
                var selectedTrans = this.getSelectedTrans();
            } else {
                var selectedTrans = selectedTransProp;
            }
            var selectedTransDeleted = [];
            for (const key in selectedTrans) {
                if (!undelete && selectedTrans[key].meta.deleted) {
                    selectedTransDeleted.push(selectedTrans[key]);
                }
                if (selectedTrans[key].meta.new) {
                    this.transFilesContents[this.langCode].splice(selectedTrans[key].meta.currentKey, 1);
                } else {
                    selectedTrans[key].meta.deleted = !undelete;
                }
            }
            if (!undelete && selectedTransDeleted.length === selectedTrans.length) {
                this.deleteTrans(e, true, selectedTrans);
            }
        },
        getSelectedTrans: function () {
            var selectedTrans = [];
            for (const key in this.transFilesContents[this.langCode]) {
                var trans = this.transFilesContents[this.langCode][key];
                if (trans.meta.selected) {
                    trans.currentKey = key;
                    selectedTrans.push(trans);
                }
            }
            return selectedTrans;
        },
        deselectTrans: function () {
            for (const key in this.transFilesContents[this.langCode]) {
                this.transFilesContents[this.langCode][key].meta.selected = false;
            }
        },
        closeModal: function () {
            this.modalMsg = {
                msg: '',
                type: '',
            };
            this.modalSearchOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
        selectAction: function (e, arrkey) {
            var keyRecord = this.transFilesContents[this.langCode][arrkey];
            if (!keyRecord.meta.selected) {
                if (this.lastTransSelectedOrginalKey === keyRecord.meta.orginalKey) {
                    this.lastTransSelectedOrginalKey = '';
                }
                return;
            };
            this.lastTransSelectedOrginalKey = keyRecord.meta.orginalKey;
        },
        selectActionMulti: function (e, arrkey) {
            if ('' === this.lastTransSelectedOrginalKey) {
                return;
            }
            var lastTransSelectedArrkey = null;
            for (const key in this.transFilesContents[this.langCode]) {
                var orginalKey = this.transFilesContents[this.langCode][key].meta.orginalKey;
                if (orginalKey === this.lastTransSelectedOrginalKey) {
                    lastTransSelectedArrkey = key;
                }
            }
            var lowerKey = Math.min(arrkey, lastTransSelectedArrkey);
            var key = Math.max(arrkey, lastTransSelectedArrkey) + 1;
            while (key !== lowerKey) {
                key--;
                this.transFilesContents[this.langCode][key].meta.selected = true;
            }
        },
        getRandomInt: function (max) {
            return Math.floor(Math.random() * max);
        },
        getKeys: function (trans) {
            var keys = [];
            trans.forEach(element => {
                keys.push(element.key);
            });
            return keys;
        },
        addNewTrans: function () {
            do {
                var newKey = 'new_' + this.getRandomInt(999999);
            } while (this.getKeys(this.transFilesContents[this.langCode]).indexOf(newKey) > -1);
            var data = {
                [this.langCode]: [{
                    key: newKey,
                    val: '( new )'
                }]
            };
            this.getTransFilesContents(data, { new: true });
        },
        openSearchModal: function () {
            this.modalSearchOpen = true;
            this.confirmSearchOpen = false;
            document.body.classList.add('overflow-hidden');
        },
        searchTrans: function () {
            this.openSearchModal();
            axios
                .post(this.searchUrl, { langCode: this.langCode })
                .then((response) => {
                    var langCode = Object.keys(response.data.searchResults)[0];
                    this.searchResults[langCode] = this.searchResultsAddMeta(response.data.searchResults[langCode]);
                }).catch((error) => {
                    console.log(error);
                });
        },
        searchResultsAddMeta: function (searchResults) {
            var searchResultsWithMeta = {};
            for (const location in searchResults) {
                searchResultsWithMeta[location] = [];
                for (const trans of searchResults[location]) {
                    searchResultsWithMeta[location].push({
                        trans: trans,
                        selected: false,
                        added: false,
                    });
                }
            }
            return searchResultsWithMeta;
        },
        saveTransFiles: function () {
            var data = {};
            data[this.langCode] = this.transFilesContents[this.langCode];
            axios
                .post(this.saveTransFilesUrl, { trans: data })
                .then((response) => {
                    if (response.data.success) {
                        this.getTransFilesContents(response.data.transFilesContents);
                        this.filterErrorsOn = false;
                        this.confirmSaveOpen = false;
                    } else {
                        this.modalMsg = {
                            msg: response.data.error,
                            type: 'error',
                        };
                    }
                }).catch((error) => {
                    console.log(error);
                });
        },
        getMeta: function (orginalVal, orginalKey, initMeta = {}) {
            var meta = {
                visible: true,
                new: false,
                deleted: false,
                selected: false,
                modified: { key: false, val: false },
                orginalVal: orginalVal,
                orginalKey: orginalKey,
                error: '',
            };
            if (Object.keys(initMeta).length) {
                for (const prop in initMeta) {
                    meta[prop] = initMeta[prop];
                }
            }
            return meta;
        },
        getTransFilesContentsData: function () {
            axios
                .post(this.getTransFilesContentsDataUrl)
                .then((response) => {
                    if (response.data.transFilesContents.error) {
                        this.error = response.data.transFilesContents.error;
                        return;
                    }
                    this.setHistoryKeysGlobal(Object.keys(response.data.transFilesContents));
                    this.getTransFilesContents(response.data.transFilesContents);
                }).catch((error) => {
                    console.log(error);
                });
        },
        getTransFilesContents: function (data, initMeta = {}) {
            var transFilesContents = this.transFilesContents;
            var addNewTrans = initMeta.new;
            for (const prop in data) {
                if (!addNewTrans) {
                    transFilesContents[prop] = [];
                }
                for (const keyVal of data[prop]) {
                    var key = keyVal.key;
                    var val = keyVal.val;
                    var meta = this.getMeta(val, key, initMeta);
                    var modified = {
                        meta: meta,
                        val: val,
                        key: key
                    };
                    if (addNewTrans) {
                        transFilesContents[prop].unshift(modified);
                    } else {
                        transFilesContents[prop].push(modified);
                    }
                }
            }
        },
        confirmClose: function (e, force = false) {
            if (force || !e.target.closest(".btn-confirm-wrapper")) {
                this.confirmTranslateOpen = false;
                this.confirmSearchOpen = false;
                this.confirmSaveOpen = false;
            }
        },
        translationModifiedEntry: function (e, arrkey, type) {
            if ('blur' === e.type) {
                this.translationModified(e, arrkey, type);
            } else {
                this.translationModifiedDebounce(e, arrkey, type);
            }
        },
        translationModified: function (e, arrkey, type) {
            if (this.textareaInputBlocked) {
                return;
            }
            var keyRecord = this.transFilesContents[this.langCode][arrkey];
            if ('key' === type) {
                var newKey = e.target.value.trim();
                if (newKey === keyRecord.key) {
                    return;
                }
                this.changeKey(keyRecord, newKey);
                this.checkDuplicateKey(keyRecord, newKey);
            } else if ('val' === type) {
                var newVal = e.target.value.trim();
                if (newVal === keyRecord.val) {
                    return;
                }
                this.changeVal(keyRecord, newVal);
            }
            this.reCheckDuplicateKeys();
        },
        translationModifiedDebounce: _.debounce(function (e, arrkey, type) {
            this.translationModified(e, arrkey, type);
        }, 500),
        reCheckDuplicateKeys: function () {
            var duplicateKeyRecords = this.duplicateKeyRecords;
            if (duplicateKeyRecords.length === 0) {
                return;
            }
            for (const key in duplicateKeyRecords) {
                var keyRecord = duplicateKeyRecords[key];
                this.checkDuplicateKey(keyRecord, keyRecord.key);
            }
        },
        changeVal: function (keyRecord, newVal) {
            keyRecord.val = newVal;
            if (!keyRecord.meta.new) {
                keyRecord.meta.modified.val = (newVal !== keyRecord.meta.orginalVal);
            }
        },
        changeKey: function (keyRecord, newKey) {
            keyRecord.key = newKey;
            if (!keyRecord.meta.new) {
                keyRecord.meta.modified.key = (newKey !== keyRecord.meta.orginalKey);
            }
        },
        getDuplicateKeyRecords: function () {
            var duplicateKeyRecords = [];
            for (const key in this.transFilesContents[this.langCode]) {
                var trans = this.transFilesContents[this.langCode][key];
                if (trans.meta.error) {
                    duplicateKeyRecords.push(trans);
                }
            }
            return duplicateKeyRecords;
        },
        checkDuplicateKey: function (keyRecord, newKey) {
            var matches = 0;
            for (const key in this.transFilesContents[this.langCode]) {
                var keyTextarea = this.transFilesContents[this.langCode][key];
                if (newKey === keyTextarea.key) {
                    if (1 < ++matches) {
                        keyRecord.meta.error = 'duplicate key';
                        return;
                    }
                }
            }
            keyRecord.meta.error = '';
        },
        gettingLangCodeFromTabs: function () {
            this.langCode = document.querySelector('.nav-tabs .active').dataset.langCode;
            const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabEls.forEach((tabEl) => {
                tabEl.addEventListener('shown.bs.tab', e => {
                    this.langCode = e.target.dataset.langCode;
                });
            });
        },

    },
    mounted() {
        this.getTransFilesContentsData();
        // console.debug(this.transFilesContents);//mmmyyy
    },
    updated() {
        if (this.error) {
            return;
        }
        if (!this.langCode) {
            this.gettingLangCodeFromTabs();
        }
        this.storeHistory();

        console.debug('updated');//mmmyyy
        // console.debug(this.duplicateKeyRecords);//mmmyyy
    }
}
</script>
