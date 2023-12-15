export function filterErrors() {
    this.filterErrorsOn = !this.filterErrorsOn;
    const trans = this.transFilesContents[this.langCode];
    for (const key in trans) {
        if (!this.filterErrorsOn) {
            trans[key].meta.visible = true;
        } else {
            trans[key].meta.visible = !!trans[key].meta.error;
        }
    }
}
