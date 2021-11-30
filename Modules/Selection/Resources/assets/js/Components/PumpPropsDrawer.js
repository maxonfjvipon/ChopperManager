import {Col, Drawer, Input, List, Tabs} from "antd";
import {FilePdfOutlined} from "@ant-design/icons";
import Lang from "../../../../../../resources/js/translation/lang";
import React from "react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";

export const PumpPropsDrawer = ({visible, setVisible, pumpInfo}) => {
    const _img = ({src}) => (
        <JustifiedRow>
            <Col>
                <img style={{margin: "0 auto", maxWidth: 600}} alt='./media/no_image.jpg'
                     src={src}/>
            </Col>
        </JustifiedRow>
    )

    const {reducedAntFormItemClassName} = useStyles()

    const items = [
        {
            values: {
                name: 'article_num_main',
                label: Lang.get('pages.pumps.data.article_num_main'),
                initialValue: pumpInfo.article_num_main,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'article_num_reserve',
                label: Lang.get('pages.pumps.data.article_num_reserve'),
                initialValue: pumpInfo.article_num_reserve,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'article_num_archive',
                label: Lang.get('pages.pumps.data.article_num_archive'),
                initialValue: pumpInfo.article_num_archive,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'weight',
                label: Lang.get('pages.pumps.data.weight'),
                initialValue: pumpInfo.weight,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'rated_power',
                label: Lang.get('pages.pumps.data.rated_power'),
                initialValue: pumpInfo.rated_power,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'rated_current',
                label: Lang.get('pages.pumps.data.rated_current'),
                initialValue: pumpInfo.rated_current,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'connection_type',
                label: Lang.get('pages.pumps.data.connection_type'),
                initialValue: pumpInfo.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'fluid_temp_min',
                label: Lang.get('pages.pumps.data.fluid_temp_min'),
                initialValue: pumpInfo.fluid_temp_min,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'fluid_temp_max',
                label: Lang.get('pages.pumps.data.fluid_temp_max'),
                initialValue: pumpInfo.fluid_temp_max,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'ptp_length',
                label: Lang.get('pages.pumps.data.ptp_length'),
                initialValue: pumpInfo.ptp_length,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'dn_suction',
                label: Lang.get('pages.pumps.data.dn_suction'),
                initialValue: pumpInfo.dn_suction,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'dn_pressure',
                label: Lang.get('pages.pumps.data.dn_pressure'),
                initialValue: pumpInfo.dn_pressure,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'category',
                label: Lang.get('pages.pumps.data.category'),
                initialValue: pumpInfo.category,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'connection',
                label: Lang.get('pages.pumps.data.connection'),
                initialValue: pumpInfo.connection,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'types',
                label: Lang.get('pages.pumps.data.types'),
                initialValue: pumpInfo.types,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'applications',
                label: Lang.get('pages.pumps.data.applications'),
                initialValue: pumpInfo.applications,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'description',
                // label: Lang.get('pages.pumps.data.applications'),
                label: Lang.get('pages.pumps.data.description'),
                initialValue: pumpInfo.description,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly/>
        },
    ]

    return (
        <Drawer
            width={650}
            placement="right"
            title={Lang.get('pages.selections.single.graphic.info')}
            visible={visible}
            closable={false}
            onClose={() => {
                setVisible(false)
            }}
        >
            {pumpInfo && <Tabs type="card" defaultActiveKey="curve">
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.props')} key="props">
                    <ItemsForm
                        layout="horizontal"
                        labelSpan={{xs: 6}}
                        items={items}
                    />
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.model_picture')} key="model_pic">
                    <_img src={pumpInfo.images.pump}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.sizes_picture')} key="size_pic">
                    <_img src={pumpInfo.images.sizes}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.electric_diagram')} key="electric_pic">
                    <_img src={pumpInfo.images.electric_diagram}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.exploded_view')} key="expl_pic">
                    <_img src={pumpInfo.images.cross_sectional_drawing}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single.pump_info.files')} key="files">
                    <List
                        size="large"
                        itemLayout="horizontal"
                        dataSource={pumpInfo.files}
                        renderItem={file => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={<FilePdfOutlined/>}
                                    title={<a download href={file.link}>{file.name}</a>}
                                />
                            </List.Item>
                        )}
                    />
                </Tabs.TabPane>
            </Tabs>}
        </Drawer>
    )
}