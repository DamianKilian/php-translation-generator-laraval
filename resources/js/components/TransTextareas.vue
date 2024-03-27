<template>
    <div class="flex-grow-1 pb-5">
        <div v-for="(val, arrkey) in transFileContent" class="row"
            :class="{ 'd-none': !val.meta.visible, 'bg-primary': val.meta.new, 'bg-danger': val.meta.deleted }"
            :key="val.meta.orginalKey">
            <div class="col p-2"
                :class="{ 'bg-warning': val.meta.modified.key, 'border border-danger bg-white border-3': '' !== val.meta.error }">
                <b v-if="'' !== val.meta.error" class="text-danger trans-info"><span
                        class="icon icon-html-entity">&#9888;</span>{{ val.meta.error
                    }}</b>
                <b v-if="val.meta.unused" class="text-secondary trans-info">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#9b9b9b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z" />
                            <path d="M14 3v5h5M9.9 17.1L14 13M9.9 12.9L14 17" />
                        </svg>
                    </span>Unused
                </b>
                <div class="trans">
                    <div class="actions">
                        <input @change="selectAction($event, arrkey)" v-model="val['meta'].selected"
                            @click.shift="selectActionMulti($event, arrkey)" class="select-action" type="checkbox">
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
                        @focus="textareaInputBlocked = false" @blur="translationModifiedEntry($event, arrkey, 'key')"
                        @input="translationModifiedEntry($event, arrkey, 'key')" :data-arrkey="arrkey"
                        data-type="key">{{ val['key'] }}</textarea>
                </div>
            </div>
            <div class="col p-2" :class="{ 'bg-warning': val.meta.modified.val }">
                <div class="trans">
                    <textarea :id="'val-textarea' + arrkey" class="form-control val-textarea key-val-textarea p-3"
                        rows="3" @focus="textareaInputBlocked = false"
                        @blur="translationModifiedEntry($event, arrkey, 'val')"
                        @input="translationModifiedEntry($event, arrkey, 'val')" :data-arrkey="arrkey"
                        data-type="val">{{ val['val'] }}</textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['transFileContent', 'transFilesContents', 'langCode', 'deleteTrans', 'getMeta',
        'historyStorageBlock', 'textareaInputBlocked', 'duplicateKeyRecords', 'storeHistory', 'textareaInputEvent'],
    data() {
        return {
            lastTransSelectedOrginalKey: '',
        }
    },
    methods: {
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
                    this.$emit('historyStorageBlock', true);
                    // this.historyStorageBlock = true;
                    valTextarea.value = orginalVal;
                    this.textareaInputEvent(valTextarea);
                }
                if (keyTextarea.value !== orginalKey) {
                    this.$emit('historyStorageBlock', true);
                    // this.historyStorageBlock = true;
                    keyTextarea.value = orginalKey;
                    this.textareaInputEvent(keyTextarea);
                }
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
        changeKey: function (keyRecord, newKey) {
            keyRecord.key = newKey;
            if (!keyRecord.meta.new) {
                keyRecord.meta.modified.key = (newKey !== keyRecord.meta.orginalKey);
            }
        },
        changeVal: function (keyRecord, newVal) {
            keyRecord.val = newVal;
            if (!keyRecord.meta.new) {
                keyRecord.meta.modified.val = (newVal !== keyRecord.meta.orginalVal);
            }
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
    },
    mounted() {
    },
    updated() {
        this.storeHistory();
    }
}
</script>
