export const useCookie = () => {
    const cookieByKey = key => {
        const matches = document.cookie.match(new RegExp("(?:^|; )" + key.replace(/([.$?*|{}()\[\]\\\/+^])/g, '\\$1') + "=([^;]*)"));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    const setCookie = (name, value, options = {
        expires: null, maxAge: 86400
    }) => {
        let cookie = name + '=' + value
        if (options) {
            cookie += ';' + (options.expires ? 'expires=' + options.expires : 'max-age=' + options.maxAge)
        }
        document.cookie = cookie
    }

    const deleteCookieByKey = (key) => {
        document.cookie = key + '=;max-age=-1'
    }

    return {cookieByKey, setCookie, deleteCookieByKey}
}


