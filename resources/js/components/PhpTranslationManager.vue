<template>
    <nav>
        <div class="nav nav-tabs" id="nav-tab">
            <button v-for="(langCode, index) in Object.keys(transFilesContents)" class="nav-link"
                :class="{ 'active': index === 0 }" :set="code = langCode.replace('.json', '')" :id="'nav-' + code + '-tab'"
                data-bs-toggle="tab" :data-bs-target="'#nav-' + code">{{ langCode }}</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div v-for="(transFileContent, langCode) in transFilesContents" class="tab-pane fade show"
            :class="{ 'active': langCode === Object.keys(transFilesContents)[0] }"
            :id="'nav-' + langCode.replace('.json', '')">
            <div class="bg-light p-5 rounded">
                <form>
                    <div v-for="(val, key) in transFileContent" class="row g-3 mb-3">
                        <div class="col">
                            <textarea class="form-control" rows="3">{{ key }}</textarea>
                        </div>
                        <div class="col">
                            <textarea class="form-control" rows="3">{{ val }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        transFilesContents: Object,
    },
    data() {
        return {
        }
    },
    mounted() {
    }
}
</script>
