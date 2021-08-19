export const useCheck = () => {
    const isEmptyStr = (what) => {
        return what === ''
    }

    const isArrayEmpty = array => {
        return array === undefined || array.length === 0
    }

    const isNull = (what) => {
        return what == null
    }

    const isUndefined = what => {
        return what === undefined
    }

    const valueFromDocById = (id) => {
        return document.getElementById(id).value.toString().trim()
    }

    const isEmptyObj = obj => {
        return JSON.stringify(obj) === JSON.stringify({})
    }

    return {
        isEmptyStr,
        isArrayEmpty,
        isNull,
        isUndefined,
        valueFromDocById,
        isEmptyObj
    }
}
