import {usePage} from "@inertiajs/inertia-react";

export const usePermissions = () => {
    const {permissions} = usePage().props.auth

    const has = (..._permissions) => {
        for (let i = 0; i < _permissions.length; i++) {
            if (!permissions.includes(_permissions[i])) {
                return false
            }
        }
        return true
    }

    const filterPermissionsArray = (arr) => arr.filter(Boolean)

    return {
        has,
        filterPermissionsArray
    }
}
