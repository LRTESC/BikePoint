import { localeFallbacks } from '../var/translations/configuration';
import { trans, getLocale, setLocale, setLocaleFallbacks } from '@symfony/ux-translator';

setLocaleFallbacks(localeFallbacks);

export { trans };

export * from '../var/translations';