import React, {useEffect} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {Input, Row, Space} from "antd";
import {
    VictoryChart,
    VictoryLine,
    VictoryScatter,
    VictoryVoronoiContainer,
    VictoryTooltip,
    VictoryAxis, VictoryTheme
} from 'victory'
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {FlexCol} from "../../../../../../../resources/js/src/Shared/FlexCol";
import {BackLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";
import Lang from '../../../../../../../resources/js/translation/lang'
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {RoundedCard} from "../../../../../../../resources/js/src/Shared/Cards/RoundedCard";

export default function Show() {
    const {pump} = usePage().props
    const {reducedAntFormItemClassName} = useStyles()
    const tRoute = useTransRoutes()

    useEffect(() => {
        let div = document.getElementById('for-graphic')
        div.innerHTML = pump?.data.svg
    }, [pump])

    const items = [
        {
            values: {
                name: 'article_num_main',
                label: Lang.get('pages.pumps.data.article_num_main'),
                initialValue: pump.data.article_num_main,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'article_num_reserve',
                label: Lang.get('pages.pumps.data.article_num_reserve'),
                initialValue: pump.data.article_num_reserve,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'article_num_archive',
                label: Lang.get('pages.pumps.data.article_num_archive'),
                initialValue: pump.data.article_num_archive,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'price',
                label: Lang.get('pages.pumps.data.price'),
                initialValue: pump.data.price,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'currency',
                label: Lang.get('pages.pumps.data.currency'),
                initialValue: pump.data.currency,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'weight',
                label: Lang.get('pages.pumps.data.weight'),
                initialValue: pump.data.weight,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'rated_power',
                label: Lang.get('pages.pumps.data.rated_power'),
                initialValue: pump.data.rated_power,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'rated_current',
                label: Lang.get('pages.pumps.data.rated_current'),
                initialValue: pump.data.rated_current,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'connection_type',
                label: Lang.get('pages.pumps.data.connection_type'),
                initialValue: pump.data.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'fluid_temp_min',
                label: Lang.get('pages.pumps.data.fluid_temp_min'),
                initialValue: pump.data.fluid_temp_min,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'fluid_temp_max',
                label: Lang.get('pages.pumps.data.fluid_temp_max'),
                initialValue: pump.data.fluid_temp_max,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'ptp_length',
                label: Lang.get('pages.pumps.data.ptp_length'),
                initialValue: pump.data.ptp_length,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'dn_suction',
                label: Lang.get('pages.pumps.data.dn_suction'),
                initialValue: pump.data.dn_suction,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'dn_pressure',
                label: Lang.get('pages.pumps.data.dn_pressure'),
                initialValue: pump.data.dn_pressure,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'category',
                label: Lang.get('pages.pumps.data.category'),
                initialValue: pump.data.category,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'connection',
                label: Lang.get('pages.pumps.data.connection'),
                initialValue: pump.data.connection,
                className: reducedAntFormItemClassName,
            }, input: <Input
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'types',
                label: Lang.get('pages.pumps.data.types'),
                initialValue: pump.data.types,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'applications',
                label: Lang.get('pages.pumps.data.applications'),
                initialValue: pump.data.applications,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                //bordered={false}
                readOnly/>
        },
        {
            values: {
                name: 'description',
                label: Lang.get('pages.pumps.data.description'),
                initialValue: pump.data.description,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                //bordered={false}
                readOnly/>
        },
    ]

    return (
        <IndexContainer
            title={pump.data.full_name}
            extra={<BackLink title={Lang.get('pages.pumps.back')} href={tRoute('pumps.index')}/>}
        >
            <Row gutter={[16, 16]} style={{flex: "auto"}}>
                <FlexCol xs={24} sm={20} md={16} lg={13} xl={8} xxl={8}>
                    <IndexContainer
                        title={Lang.get('pages.pumps.props')}
                        type="inner"
                    >
                        <ItemsForm
                            layout="horizontal"
                            labelSpan={{xs: 8}}
                            items={items}
                        />
                    </IndexContainer>
                </FlexCol>
                <FlexCol xs={24} sm={20} md={16} lg={11} xl={16} xxl={16}>
                    <RoundedCard
                        className={'flex-rounded-card'}
                        type="inner"
                        title={Lang.get('pages.pumps.hydraulic_perf')}
                        style={{width: "100%"}}
                    >
                        <div id="for-graphic"/>
                    </RoundedCard>
                    {/*<IndexContainer*/}
                    {/*    type="inner"*/}
                    {/*    title={Lang.get('pages.pumps.hydraulic_perf')}*/}
                    {/*    className="rounded-card-full-body"*/}
                    {/*>*/}
                    {/*    <div id="for-graphic"></div>*/}
                    {/*</IndexContainer>*/}
                </FlexCol>
            </Row>
        </IndexContainer>
    )
}
