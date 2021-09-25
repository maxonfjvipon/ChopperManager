import {
    Button,
    Space,
    Tag,
    Tooltip,
} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {Inertia} from "@inertiajs/inertia";
import React, {useEffect} from "react";
import {FileUploader} from "../../Shared/Buttons/FileUploader";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import {TTable} from "../../Shared/ResourcePanel/Index/Table/TTable";
import {EditOutlined} from "@ant-design/icons";

const Index = () => {
    const {pumps, _pumps} = usePage().props

    // useEffect(() => {
    //     console.log("pumps", pumps)
    // }, [pumps])

    // const columns = [
    //     {
    //         title: Lang.get('pages.pumps.data.article_num_main'),
    //         dataIndex: 'article_num_main',
    //         width: 120
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.article_num_reserve'),
    //         dataIndex: 'article_num_reserve',
    //         width: 120
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.article_num_archive'),
    //         dataIndex: 'part_num_archive',
    //         width: 120
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.brand'),
    //         dataIndex: 'series',
    //         width: 100,
    //         render: series => series.brand.name,
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.series'),
    //         dataIndex: 'series',
    //         render: series => series.name,
    //         width: 100
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.name'),
    //         dataIndex: 'name',
    //         width: 150
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.category'),
    //         dataIndex: 'category',
    //         render: category => category.name,
    //         width: 100
    //     },
    //     {title: Lang.get('pages.pumps.data.price'), dataIndex: 'price', width: 70},
    //     {
    //         title: Lang.get('pages.pumps.data.currency'),
    //         dataIndex: 'currency',
    //         key: 'currency',
    //         render: currency => currency.code,
    //         width: 70
    //     },
    //     {title: Lang.get('pages.pumps.data.weight'), dataIndex: 'weight', key: 'weight', width: 90},
    //     {title: Lang.get('pages.pumps.data.rated_power'), dataIndex: 'rated_power', width: 95},
    //     {
    //         title: Lang.get('pages.pumps.data.rated_current'),
    //         dataIndex: 'rated_current',
    //         width: 80
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.connection_type'),
    //         dataIndex: 'connection_type',
    //         render: connectionType => connectionType.name,
    //         width: 120
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.fluid_temp_min'),
    //         dataIndex: 'fluid_temp_min',
    //         width: 160
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.fluid_temp_max'),
    //         dataIndex: 'fluid_temp_max',
    //         width: 170
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.ptp_length'),
    //         dataIndex: 'ptp_length',
    //         width: 150
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.dn_suction'),
    //         dataIndex: 'dn_suction',
    //         render: dn => dn.value,
    //         width: 90
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.dn_pressure'),
    //         dataIndex: 'dn_pressure',
    //         render: dn => dn.value,
    //         width: 90
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.power_adjustment'),
    //         dataIndex: 'series',
    //         render: series => series.power_adjustment.name,
    //         width: 140
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.connection'),
    //         dataIndex: 'connection',
    //         render: connection => connection.phase + "(" + connection.voltage + ")",
    //         width: 90
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.types'),
    //         dataIndex: 'series',
    //         width: 360,
    //         render: series => (
    //             <Space size={[0, 5]} wrap>
    //                 {series.types.map(type => <Tag color="green" key={type.name}>{type.name}</Tag>)}
    //             </Space>
    //         )
    //     },
    //     {
    //         title: Lang.get('pages.pumps.data.applications'),
    //         dataIndex: 'series',
    //         width: 450,
    //         render: series => (
    //             <Space size={[0, 5]} wrap>
    //                 {series.applications.map(application => <Tag color="blue"
    //                                                       key={application.name}>{application.name}</Tag>)}
    //             </Space>
    //         )
    //     },
    //     {
    //         key: 'actions', width: "1%", render: (_, record) => {
    //             return (
    //                 <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
    //                     <Button
    //                         onClick={showPumpClickHandler(record.id)}
    //                         icon={<EditOutlined/>}
    //                     />
    //                 </Tooltip>
    //             )
    //         }
    //     }
    // ]

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
            // render: series => series.brand.name,
        },
        {
            title: Lang.get('pages.pumps.data.series'),
            dataIndex: 'series',
            // render: series => series.name,
            width: 100
        },
        {
            title: Lang.get('pages.pumps.data.name'),
            dataIndex: 'name',
            width: 150
        },
        {
            title: Lang.get('pages.pumps.data.category'),
            dataIndex: 'category',
            // render: category => category.name,
            width: 100
        },
        {title: Lang.get('pages.pumps.data.price'), dataIndex: 'price', width: 70},
        {
            title: Lang.get('pages.pumps.data.currency'),
            dataIndex: 'currency',
            key: 'currency',
            // render: currency => currency.code,
            width: 70
        },
        {title: Lang.get('pages.pumps.data.weight'), dataIndex: 'weight', key: 'weight', width: 90},
        {title: Lang.get('pages.pumps.data.rated_power'), dataIndex: 'rated_power', width: 95},
        {
            title: Lang.get('pages.pumps.data.rated_current'),
            dataIndex: 'rated_current',
            width: 80
        },
        {
            title: Lang.get('pages.pumps.data.connection_type'),
            dataIndex: 'connection_type',
            // render: connectionType => connectionType.name,
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_min'),
            dataIndex: 'fluid_temp_min',
            width: 160
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_max'),
            dataIndex: 'fluid_temp_max',
            width: 170
        },
        {
            title: Lang.get('pages.pumps.data.ptp_length'),
            dataIndex: 'ptp_length',
            width: 150
        },
        {
            title: Lang.get('pages.pumps.data.dn_suction'),
            dataIndex: 'dn_suction',
            // render: dn => dn.value,
            width: 90
        },
        {
            title: Lang.get('pages.pumps.data.dn_pressure'),
            dataIndex: 'dn_pressure',
            // render: dn => dn.value,
            width: 90
        },
        {
            title: Lang.get('pages.pumps.data.power_adjustment'),
            dataIndex: 'power_adjustment',
            // render: series => series.power_adjustment.name,
            width: 140
        },
        {
            title: Lang.get('pages.pumps.data.connection'),
            dataIndex: 'mains_connection',
            // render: connection => connection.phase + "(" + connection.voltage + ")",
            width: 90
        },
        {
            title: Lang.get('pages.pumps.data.types'),
            dataIndex: 'types',
            width: 360,
            // render: series => (
            //     <Space size={[0, 5]} wrap>
            //         {series.types.map(type => <Tag color="green" key={type.name}>{type.name}</Tag>)}
            //     </Space>
            // )
        },
        {
            title: Lang.get('pages.pumps.data.applications'),
            dataIndex: 'applications',
            width: 450,
            // render: series => (
            //     <Space size={[0, 5]} wrap>
            //         {series.applications.map(application => <Tag color="blue"
            //                                                      key={application.name}>{application.name}</Tag>)}
            //     </Space>
            // )
        },
        {
            key: 'actions', width: "1%", render: (_, record) => {
                return (
                    <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
                        <Button
                            onClick={showPumpClickHandler(record.id)}
                            icon={<EditOutlined/>}
                        />
                    </Tooltip>
                )
            }
        }
    ]

    const showPumpClickHandler = id => () => {
        Inertia.get(route('pumps.show', id))
    }

    return (
        <Container
            mainActionComponent={<FileUploader
                route={route('pumps.import')}
                title={Lang.get('pages.pumps.upload')}
            />}
            title={Lang.get('pages.pumps.title')}
        >
            <TTable
                columns={columns}
                dataSource={pumps}
                showHandler={showPumpClickHandler}
                scroll={{x: 4000, y: 630}}
            />
        </Container>
    )
}

Index.layout = page =>
    <AuthLayout children={page} title={Lang.get('pages.pumps.title')}/>

export default Index
