export function filterErrors() {
    const trans = this.transFilesContents[this.langCode];
    for (const key in trans) {
        if(!trans[key].meta.error){
            trans[key].meta.visible = !trans[key].meta.visible;
        }
    }
}
