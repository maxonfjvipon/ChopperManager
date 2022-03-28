import React, {useEffect, useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import AuthLayout from "../../Shared/Layout/AuthLayout";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {SearchInput} from "../../Shared/SearchInput";
import {IndexContainer} from "../../Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {TableActionsContainer} from "../../Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../Shared/Resource/Table/Actions/View";
import {Edit} from "../../Shared/Resource/Table/Actions/Edit";

const Index = () => {
    // HOOKS
    const {partners} = usePage().props

    // STATE
    const [partnersToShow, setPartnersToShow] = useState(partners)

    // CONSTS
    const searchId = 'partner-search-input'

    // CONSTS
    const columns = [
        {
            title: "Наименование",
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {title: 'ИНН', dataIndex: 'itn'},
        {title: 'Область', dataIndex: 'area'},
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showPartnerHandler(record.id)}/>
                        <Edit clickHandler={editPartnerHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ]

    const editPartnerHandler = id => () => {
        Inertia.get(route('partners.edit', id))
    }

    const showPartnerHandler = id => () => {
        Inertia.get(route('partners.show', id))
    }

    const searchPartnerClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setPartnersToShow(partners)
        } else {
            setPartnersToShow(partners.filter(partner => partner.name.toLowerCase().includes(value)))
        }
    }

    // // EFFECTS
    // useEffect(() => {
    //     setPartnersToShow(partners)
    // }, [partners])

    // RENDER
    return (
        <IndexContainer
            title={"Контрагенты"}
            actions={<PrimaryAction
                label="Новый контрагент"
                route={route('partners.create')}
            />}
        >
            <SearchInput
                id={searchId}
                placeholder="Поиск по наименованию"
                searchClickHandler={searchPartnerClickHandler}
            />
            <TTable
                columns={columns}
                dataSource={partnersToShow}
                doubleClickHandler={showPartnerHandler}
            />
        </IndexContainer>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
