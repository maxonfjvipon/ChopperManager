import {usePage} from "@inertiajs/inertia-react";
import {Inertia} from "@inertiajs/inertia";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import React, {useEffect, useState} from "react";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {ImportErrorBagDrawer} from "../../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {FileUploader} from "../../../../../../../resources/js/src/Shared/Buttons/FileUploader";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {AuthLayout} from "../../../../../../../resources/js/src/Shared/Layout/AuthLayout";
import Lang from '../../../../../../../resources/js/translation/lang'
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {PumpTechInfoUploader} from "../../Components/PumpTechInfoUploader";

const Index = () => {
    const {pumps, filter_data, flash} = usePage().props
    const {tRoute} = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()
    const [loading, setLoading] = useState(true)

    if (pumps === undefined ) {
        Inertia.reload({
            replace: true,
            preserveScroll: true,
            preserveState: true,
            only: ['pumps']
        })
    }

    useEffect(() => {
        if (pumps !== undefined) {
            setLoading(false)
        }
    }, [pumps])

    const columns = [
        {
            title: Lang.get('pages.pumps.data.article_num_main'),
            dataIndex: 'article_num_main',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.article_num_reserve'),
            dataIndex: 'article_num_reserve',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.article_num_archive'),
            dataIndex: 'part_num_archive',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.brand'),
            dataIndex: 'brand',
            width: 100,
            filters: filter_data.brands,
            onFilter: (brand, record) => record.brand === brand
        },
        {
            title: Lang.get('pages.pumps.data.series'),
            dataIndex: 'series',
            width: 100,
            filters: filter_data.series,
            onFilter: (series, record) => record.series === series
        },
        {
            title: Lang.get('pages.pumps.data.name'),
            dataIndex: 'name',
            width: 150
        },
        {
            title: Lang.get('pages.pumps.data.category'),
            dataIndex: 'category',
            width: 100,
            filters: filter_data.categories,
            onFilter: (category, record) => record.category === category
        },
        {
            title: Lang.get('pages.pumps.data.price'),
            dataIndex: 'price',
            width: 70,
            sorter: {compare: (a, b) => a.price - b.price}
        },
        {
            title: Lang.get('pages.pumps.data.currency'),
            dataIndex: 'currency',
            key: 'currency',
            width: 70
        },
        {
            title: Lang.get('pages.pumps.data.weight'),
            dataIndex: 'weight',
            key: 'weight',
            width: 90,
            sorter: {compare: (a, b) => a.weight - b.weight}
        },
        {
            title: Lang.get('pages.pumps.data.rated_power'),
            dataIndex: 'rated_power',
            width: 95,
            sorter: {compare: (a, b) => a.rated_power - b.rated_power}
        },
        {
            title: Lang.get('pages.pumps.data.rated_current'),
            dataIndex: 'rated_current',
            width: 100,
            sorter: {compare: (a, b) => a.rated_current - b.rated_current}
        },
        {
            title: Lang.get('pages.pumps.data.connection_type'),
            dataIndex: 'connection_type',
            width: 120,
            filters: filter_data.connection_types,
            onFilter: (connection_type, record) => record.connection_type === connection_type
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_min'),
            dataIndex: 'fluid_temp_min',
            width: 110,
            sorter: {
                compare: (a, b) => a.fluid_temp_min - b.fluid_temp_min
            },
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_max'),
            dataIndex: 'fluid_temp_max',
            width: 110,
            sorter: {
                compare: (a, b) => a.fluid_temp_max - b.fluid_temp_max
            },
        },
        {
            title: Lang.get('pages.pumps.data.ptp_length'),
            dataIndex: 'ptp_length',
            width: 150,
            sorter: {
                compare: (a, b) => a.ptp_length - b.ptp_length
            },

        },
        {
            title: Lang.get('pages.pumps.data.dn_suction'),
            dataIndex: 'dn_suction',
            width: 110,
            sorter: {
                compare: (a, b) => a.dn_suction - b.dn_suction
            },
            filters: filter_data.dns,
            onFilter: (dn_suction, record) => record.dn_suction === dn_suction
        },
        {
            title: Lang.get('pages.pumps.data.dn_pressure'),
            dataIndex: 'dn_pressure',
            width: 110,
            sorter: {
                compare: (a, b) => a.dn_pressure - b.dn_pressure
            },
            filters: filter_data.dns,
            onFilter: (dn_pressure, record) => record.dn_pressure === dn_pressure
        },
        {
            title: Lang.get('pages.pumps.data.power_adjustment'),
            dataIndex: 'power_adjustment',
            width: 140,
            filters: filter_data.power_adjustments,
            onFilter: (power_adjustment, record) => record.power_adjustment === power_adjustment
        },
        {
            title: Lang.get('pages.pumps.data.connection'),
            dataIndex: 'mains_connection',
            width: 90,
            filters: filter_data.mains_connections,
            onFilter: (mains_connection, record) => record.mains_connection === mains_connection
        },
        {
            title: Lang.get('pages.pumps.data.types'),
            dataIndex: 'types',
            width: 360,
            filters: filter_data.types,
            onFilter: (type, record) => record.types.split(', ').includes(type)
        },
        {
            title: Lang.get('pages.pumps.data.applications'),
            dataIndex: 'applications',
            width: 450,
            filters: filter_data.applications,
            onFilter: (application, record) => record.applications.split(', ').includes(application)
        },
        {
            key: 'actions', width: "1%", render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('pump_show') && <View clickHandler={showPumpClickHandler(record.id)}/>}
                    </TableActionsContainer>
                )
            }
        }
    ]

    const showPumpClickHandler = id => () => {
        Inertia.get(tRoute('pumps.show', id))
    }

    return (
        <>
            <ImportErrorBagDrawer title={Lang.get('pages.pumps.errors_title')}/>
            <IndexContainer
                title={Lang.get('pages.pumps.title')}
                actions={filterPermissionsArray([
                    has('pump_import') && <FileUploader
                        route={tRoute('pumps.import')}
                        title={Lang.get('pages.pumps.upload')}
                    />,
                    has('price_list_import') && <FileUploader
                        route={tRoute('pumps.import.price_lists')}
                        title={Lang.get('pages.pumps.upload_price_lists')}
                    />,
                    has('pump_import') && <PumpTechInfoUploader/>
                ])}
            >
                <TTable
                    columns={columns}
                    dataSource={pumps}
                    doubleClickHandler={has('pump_show') && showPumpClickHandler}
                    scroll={{x: 4000, y: 630}}
                    loading={loading}
                />
            </IndexContainer>
        </>
    )
}

Index.layout = page => <AuthLayout children={page} title={Lang.get('pages.pumps.title')}/>

export default Index