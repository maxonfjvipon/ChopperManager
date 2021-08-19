const ruleObj = (field, value, message) => {
    const obj = {}
    obj[field] = value
    obj['message'] = message
    return obj
}

export const useInputRules = () => {
    const incorrectTypeMessage = (type) => {
        switch (type) {
            case 'email': return 'Неверный формат Email!'
            case 'number': return 'Неверный формат. Должно быть число'
        }
    }

    const type = type => {
        return ruleObj('type', type, incorrectTypeMessage(type));
    }
    const email = type('email')
    const number = type('number')
    const inn = ruleObj('pattern', RegExp('^(\\d{10}|\\d{12})$'), 'Неверный формат ИНН')
    const phone = ruleObj('pattern', RegExp('^((\\+7|7|8)+([0-9]){10})$'), 'Неверный формат телефона')

    const required = ruleObj('required', true, 'Поле должно быть заполнено!')

    const max = (len) => ruleObj('max', len, 'Недопустимая длина поля: ' + len)

    return {
        rules: {
            email: [required, email],
            password: [required],
            inn: [inn],
            phone: [required, phone],
            required,
            number,
            max,
        }
    }
}
