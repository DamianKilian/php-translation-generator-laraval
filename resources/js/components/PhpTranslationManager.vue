<template>
    <nav>
        <div class="nav nav-tabs" id="nav-tab">
            <button v-for="(langCode, index) in Object.keys(transFilesContents)" class="nav-link"
                :class="{ 'active': index === 0 }" :set="code = langCode.replace('.json', '')" :id="'nav-' + code + '-tab'"
                data-bs-toggle="tab" :data-bs-target="'#nav-' + code" :data-lang-code="langCode">{{ langCode }}</button>
        </div>
    </nav>
    <div @click="confirmSaveClose" class="tab-content" id="nav-tabContent">
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
                            <div @click="addNewTrans" class="new btn-icon">
                                <span class="text-primary">&plusb;</span>
                            </div>
                            <div class="save-wrapper">
                                <div @click="confirmSaveOpen = !confirmSaveOpen" class="save btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                        fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                </div>
                                <div :class="{ open: confirmSaveOpen }" class="confirm-save text-bg-dark p-2">
                                    <button @click="saveTransFiles" type="button" class="btn btn-primary float-end">
                                        Save for "{{ langCode }}"
                                    </button>
                                </div>
                            </div>
                            <div @click="filterErrors" class="error btn-icon"
                                v-if="Object.keys(duplicateKeyRecords).length !== 0 || filterErrorsOn">
                                <span :class="{ 'text-success': Object.keys(duplicateKeyRecords).length === 0 }">
                                    &#9888;
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 pb-5">
                    <form autocomplete="off">
                        <div v-for="(val, arrkey) in transFileContent" class="row g-3"
                            :class="{ 'd-none': !val.meta.visible, 'bg-primary': val.meta.new }" :key="val.meta.orginalKey">
                            <div class="col p-2"
                                :class="{ 'bg-warning': val.meta.modified.key, 'bg-danger': '' !== val.meta.error }">
                                {{ val.meta.error }}
                                <textarea class="form-control key-textarea" rows="3" :value="val['key']"
                                    @input="e => { translationModified(e, arrkey, 'key') }"></textarea>
                            </div>
                            <div class="col p-2" :class="{ 'bg-warning': val.meta.modified.val }">
                                <textarea class="form-control val-textarea" rows="3" :value="val['val']"
                                    @input="e => { translationModified(e, arrkey, 'val') }"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <Modal v-if="modalMsg.msg" :closeModal="closeModal" :modalMsg="modalMsg" />
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
        transFilesContentsProp: Object,
        saveTransFilesUrl: String,
    },
    data() {
        return {
            modalMsg: {
                msg: '',
                type: '',
            },
            newTransKeys: [],
            filterErrorsOn: false,
            duplicateKeyRecords: {},
            langCode: null,
            sidePanelOpen: false,
            confirmSaveOpen: false,
            transFilesContents: this.getTransFilesContents(),
        }
    },
    methods: {
        filterErrors,
        closeModal: function () {
            this.modalMsg = {
                msg: '',
                type: '',
            };
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
                    val: 'new'
                }]
            };
            this.getTransFilesContents(data, true);
            this.newTransKeys.push(newKey);
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
        getMeta: function (orginalVal, orginalKey, addNewTrans) {
            return {
                visible: true,
                new: !!addNewTrans,
                deleted: false,
                modified: { key: false, val: false },
                orginalVal: orginalVal,
                orginalKey: orginalKey,
                error: '',
            };
        },
        getTransFilesContents: function (data, addNewTrans) {
            if (!data) {
                data = this.transFilesContentsProp;
                var transFilesContents = {};
            } else {
                var transFilesContents = this.transFilesContents;
            }
            for (const prop in data) {
                if (!addNewTrans) {
                    transFilesContents[prop] = [];
                }
                for (const keyVal of data[prop]) {
                    var key = keyVal.key;
                    var val = keyVal.val;
                    var meta = this.getMeta(val, key, addNewTrans);
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
            return transFilesContents;
        },
        confirmSaveClose: function (e) {
            if (!e.target.closest(".save-wrapper")) {
                this.confirmSaveOpen = false;
            }
        },
        translationModified: _.debounce(function (e, arrkey, type) {
            var keyRecord = this.transFilesContents[this.langCode][arrkey];
            if ('key' === type) {
                var newKey = e.target.value.trim();
                if (newKey === keyRecord.key) {
                    return;
                }
                this.changeKey(keyRecord, newKey);
                this.checkDuplicateKey(arrkey, newKey);
            } else if ('val' === type) {
                var newVal = e.target.value.trim();
                if (newVal === keyRecord.val) {
                    return;
                }
                this.changeVal(keyRecord, newVal);
            }
            this.reCheckDuplicateKeys();
        }, 500),
        reCheckDuplicateKeys: function () {
            if (Object.keys(this.duplicateKeyRecords).length === 0) {
                return;
            }
            for (const key in this.duplicateKeyRecords) {
                var keyRecord = this.duplicateKeyRecords[key];
                delete this.duplicateKeyRecords[key];
                this.checkDuplicateKey(key, keyRecord.key);
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
        checkDuplicateKey: function (key, newKey) {
            var keyRecord = this.transFilesContents[this.langCode][key];
            var matches = 0;
            for (const key in this.transFilesContents[this.langCode]) {
                var keyTextarea = this.transFilesContents[this.langCode][key];
                if (newKey === keyTextarea.key) {
                    if (1 < ++matches) {
                        keyRecord.meta.error = 'duplicate key';
                        this.duplicateKeyRecords[key] = keyRecord;
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
        this.gettingLangCodeFromTabs();
        // console.debug(this.transFilesContents);//mmmyyy
    },
    updated() {
        console.debug('updated');//mmmyyy
        // console.debug(this.duplicateKeyRecords);//mmmyyy
    }
}
</script>
