import React from 'react'
import {Input} from "antd";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import Lang from "../../../../../../resources/js/translation/lang";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {BoxFlex} from "../../../../../../resources/js/src/Shared/Box/BoxFlex";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {Inertia} from "@inertiajs/inertia";

export const UserInfoTab = () => {
    const {reducedAntFormItemClassName, margin} = useStyles()
    const {rules} = useInputRules()
    const {user, businesses, countries, currencies} = usePage().props
    const tRoute = useTransRoutes()

    const userdata = user.data
    const profileFormName = 'profile-form'

    const items = [
        {
            values: {
                name: 'organization_name',
                label: Lang.get('pages.profile.index.organization_name'),
                rules: [rules.required],
                initialValue: userdata.organization_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'business_id',
                label: Lang.get('pages.profile.index.main_business'),
                rules: [rules.required],
                initialValue: userdata.business_id
            },
            input: <Selection options={businesses}/>
        },
        {
            values: {
                name: 'itn',
                label: Lang.get('pages.profile.index.itn'),
                rules: rules.itn,
                initialValue: userdata.itn,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone',
                label: Lang.get('pages.profile.index.phone'),
                rules: rules.phone,
                initialValue: userdata.phone,
            },
            input: <Input readOnly/>,
        },
        {
            values: {
                name: 'country_id',
                label: Lang.get('pages.profile.index.country'),
                rules: [rules.required],
                initialValue: userdata.country_id
            },
            input: <Selection options={countries}/>
        },
        {
            values: {
                name: 'city',
                label: Lang.get('pages.profile.index.city'),
                initialValue: userdata.city,
                rules: [rules.required]
            },
            input: <Input/>
        },
        {
            values: {
                name: 'currency_id',
                label: Lang.get('pages.profile.index.currency.label'),
                initialValue: userdata.currency_id,
                // tooltip: Lang.get('pages.profile.index.currency.tooltip'),
                rules: [rules.required]
            },
            input: <Selection options={currencies}/>
        },
        {
            values: {
                name: 'first_name',
                label: Lang.get('pages.profile.index.first_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: userdata.first_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'middle_name',
                label: Lang.get('pages.profile.index.middle_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: userdata.middle_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'last_name',
                label: Lang.get('pages.profile.index.last_name'),
                rules: [rules.max(255)],
                initialValue: userdata.last_name
            }, input: <Input/>
        },
        {
            values: {
                name: 'email',
                label: Lang.get('pages.profile.index.email'),
                rules: rules.email,
                initialValue: userdata.email,
                className: reducedAntFormItemClassName
            },
            input: <Input readOnly/>
        },
    ]

    const updateProfileHandler = async body => {
        Inertia.post(tRoute('profile.update'), body, {
            preserveScroll: true,
        })
    }

    return (
        <>
            <RoundedCard
                type="inner"
                title={Lang.get('pages.profile.index.cards.user_info')}
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
                    {Lang.get('pages.profile.index.save_changes')}
                </PrimaryButton>
            </BoxFlex>
        </>
    )
}
