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

export function filterUnusedTrans() {
    this.filterUnusedTransOn = !this.filterUnusedTransOn;
    const trans = this.transFilesContents[this.langCode];
    for (const key in trans) {
        trans[key].meta.visible = this.filterUnusedTransOn
            ? trans[key].meta.unused
            : true;
    }
}
