import {usePage} from '@inertiajs/vue3';

export function __(key, replace = {}) {
    const {props} = usePage();
    const translations = props?.translations || {};

    // Try to find direct match first (flat keys like "search.label")
    let translation = translations[key];

    if (translation === undefined) {
        // If not found, traverse dot notation (nested keys)
        const keyParts = key.split('.');
        translation = keyParts.reduce((acc, part) => {
            return acc && acc[part] !== undefined ? acc[part] : null;
        }, translations);
    }

    // If still not found, fallback to the key itself
    if (translation === null || translation === undefined) {
        translation = key;
    }

    // Perform replacements
    for (const [placeholder, value] of Object.entries(replace)) {
        const regex = new RegExp(`:${placeholder}`, 'gi');
        translation = translation.replace(regex, value);
    }

    return translation;
}