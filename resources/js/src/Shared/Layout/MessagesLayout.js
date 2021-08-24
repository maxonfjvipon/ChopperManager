import React, {useEffect} from 'react'
import {usePage} from "@inertiajs/inertia-react";
import {message} from "antd";
import {useCheck} from "../../Hooks/check.hook";

export const MessagesLayout = ({children}) => {
    const {flash, errors} = usePage().props

    const {isEmptyObj, isUndefined} = useCheck()

    useEffect(() => {
        if (errors && !isUndefined(errors) && !isEmptyObj(errors))
            for (let key in errors) {
                message.error(errors[key])
            }
    }, [errors])

    useEffect(() => {
        if (flash.success) {
            message.success(flash.success)
        }
        if (flash.warning) {
            message.warning(flash.warning)
        }
        if (flash.info) {
            message.info(flash.info)
        }
    }, [flash])

    return children
}
