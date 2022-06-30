import React from 'react'
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {BoxFlex} from "../../../../../../resources/js/src/Shared/Box/BoxFlex";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {Form, Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";

export const ChangePasswordTab = () => {
    const {reducedAntFormItemClassName, margin} = useStyles()
    const changePasswordForm = 'change-password-form'
    const [changePwdForm] = Form.useForm()

    const changePasswordHandler = body => {
        Inertia.post(route('profile.password.change'), body, {
            preserveScroll: true
        })
        changePwdForm.resetFields()
    }

    const updatePasswordItems = [
        {
            values: {name: 'current_password', label: "Текущий пароль", rules: []},
            input: <Input.Password/>
        },
        {
            values: {name: 'password', label: "Новый пароль", rules: []},
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation', label: "Повторите новый пароль", rules: [],
                className: reducedAntFormItemClassName
            },
            input: <Input.Password/>
        },
    ]

    return (
        <>
            <RoundedCard
                type="inner"
                title="Изменить пароль"
                style={margin.top(16)}
            >
                <ItemsForm
                    form={changePwdForm}
                    layout="horizontal"
                    labelSpan={{xxl: 6, xl: 10, lg: 10, md: 12, sm: 12}}
                    items={updatePasswordItems}
                    onFinish={changePasswordHandler}
                    name={changePasswordForm}
                />
            </RoundedCard>
            <BoxFlex style={margin.top(16)}>
                <PrimaryButton htmlType="submit" form={changePasswordForm}>
                    Изменить пароль
                </PrimaryButton>
            </BoxFlex>
        </>
    )
}
