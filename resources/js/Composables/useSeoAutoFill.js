import { ref, watch } from 'vue';

const STOP = new Set([
    'a','an','the','and','or','but','in','on','at','to','for','of','with',
    'by','from','as','is','was','are','were','be','been','being','have',
    'has','had','do','does','did','will','would','could','should','may',
    'might','can','this','that','these','those','it','its','we','our',
    'you','your','hotel','beach','way','cox','bazar',
]);

function buildKeywords(a, b) {
    const text = ((a || '') + ' ' + (b || '')).toLowerCase().replace(/<[^>]*>/g, ' ');
    const words = text.split(/\W+/).filter(w => w.length > 3 && !STOP.has(w));
    return [...new Set(words)].slice(0, 12).join(', ');
}

/**
 * Auto-fills SEO meta fields from content source fields.
 * Stops auto-filling a field the moment the admin manually edits it.
 *
 * @param {object} form           - reactive form object (Inertia useForm or plain reactive)
 * @param {object} opts
 * @param {function} titleSource  - () => string  title source getter
 * @param {function} descSource   - () => string  description source getter
 * @param {string} titleSuffix    - appended to auto title (default ' – Hotel Beach Way')
 * @param {string} titleKey       - form key for meta title     (default 'meta_title')
 * @param {string} descKey        - form key for meta desc      (default 'meta_description')
 * @param {string} keywordsKey    - form key for meta keywords  (default 'meta_keywords')
 */
export function useSeoAutoFill(form, {
    titleSource,
    descSource,
    titleSuffix  = ' – Hotel Beach Way',
    titleKey     = 'meta_title',
    descKey      = 'meta_description',
    keywordsKey  = 'meta_keywords',
} = {}) {
    const titleAuto    = ref(!form[titleKey]);
    const descAuto     = ref(!form[descKey]);
    const keywordsAuto = ref(!form[keywordsKey]);

    watch(titleSource, (val) => {
        if (titleAuto.value)    form[titleKey]    = val ? (val + titleSuffix) : '';
        if (keywordsAuto.value) form[keywordsKey] = buildKeywords(val, descSource());
    });

    watch(descSource, (val) => {
        if (descAuto.value)     form[descKey]     = val || '';
        if (keywordsAuto.value) form[keywordsKey] = buildKeywords(titleSource(), val);
    });

    return {
        onMetaTitleInput:    () => { titleAuto.value    = false; },
        onMetaDescInput:     () => { descAuto.value     = false; },
        onMetaKeywordsInput: () => { keywordsAuto.value = false; },
    };
}
