import React, {useEffect} from 'react';
import {Row, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../translation/lang";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {useNotifications} from "../../Hooks/notifications.hook";
import {IndexContainer} from "../../Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {FlexCol} from "../../Shared/FlexCol";
import {TableActionsContainer} from "../../Shared/Resource/Table/Actions/TableActionsContainer";
import {Edit} from "../../Shared/Resource/Table/Actions/Edit";
import {Delete} from "../../Shared/Resource/Table/Actions/Delete";
import {usePermissions} from "../../Hooks/permissions.hook";

const Index = () => {
    // HOOKS
    const {filter_data, brands, series} = usePage().props
    const {tRoute} = useTransRoutes()
    const {openRestoreNotification} = useNotifications()
    const {has, filterPermissionsArray} = usePermissions()

    // CONSTS
    const brandsColumns = [
        {
            title: Lang.get('pages.pump_brands.index.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('brand_edit') && <Edit clickHandler={editBrandHandler(record.id)}/>}
                        {has('brand_delete') && <Delete
                            sureDeleteTitle={Lang.get('pages.pump_brands.index.table.delete')}
                            confirmHandler={deleteBrandHandler(record.id)}
                        />}
                    </TableActionsContainer>
                )
            }
        },
    ]

    const seriesColumns = [
        {
            title: Lang.get('pages.pump_series.index.table.brand'),
            dataIndex: 'brand',
            filters: filter_data.brands,
            onFilter: (brand, record) => record.brand === brand
        },
        {
            title: Lang.get('pages.pump_series.index.table.name'),
            dataIndex: 'name',
            sorter: (a, b) => a.name > b.name ? 1 : (b.name > a.name) ? -1 : 0,
            defaultSortOrder: 'ascend',
        },
        {
            title: Lang.get('pages.pump_series.index.table.category'),
            dataIndex: 'category',
            filters: filter_data.categories,
            onFilter: (category, record) => record.category === category
        },
        {
            title: Lang.get('pages.pump_series.index.table.power_adjustment'),
            dataIndex: 'power_adjustment',
            filters: filter_data.power_adjustments,
            onFilter: (power_adjustment, record) => record.power_adjustment === power_adjustment
        },
        {
            title: Lang.get('pages.pump_series.index.table.applications'),
            dataIndex: 'applications',
            filters: filter_data.applications,
            onFilter: (application, record) => record.applications.split(', ').includes(application)
        },
        {
            title: Lang.get('pages.pump_series.index.table.types'),
            dataIndex: 'types',
            filters: filter_data.types,
            onFilter: (type, record) => record.types.split(', ').includes(type)
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('series_edit') && <Edit clickHandler={editSeriesHandler(record.id)}/>}
                        {has('series_delete') && <Delete
                            confirmHandler={deleteSeriesHandler(record.id)}
                            sureDeleteTitle={Lang.get('pages.pump_series.index.table.delete')}
                        />}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const deleteBrandHandler = id => () => {
        Inertia.delete(tRoute('pump_brands.destroy', id))
        if (has('brand_restore'))
            openRestoreNotification(
                Lang.get('pages.pump_brands.index.restore.title'),
                tRoute('pump_brands.restore', id),
                Lang.get('pages.pump_brands.index.restore.button')
            )
    }

    const editBrandHandler = id => () => {
        Inertia.get(tRoute('pump_brands.edit', id))
    }

    const deleteSeriesHandler = id => () => {
        Inertia.delete(tRoute('pump_series.destroy', id))
        if (has('series_restore'))
            openRestoreNotification(
                Lang.get('pages.pump_series.index.restore.title'),
                tRoute('pump_series.restore', id),
                Lang.get('pages.pump_series.index.restore.button')
            )
    }

    const editSeriesHandler = id => () => {
        Inertia.get(tRoute('pump_series.edit', id))
    }

    // RENDER
    return (
        <IndexContainer
            title={Lang.get('pages.pump_series.index.title')}
            actions={filterPermissionsArray([
                has('brand_create') && <PrimaryAction
                    label={Lang.get('pages.pump_brands.index.button')}
                    route={tRoute('pump_brands.create')}
                />,
                has('series_create') && <PrimaryAction
                    label={Lang.get('pages.pump_series.index.button')}
                    route={tRoute('pump_series.create')}
                />
            ])}
        >
            <TTable
                columns={brandsColumns}
                dataSource={brands}
                doubleClickHandler={has('brand_edit') && editBrandHandler}
                expandable={{
                    expandedRowRender: <TTable
                        columns={seriesColumns}
                        dataSource={series}
                        doubleClickHandler={has('series_edit') && editSeriesHandler}
                    />
                }}
            />
        </IndexContainer>


    // <Row gutter={[16, 0]} style={{flex: "auto"}}>
    //     <FlexCol span={4}>
    //         <IndexContainer
    //             title={Lang.get('pages.pump_brands.index.title')}
    //             actions={has('brand_create') && <PrimaryAction
    //                 label={Lang.get('pages.pump_brands.index.button')}
    //                 route={tRoute('pump_brands.create')}
    //             />}
    //         >
    //             <TTable
    //                 columns={brandsColumns}
    //                 dataSource={brands}
    //                 doubleClickHandler={has('brand_edit') && editBrandHandler}
    //             />
    //         </IndexContainer>
    //     </FlexCol>
    //     <FlexCol span={20}>
    //         <IndexContainer
    //             title={Lang.get('pages.pump_series.index.title')}
    //             actions={has('series_create') && <PrimaryAction
    //                 label={Lang.get('pages.pump_series.index.button')}
    //                 route={tRoute('pump_series.create')}
    //             />}
    //         >
    //             <TTable
    //                 columns={seriesColumns}
    //                 dataSource={series}
    //                 doubleClickHandler={has('series_edit') && editSeriesHandler}
    //             />
    //         </IndexContainer>
    //     </FlexCol>
    // </Row>
)
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
