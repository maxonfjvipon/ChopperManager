import React from 'react'
import {Input} from "antd";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {BoxFlex} from "../../../../../../resources/js/src/Shared/Box/BoxFlex";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {Inertia} from "@inertiajs/inertia";

export const UserInfoTab = () => {
    const {reducedAntFormItemClassName, margin} = useStyles()
    const {rules} = useInputRules()
    const {user, filter_data} = usePage().props

    const profileFormName = 'profile-form'

    const items = [
        {
            values: {
                name: 'itn',
                label: "ИНН",
                rules: rules.itn,
                initialValue: user.itn,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone',
                label: "Контактный телефон",
                initialValue: user.phone,
                rules: [rules.phone],
            },
            input: <Input/>,
        },
        {
            values: {
                name: 'area_id',
                label: "Область",
                rules: [rules.required],
                initialValue: user.area_id,
            },
            input: <Selection options={filter_data.areas}/>
        },
        {
            values: {
                name: 'first_name',
                label: "Имя",
                rules: [rules.required, rules.max(255)],
                initialValue: user.first_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'middle_name',
                label: "Фамилия",
                rules: [rules.required, rules.max(255)],
                initialValue: user.middle_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'last_name',
                label: "Отчество",
                rules: [rules.max(255)],
                initialValue: user.last_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'email',
                label: "Email",
                rules: rules.email,
                initialValue: user.email,
            },
            input: <Input/>
        },
    ]

    const updateProfileHandler = async body => {
        Inertia.post(route('profile.update'), body, {
            preserveScroll: true,
        })
    }

    return (
        <>
            <RoundedCard
                type="inner"
                title="Профиль пользователя"
            >
                <ItemsForm
                    layout="horizontal"
                    labelSpan={{xxl: 7, xl: 10, lg: 10, md: 12, sm: 12}}
                    items={items}
                    onFinish={updateProfileHandler}
                    name={profileFormName}
                />
            </RoundedCard>
            <BoxFlex style={margin.top(16)}>
                <PrimaryButton htmlType="submit" form={profileFormName}>
                    Сохранить изменения
                </PrimaryButton>
            </BoxFlex>
        </>
    )
}
