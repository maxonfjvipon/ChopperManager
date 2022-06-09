import React, {useEffect, useState} from 'react'
import {BackLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";
import {usePage, useRemember} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {Checkbox, Col, Form, Input, Radio, Row, Space, Tabs} from "antd";
import {RequiredFormItem} from "../../../../../../../resources/js/src/Shared/RequiredFormItem";
import {InputNum} from "../../../../../../../resources/js/src/Shared/Inputs/InputNum";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {PrimaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useHttp} from "../../../../../../../resources/js/src/Hooks/http.hook";
import {SelectedPumpsTable} from "../../Components/SelectedPumpsTable";
import {RoundedCard} from "../../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {AddedPumpsTable} from "../../Components/AddedPumpsTable";
import {Inertia} from "@inertiajs/inertia";

const BackToProjectLink = ({project_id}) => <BackLink
    title="Назад к проекту"
    href={route('projects.show', project_id)}
/>

const BackToSelectionsDashboardLink = ({project_id}) => <BackLink
    title="Назад к дашборду подборов"
    href={route('selections.dashboard', project_id)}
/>

export default function SingleAuto({title = "Hello"}) {
    const rememberedForm = Inertia.restore('selection_form')

    // HOOKS
    const {selection, project_id, selection_props} = usePage().props
    const {fullWidth, reducedAntFormItemClassName, margin} = useStyles()
    const {loading, postRequest} = useHttp()

    // STATE
    const [brandsToShow, setBrandsToShow] = useState(selection_props.brands_with_series_with_pumps)
    const [seriesToShow, setSeriesToShow] = useState([])
    const [pumpsToShow, setPumpsToShow] = useState([])

    const [brandsValue, setBrandsValue] = useState(rememberedForm?.pump_brand_ids || [])
    const [seriesValue, setSeriesValue] = useState(rememberedForm?.pump_series_ids || [])
    const [selectedPumps, setSelectedPumps] = useState([])
    const [addedStations, setAddedStations] = useState(Inertia.restore('added_stations'))

    const [stationToShow, setStationToShow] = useState(null)
    const [chosenSelectedPumps, setChosenSelectedPumps] = useState({})
    const [addedStationKey, setAddedStationKey] = useState(1)

    const [exportDrawerVisible, setExportDrawerVisible] = useState(false)

    // CONSTS
    const [selectionForm] = Form.useForm()
    const mainPumpsCountCheckboxesOptions = [1, 2, 3, 4, 5].map(value => {
        return {value, label: value}
    })
    const labels = {
        flow: "Расход, м³/ч",
        head: "Напор, м",
        deviation: "Запас/допуск, %",
        mainPumpsCounts: "Количество рабочих насосов",
        reservePumpsCount: "Количество резервных насосов",
        controlSystemTypes: "Системы управления",
        brands: "Бренды",
        series: "Серии",
        collectors: "Коллекторы",
        select: "Подобрать",
        comment: "Комментарий",
    }

    const showPumpInfo = async event => {
        // event.preventDefault()
        // if (chosenSelectedPumps[stationToShow.key]?.pump_info === undefined) {
        //     const data = await postRequest(tRoute('pumps.show', stationToShow.pump_id), {
        //         pumpable_type: pumpableType(),
        //         need_curves: false,
        //     })
        //     setPumpInfo(data)
        //     chosenSelectedPumps[stationToShow.key] = {
        //         ...chosenSelectedPumps[stationToShow.key],
        //         pump_info: data
        //     }
        // } else {
        //     if (pumpInfo.id === stationToShow.pump_id) {
        //         setPumpInfoVisible(true)
        //     } else {
        //         setPumpInfo(chosenSelectedPumps[stationToShow.key].pump_info)
        //     }
        // }
    }

    // HANDLERS
    const makeSelectionHandler = async () => {
        setStationToShow(null)
        if (!selection)
            setAddedStations([])
        const body = {
            ...await selectionForm.validateFields(),
            station_type: 'Water', // fixme
            selection_type: 'Auto',
        }
        try {
            const selected_pumps = (await postRequest(route('selections.select'), body, true)).selected_pumps
            setSelectedPumps(selected_pumps)
        } catch (e) {
            console.log(e)
        }
    }

    const addStationHandler = record => () => {
        let stations = addedStations
        stations.push({
            ...record,
            key: addedStationKey,
            extra_percentage: 0,
            extra_sum: 0,
            final_price: record.cost_price,
            comment: "",
        })
        setAddedStations([...stations])
        setAddedStationKey(addedStationKey + 1)
        Inertia.remember(selectionForm.getFieldsValue(true), "selection_form")
    }

    // EFFECTS
    useEffect(() => {
        Inertia.remember(addedStations, 'added_stations')
    }, [addedStations])

    useEffect(() => {
        if (stationToShow) {
            if (chosenSelectedPumps[stationToShow.num]?.curves === undefined) {
                try {
                    axios.request({
                        url: route('pump_stations.curves'),
                        method: 'POST',
                        data: {
                            pump_id: stationToShow.pump_id,
                            head: stationToShow.head,
                            flow: stationToShow.flow,
                            reserve_pumps_count: stationToShow.reserve_pumps_count || 0,
                            main_pumps_count: stationToShow.main_pumps_count || undefined,
                        },
                    }).then(res => {
                        document.getElementById('curves').innerHTML = res.data.curves
                        chosenSelectedPumps[stationToShow.num] = {
                            ...chosenSelectedPumps[stationToShow.num],
                            curves: res.data.curves,
                        }
                        setChosenSelectedPumps(chosenSelectedPumps)
                    })
                } catch (e) {
                }
            } else {
                document.getElementById('curves').innerHTML = chosenSelectedPumps[stationToShow.num].curves
            }
        } else {
            setChosenSelectedPumps({})
        }
    }, [stationToShow])

    useEffect(() => {
        if (brandsValue) {
            setSeriesToShow([].concat(...selection_props.brands_with_series_with_pumps
                .filter(brand => brandsValue.includes(brand.id))
                .map(brand => brand.series))
            )
        }
    }, [brandsValue])

    // RENDER
    return (
        <IndexContainer
            title={selection
                ? selection.selected_pump_name
                : title}
            extra={[
                <BackToProjectLink project_id={project_id}/>,
                <BackToSelectionsDashboardLink project_id={project_id}/>
            ]}
        >
            <Row gutter={[16, 16]}>
                <Col xs={16}>
                    <Form
                        form={selectionForm}
                        name="selection-form"
                        layout="vertical"
                    >
                        <Row gutter={[16, 16]}>
                            {/* FLOW */}
                            <Col xs={4}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    label={labels.flow}
                                    name='flow'
                                    initialValue={selection?.flow || rememberedForm?.flow}
                                >
                                    <InputNum
                                        placeholder={labels.flow}
                                        style={fullWidth}
                                        min={0}
                                        max={10000}
                                        precision={2}
                                        disabled={!!selection}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* HEAD */}
                            <Col xs={4}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    label={labels.head}
                                    name="head"
                                    initialValue={selection?.head || rememberedForm?.head}
                                >
                                    <InputNum
                                        placeholder={labels.head}
                                        style={fullWidth}
                                        min={0}
                                        max={10000}
                                        precision={2}
                                        disabled={!!selection}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* DEVIATION */}
                            <Col xs={4}>
                                <Form.Item
                                    className={reducedAntFormItemClassName}
                                    label={labels.deviation}
                                    name="deviation"
                                    initialValue={selection?.deviation || rememberedForm?.deviation}
                                >
                                    <InputNum
                                        placeholder={labels.deviation}
                                        style={fullWidth}
                                        min={-50}
                                        max={50}
                                        precision={2}
                                    />
                                </Form.Item>
                            </Col>
                            {/* MAIN PUMPS COUNT */}
                            <Col xs={6}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    label={labels.mainPumpsCounts}
                                    name="main_pumps_counts"
                                    initialValue={selection?.main_pumps_counts || rememberedForm?.main_pumps_counts}
                                >
                                    <Checkbox.Group options={mainPumpsCountCheckboxesOptions}/>
                                </RequiredFormItem>
                            </Col>
                            {/* RESERVE PUMPS COUNT */}
                            <Col xs={6}>
                                <Form.Item
                                    className={reducedAntFormItemClassName}
                                    required
                                    name="reserve_pumps_count"
                                    label={labels.reservePumpsCount}
                                    initialValue={selection?.reserve_pumps_count || rememberedForm?.reserve_pumps_count || 0}
                                >
                                    <Radio.Group value={0}>
                                        {[0, 1, 2, 3, 4].map(value => (
                                            <Radio key={'rp_' + value}
                                                   value={value}>{value}</Radio>
                                        ))}
                                    </Radio.Group>
                                </Form.Item>
                            </Col>
                            {/* CONTROL SYSTEMS */}
                            <Col xs={5}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    name="control_system_type_ids"
                                    initialValue={selection?.control_system_type_ids || rememberedForm?.control_system_type_ids}
                                    label={labels.controlSystemTypes}
                                >
                                    <MultipleSelection
                                        placeholder={labels.controlSystemTypes}
                                        style={fullWidth}
                                        options={selection_props.control_system_types}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* PUMP BRANDS */}
                            <Col xs={5}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    name="pump_brand_ids"
                                    initialValue={brandsValue}
                                    label={labels.brands}
                                >
                                    <MultipleSelection
                                        placeholder={labels.brands}
                                        style={fullWidth}
                                        options={brandsToShow}
                                        onChange={values => {
                                            setBrandsValue(values)
                                        }}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* PUMP SERIES */}
                            <Col xs={6}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    name="pump_series_ids"
                                    initialValue={seriesValue}
                                    label={labels.series}
                                >
                                    <MultipleSelection
                                        disabled={brandsValue.length === 0}
                                        placeholder={labels.series}
                                        style={fullWidth}
                                        options={seriesToShow}
                                        onChange={values => {
                                            setSeriesValue(values)
                                        }}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* COLLECTORS */}
                            <Col xs={5}>
                                <RequiredFormItem
                                    className={reducedAntFormItemClassName}
                                    name="collectors"
                                    initialValue={selection?.collectors || rememberedForm?.collectors}
                                    label={labels.collectors}
                                >
                                    <MultipleSelection
                                        placeholder={labels.collectors}
                                        style={fullWidth}
                                        options={selection_props.collectors}
                                    />
                                </RequiredFormItem>
                            </Col>
                            {/* SELECT BUTTON */}
                            <Col xs={3}>
                                <Form.Item className={reducedAntFormItemClassName}>
                                    <PrimaryButton
                                        style={{...fullWidth, ...margin.top(20)}}
                                        onClick={makeSelectionHandler}
                                        loading={loading}
                                    >
                                        {labels.select}
                                    </PrimaryButton>
                                </Form.Item>
                            </Col>
                            <Col xs={24}>
                                <RoundedCard
                                    className="table-rounded-card"
                                    type="inner"
                                    title="Подобранные станции"
                                >
                                    <SelectedPumpsTable
                                        selectedPumps={selectedPumps}
                                        setStationToShow={setStationToShow}
                                        loading={loading}
                                        addStationHandler={addStationHandler}
                                        dependencies={[addedStations, addedStationKey]}
                                    />
                                </RoundedCard>
                            </Col>
                        </Row>
                    </Form>
                </Col>
                <Col xs={8}>
                    <RoundedCard
                        className='flex-rounded-card'
                        type="inner"
                        title={stationToShow?.name}
                        // extra={stationToShow && <Space>
                        //     <a onClick={e => {
                        //         e.preventDefault()
                        //         setExportDrawerVisible(true)
                        //     }}>{Lang.get('pages.selections.single_pump.graphic.export')}</a>}
                        //     <a onClick={showPumpInfo}>
                        //         {Lang.get('pages.selections.single_pump.graphic.info')}>>
                        //     </a>
                        // </Space>}
                    >
                        <div id="curves"/>
                    </RoundedCard>
                </Col>
                <Col xs={24}>
                    <RoundedCard
                        className="table-rounded-card"
                        type="inner"
                        title="Добавленные станции"
                    >
                        <AddedPumpsTable
                            addedStations={addedStations}
                            loading={loading}
                            setStationToShow={setStationToShow}
                            setAddedStations={setAddedStations}
                        />
                    </RoundedCard>
                </Col>
                <Col xs={24}>
                    <Form form={selectionForm} layout="vertical">
                        <Form.Item
                            name='comment'
                            label={labels.comment}
                            className={reducedAntFormItemClassName}
                            initialValue={selection?.comment || rememberedForm?.comment}
                        >
                            <Input.TextArea autoSize placeholder={labels.comment}/>
                        </Form.Item>
                    </Form>
                </Col>
            </Row>
        </IndexContainer>
    )
}
