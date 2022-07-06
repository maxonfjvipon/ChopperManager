import React, {useEffect, useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useDate} from "../../../../../../../resources/js/src/Hooks/date.hook";
import {SearchInput} from "../../../../../../../resources/js/src/Shared/SearchInput";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {useLabels} from "../../../../../../../resources/js/src/Hooks/labels.hook";

export default function Index() {
    // HOOKS
    const {dealers, filter_data} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {compareDate} = useDate()
    const {labels} = useLabels()

    // STATE
    const [dealersToShow, setDealersToShow] = useState(dealers)

    // CONSTS
    const searchId = 'dealer-search-input'
    const columns = filteredBoolArray([
        {
            title: labels.created_at,
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
        }, {
            title: labels.name,
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: labels.area,
            dataIndex: 'area',
            width: 180, render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            filters: filter_data.areas,
            onFilter: (area, record) => record.area === area
        },
        {
            title: labels.itn,
            dataIndex: 'itn'
        },
        {
            title: labels.phone,
            dataIndex: 'phone'
        },
        {
            title: labels.email,
            dataIndex: 'email'
        }, {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showDealerHandler(record.id)}/>
                        <Edit clickHandler={editDealerHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ])

    const editDealerHandler = id => () => {
        Inertia.get(route('dealers.edit', id))
    }

    const showDealerHandler = id => () => {
        Inertia.get(route('dealers.show', id))
    }

    const searchDealerClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setDealersToShow(dealers)
        } else {
            setDealersToShow(dealers.filter(dealer => dealer.name.toLowerCase().includes(value)))
        }
    }

    // EFFECTS
    useEffect(() => {
        setDealersToShow(dealers)
    }, [dealers])

    // RENDER
    return (
        <IndexContainer
            title={"Дилеры"}
            actions={<PrimaryAction
                label="Создать дилера"
                route={route('dealers.create')}
            />}
        >
            <SearchInput
                id={searchId}
                placeholder="Поиск по наименованию"
                searchClickHandler={searchDealerClickHandler}
                width={450}
            />
            <TTable
                columns={columns}
                dataSource={dealersToShow}
                doubleClickHandler={showDealerHandler}
            />
        </IndexContainer>
    )
}
