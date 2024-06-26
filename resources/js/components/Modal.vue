<template>
    <div class='app-modal' v-if="modalMsg.msg">
        <span v-if="'error' === this.modalMsg.type" class="text-danger fs-3">
            &#9888;
        </span>
        <p :class="{ 'text-danger': 'error' === this.modalMsg.type }">{{ this.modalMsg.msg }}</p>
        <button class='btn' @click="closeModal">
            Close
        </button>
    </div>
    <div class='app-modal search-results overflow-auto' v-if="modalSearchOpen">
        <div v-if="undefined !== searchResults[langCode] && Object.keys(searchResults[langCode]).length">
            <div class="fixed-top">
                <div class="search-results-btns">
                    <div class="select-all-results search-results-btn" @click="selectAllResults">
                        <div class="icon">&#9745;</div>
                        <div class="search-tooltip">Select&nbsp;all</div>
                    </div>
                    <div class="add-all-results search-results-btn" @click="addSelectedNewTransFromSearch">
                        <div class="icon">&plusb;</div>
                        <div class="search-tooltip">Add&nbsp;selected</div>
                    </div>
                    <div class="add-all-results search-results-btn" style="margin-top: -4px; position: relative;">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 24 24"
                                fill="none" stroke="#9b9b9b" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z" />
                                <path d="M14 3v5h5M9.9 17.1L14 13M9.9 12.9L14 17" />
                            </svg>
                        </div>
                        <div class="numOnBtn" style="top:2px;">{{ unusedTrans.length }}</div>
                        <div class="search-tooltip">Unused</div>
                    </div>
                    <div class="search-results-btn" @click="closeModal">
                        <div class="icon">&cross;</div>
                    </div>
                </div>
            </div>
            <div class="alert alert-danger fixed-top" v-if="error" role="alert">
                {{ error }}
            </div>
            <h3 class="lang-code">{{ langCode }}</h3>
            <div v-for="(arr, location) in searchResults[langCode]" class="row px-3 py-2">
                <h4>{{ location }}</h4>
                <div v-for="(trans) in arr" class="trans">
                    <input type="checkbox" class="select-trans" v-model="trans.selected">
                    <textarea class="form-control p-3 my-1" rows="1" readonly>{{ trans.trans }}</textarea>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class='app-modal translate' v-if="numOfStrToTranslate">
        <div class="progress">
            <div class="progress-bar" :style="{ width: numOfStrToTranslatePercent + '%' }">
                {{ numOfStrToTranslatePercent + '%' }}
            </div>
        </div>
    </div>
    <div className='app-modal-backdrop' @click="closeModal"></div>
</template>

<script>
export default {
    props: {
        unusedTrans: Object,
        getTransFilesContents: Function,
        closeModal: Function,
        getKeys: Function,
        transFilesContents: Object,
        modalMsg: Object,
        searchResults: Object,
        modalSearchOpen: Boolean,
        langCode: Boolean,
        numOfStrToTranslate: Number,
    },
    data() {
        return {
            numOfStrToTranslateInitial: this.numOfStrToTranslate,
            error: '',
        }
    },
    computed: {
        numOfStrToTranslatePercent() {
            return parseInt(100 - this.numOfStrToTranslate / this.numOfStrToTranslateInitial * 100);
        }
    },
    methods: {
        selectAllResults: function (e, select = true) {
            var notSelected = 0;
            for (const location in this.searchResults[this.langCode]) {
                for (const val of this.searchResults[this.langCode][location]) {
                    if (!val.selected && !notSelected) {
                        notSelected++;
                    }
                    val.selected = select;
                }
            }
            if (!notSelected && select) {
                this.selectAllResults(e, false);
            }
        },
        checkSelectedSearchTransAreUnique: function () {
            var keys = this.getKeys(this.transFilesContents[this.langCode]);
            for (const location in this.searchResults[this.langCode]) {
                for (const val of this.searchResults[this.langCode][location]) {
                    if (val.selected && !val.added && keys.indexOf(val.trans) > -1) {
                        return val.trans;
                    }
                }
            }
            return '';
        },
        addSelectedNewTransFromSearch: function () {
            var notUniqueTrans = this.checkSelectedSearchTransAreUnique();
            if (notUniqueTrans) {
                this.error = `"${notUniqueTrans}" translation is taken`;
                return;
            }
            for (const location in this.searchResults[this.langCode]) {
                for (const val of this.searchResults[this.langCode][location]) {
                    if (!val.selected || val.added) {
                        continue;
                    }
                    var data = {
                        [this.langCode]: [{
                            key: val.trans,
                            val: '( new )'
                        }]
                    };
                    this.getTransFilesContents(data, { new: true, selected: true });
                    val.added = true;
                }
            }
        },
    },
    mounted() {
    }
}
</script>
