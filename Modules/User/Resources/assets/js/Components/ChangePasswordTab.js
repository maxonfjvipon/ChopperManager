import React from 'react'
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import Lang from "../../../../../../resources/js/translation/lang";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {BoxFlex} from "../../../../../../resources/js/src/Shared/Box/BoxFlex";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {Col, Form, Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";

export const ChangePasswordTab = () => {
    const {reducedAntFormItemClassName, margin} = useStyles()
    const changePasswordForm = 'change-password-form'
    const [changePwdForm] = Form.useForm()
    const tRoute = useTransRoutes()

    const changePasswordHandler = body => {
        Inertia.post(tRoute('profile.password.change'), body, {
            preserveScroll: true
        })
        changePwdForm.resetFields()
    }

    const updatePasswordItems = [
        {
            values: {name: 'current_password', label: Lang.get('pages.profile.index.current_password'), rules: []},
            input: <Input.Password/>
        },
        {
            values: {name: 'password', label: Lang.get('pages.profile.index.new_password'), rules: []},
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: Lang.get('pages.profile.index.new_password_confirmation'),
                rules: [],
                className: reducedAntFormItemClassName
            },
            input: <Input.Password/>
        },
    ]

    return (
        <>
            <RoundedCard
                type="inner"
                title={Lang.get('pages.profile.index.cards.password')}
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
                    {Lang.get('pages.profile.index.change_password')}
                </PrimaryButton>
            </BoxFlex>
        </>
    )
}
