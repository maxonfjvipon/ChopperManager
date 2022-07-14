import React, {useEffect, useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useDate} from "../../../../../../../resources/js/src/Hooks/date.hook";
import {SearchInput} from "../../../../../../../resources/js/src/Shared/SearchInput";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {useLabels} from "../../../../../../../resources/js/src/Hooks/labels.hook";

export default function Index() {
    // HOOKS
    const {contractors, filter_data} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {compareDate} = useDate()
    const {labels} = useLabels()

    // STATE
    const [contractorsToShow, setContractorsToShow] = useState(contractors)

    // CONSTS
    const searchId = 'contractor-search-input'
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
        }, {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showContractorHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ])

    const showContractorHandler = id => () => {
        Inertia.get(route('contractors.show', id))
    }

    const searchContractorClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setContractorsToShow(dealers)
        } else {
            setContractorsToShow(contractors
                .filter(contractor => contractor
                        .name
                        .toLowerCase()
                        .includes(value)
                    || contractor
                        .itn
                        .includes(value))
            )
        }
    }

    // EFFECTS
    useEffect(() => {
        setContractorsToShow(contractors)
    }, [contractors])

    // RENDER
    return (
        <IndexContainer
            title={"Контрагенты"}
        >
            <SearchInput
                id={searchId}
                placeholder="Поиск по наименованию и ИНН"
                searchClickHandler={searchContractorClickHandler}
                width={450}
            />
            <TTable
                columns={columns}
                dataSource={contractorsToShow}
                doubleClickHandler={showContractorHandler}
            />
        </IndexContainer>
    )
}
