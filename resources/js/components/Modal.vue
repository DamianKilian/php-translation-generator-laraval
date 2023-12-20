<template>
    <div class='app-modal'>
        <span v-if="'error' === this.modalMsg.type" class="text-danger fs-3">
            &#9888;
        </span>
        <p :class="{ 'text-danger': 'error' === this.modalMsg.type }">{{ this.modalMsg.msg }}</p>
        <button class='btn' @click="closeModal">
            Close
        </button>
    </div>
    <div className='app-modal-backdrop' @click="closeModal"></div>
</template>

<script>
export default {
    props: {
        closeModal: Function,
        modalMsg: Object,
    },
    mounted() {
    }
}
</script>
