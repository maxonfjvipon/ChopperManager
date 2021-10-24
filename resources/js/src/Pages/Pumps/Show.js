import React, {useEffect} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {ItemsForm} from "../../Shared/ItemsForm";
import {Col, Input, Row} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {
    VictoryChart,
    VictoryLine,
    VictoryScatter,
    VictoryVoronoiContainer,
    VictoryTooltip,
    VictoryAxis, VictoryTheme
} from 'victory'
import Lang from '../../../translation/lang'
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {RoundedCard} from "../../Shared/Cards/RoundedCard";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {FlexCol} from "../../Shared/FlexCol";
import {IndexContainer} from "../../Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {SecondaryAction} from "../../Shared/Resource/Actions/SecondaryAction";
import {BackLink} from "../../Shared/Resource/BackLinks/BackLink";

const Show = () => {
    const {pump} = usePage().props
    const {reducedAntFormItemClassName} = useStyles()
    const {tRoute} = useTransRoutes()

    // useEffect(() => {
    //     console.log(pump)
    // }, [pump])

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
    ]

    const width = 1000

    return (
        <IndexContainer
            title={pump.data.full_name}
            back={<BackLink title={Lang.get('pages.pumps.back')} href={tRoute('pumps.index')}/>}
        >
            <Row gutter={[16, 16]} style={{flex: "auto"}}>
                <FlexCol xs={24} sm={20} md={16} lg={13} xl={10} xxl={7}>
                    <IndexContainer
                        title={Lang.get('pages.pumps.props')}
                        type="inner"
                    >
                        <ItemsForm
                            layout="horizontal"
                            labelSpan={{xs: 9}}
                            items={items}
                        />
                    </IndexContainer>
                </FlexCol>
                <FlexCol xs={24} sm={20} md={16} lg={11} xl={14} xxl={17}>
                    <IndexContainer
                        type="inner"
                        title={Lang.get('pages.pumps.hydraulic_perf')}
                        className="rounded-card-full-body"
                    >
                        <VictoryChart
                            width={1000}
                            height={500}
                            theme={VictoryTheme.material}
                            domain={{y: [0, pump.data.performance.y_max]}}
                            containerComponent={<VictoryVoronoiContainer/>}
                        >
                            <VictoryAxis
                                style={{
                                    tickLabels: {padding: 1},
                                    axisLabel: {padding: 20}
                                }}
                                orientation="bottom"
                                label={Lang.get('graphic.axis.flow')}
                            />
                            <VictoryAxis
                                style={{
                                    tickLabels: {padding: 1},
                                    axisLabel: {padding: 20}
                                }}
                                dependentAxis
                                label={Lang.get('graphic.axis.head')}
                            />
                            {/*<VictoryAxis*/}
                            {/*    orientation="bottom"*/}
                            {/*    label={Lang.get('pages.pumps.consumption')}*/}
                            {/*/>*/}
                            {/*<VictoryAxis dependentAxis label={Lang.get('pages.pumps.pressure')}*/}
                            {/*/>*/}
                            <VictoryLine
                                interpolation="linear" data={pump.data.performance.line_data}
                                style={{data: {stroke: "blue"}}}
                            />
                            <VictoryScatter
                                data={pump.data.performance.line_data}
                                size={5}
                                style={{
                                    data: {fill: "blue"},
                                    labels: {fill: "blue"}
                                }}
                                labels={({datum}) => {
                                    return "Q: " + datum.x + ", H: " + datum.y
                                }}
                                labelComponent={<VictoryTooltip/>}
                            />
                        </VictoryChart>
                    </IndexContainer>
                </FlexCol>
            </Row>
        </IndexContainer>
    )
}

Show.layout = page => <AuthLayout children={page}/>

export default Show
