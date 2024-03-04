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
        <div v-if="Object.keys(searchResults[langCode]).length">
            <h3 class="lang-code">{{ langCode }}</h3>
            <div v-for="(arr, location) in searchResults[langCode]" class="row px-3 py-2">
                <h4>{{ location }}</h4>
                <textarea v-for="(trans) in arr" class="form-control p-3 my-1" rows="1" readonly>{{ trans }}</textarea>
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
    mounted() {
    }
}
</script>
