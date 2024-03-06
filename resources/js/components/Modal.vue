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
                    <div class="add-all-results search-results-btn">
                        <div class="icon">&plusb;</div>
                        <div class="search-tooltip">Add&nbsp;selected</div>
                    </div>
                </div>
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
    <div className='app-modal-backdrop' @click="closeModal"></div>
</template>

<script>
export default {
    props: {
        closeModal: Function,
        modalMsg: Object,
        searchResults: Object,
        modalSearchOpen: Boolean,
        langCode: Boolean,
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
        }
    },
    mounted() {
    }
}
</script>
