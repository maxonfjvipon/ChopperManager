import lang from 'lang.js'
import translations from './translations'

const Lang = new lang({
    messages: translations,
    locale: 'en',
    fallback: 'en'
})

export default Lang
