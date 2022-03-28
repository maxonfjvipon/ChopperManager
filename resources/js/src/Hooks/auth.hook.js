import React, {useState, useEffect, useCallback} from 'react'
import {useCookie} from "./cookie.hook";

const storageName = 'sanctum'
const {setCookie, cookieByKey, deleteCookieByKey} = useCookie()

export const useAuth = () => {
    // const [token, setToken] = useState(undefined)
    const [token, setToken] = useState(null)

    const login = useCallback(token => {
        setToken(token)
        localStorage.setItem(storageName, token)
        // set cookie for 12 hours
        // setCookie(storageName, token, {maxAge: "43200"})
    }, [])

    const logout = useCallback(() => {
        setToken(null)
        localStorage.removeItem(storageName)
        // deleteCookieByKey(storageName)
    }, [])

    useEffect(() => {
        // const isAuth = token !== undefined
        const isAuth = !!token
        if (!isAuth) {
            // const _token = cookieByKey(storageName)
            const _token = localStorage.getItem(storageName)

            // if (_token !== undefined) {
            if (_token) {
                login(_token)
            }
        }
    }, [])

    return {login, logout, token}
}
