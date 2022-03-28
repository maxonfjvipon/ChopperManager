
const ruleObj = (field, value, message) => {
    const obj = {}
    obj[field] = value
    obj['message'] = message
    return obj
}

export const useInputRules = () => {
    // const incorrectTypeMessage = (type) => {
    //     switch (type) {
    //         case 'email':
    //             return Lang.get('validation.email', {attribute: Lang.get('validation.attributes.email')})
    //         case 'number':
    //             return Lang.get('validation.numeric')
    //     }
    // }
    //
    // const type = type => {
    //     return ruleObj('type', type, incorrectTypeMessage(type));
    // }
    // const email = type('email')
    // const number = type('number')

    const required = ruleObj('required', true, "Поле обязательно для заполнения")

    // const max = (len) => ruleObj('max', len, Lang.get('validation.max.numeric', {max: len}))

    return {
        rules: {
            // email: [required, email],
            password: [required],
            // itn: [itn],
            // phone: [required, phone],
            // city: citiesToShow => [required, {
            //     validator: (_, cityId) => citiesToShow.some(cityToShow => cityToShow.id === cityId)
            //         ? Promise.resolve()
            //         : Promise.reject(new Error(Lang.get('validation.custom.city.correct')))
            // }],
            required,
            // number,
            // max,
        }
    }
}
