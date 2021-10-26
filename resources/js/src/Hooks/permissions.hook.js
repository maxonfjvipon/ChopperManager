import {usePage} from "@inertiajs/inertia-react";

export const usePermissions = () => {
    const {permissions} = usePage().props.auth

    const has = (..._permissions) => {
        _permissions.forEach(() => {
            if (!permissions.includes(_permissions)) {
                return false
            }
        })
        return true
    }

    const filterPermissionsArray = (arr) => arr.filter(Boolean)

    return {
        has,
        filterPermissionsArray
    }
}
