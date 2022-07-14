import React from 'react';
import {Tag, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useNotifications} from "../../../../../../resources/js/src/Hooks/notifications.hook";
import {Delete} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Delete";
import {Edit} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";
import {ComplexPrimaryAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/ComplexPrimaryAction";
import {FileAddOutlined} from "@ant-design/icons";

export default function Index() {
    // HOOKS
    const {filter_data, brands, series} = usePage().props
    const {openRestoreNotification} = useNotifications()

    // console.log(filter_data)

    // CONSTS
    const brandsColumns = [
        {
            title: "Наименование",
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: "Страна",
            dataIndex: 'country',
            filters: filter_data.countries,
            onFilter: (country, record) => record.country === country
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <Edit clickHandler={editBrandHandler(record.id)}/>
                        <Delete
                            sureDeleteTitle="Вы точно хотите удалить бренд?"
                            confirmHandler={deleteBrandHandler(record.id)}
                        />
                    </TableActionsContainer>
                )
            }
        },
    ]

    const seriesColumns = [
        {
            title: "Наименование",
            dataIndex: 'name',
            sorter: (a, b) => a.name > b.name ? 1 : (b.name > a.name) ? -1 : 0,
            defaultSortOrder: 'ascend',
        },
        {
            title: "Валюта",
            dataIndex: 'currency',
        },
        {
            title: "Действует",
            dataIndex: 'is_discontinued',
            render: (_, record) => {
                return record.is_discontinued
                    ? <Tag color="orange">Нет</Tag>
                    : <Tag color="green">Да</Tag>
            }
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <Edit clickHandler={editSeriesHandler(record.id)}/>
                        <Delete
                            confirmHandler={deleteSeriesHandler(record.id)}
                            sureDeleteTitle="Вы точно хотите удалить серию?"
                        />
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const deleteBrandHandler = id => () => {
        Inertia.delete(route('pump_brands.destroy', id))
        openRestoreNotification(
            "Восстановить бренд?",
            route('pump_brands.restore', id),
        )
    }

    const editBrandHandler = id => () => {
        Inertia.get(route('pump_brands.edit', id))
    }

    const deleteSeriesHandler = id => () => {
        Inertia.delete(route('pump_series.destroy', id))
        openRestoreNotification(
            "Восстановить серию?",
            route('pump_series.restore', id),
        )
    }

    const editSeriesHandler = id => () => {
        Inertia.get(route('pump_series.edit', id))
    }

    return (
        <>
            <ImportErrorBagDrawer title="Ошибки загрузки"/>
            <IndexContainer
                title="Бренды и серии"
                actions={[
                    <ComplexPrimaryAction
                        label="Создать"
                        actions={[{
                            label: "Бренд",
                            route: route('pump_brands.create'),
                            icon: <FileAddOutlined/>
                        }, {
                            label: "Серию",
                            route: route('pump_series.create'),
                            icon: <FileAddOutlined/>
                        }]}
                    />
                ]}
            >
                <TTable
                    columns={brandsColumns}
                    dataSource={brands}
                    doubleClickHandler={editBrandHandler}
                    expandable={{
                        expandedRowRender: (record) => <TTable
                            columns={seriesColumns}
                            dataSource={series.filter(_series => _series.brand === record.name)}
                            doubleClickHandler={editSeriesHandler}
                        />
                    }}
                />
            </IndexContainer>
        </>
    )
}
