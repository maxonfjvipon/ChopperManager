import React, {useEffect} from 'react'
import {usePage} from "@inertiajs/inertia-react";
import {message} from "antd";
import {useCheck} from "../../Hooks/check.hook";

export const ErrorLayout = ({children}) => {
    const {errors} = usePage().props
    const {isEmptyObj, isUndefined} = useCheck()

    useEffect(() => {
        if (errors && !isUndefined(errors) && !isEmptyObj(errors))
            for (let key in errors) {
                message.error(errors[key])
            }
    }, [errors])

    return children
}
