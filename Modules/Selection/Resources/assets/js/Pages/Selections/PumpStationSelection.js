import React, {useEffect, useState} from "react";
import {Inertia} from "@inertiajs/inertia";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {BackLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";
import {
    Checkbox,
    Col, Divider,
    Form, Input,
    Radio,
    Row,
    Space, Tabs,
} from "antd";
import {RequiredFormItem} from "../../../../../../../resources/js/src/Shared/RequiredFormItem";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {SecondaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/SecondaryButton";
import {PrimaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {RoundedCard} from "../../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {SelectedPumpsTable} from "../../Components/SelectedPumpsTable";
import {BoxFlexEnd} from "../../../../../../../resources/js/src/Shared/Box/BoxFlexEnd";
import {usePage} from "@inertiajs/inertia-react";
import {useCheck} from "../../../../../../../resources/js/src/Hooks/check.hook";
import {useHttp} from "../../../../../../../resources/js/src/Hooks/http.hook";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {useDebounce} from "../../../../../../../resources/js/src/Hooks/debounce.hook";
import {InputNum} from "../../../../../../../resources/js/src/Shared/Inputs/InputNum";
import {AddedPumpStationsTable} from "../../Components/AddedPumpStationsTable";
import {PumpPropsDrawer} from "../../../../../../Pump/Resources/assets/js/Components/PumpPropsDrawer";

const BackToProjectLink = ({project_id}) => <BackLink
    title="Назад к проекту"
    href={route('projects.show', project_id)}
/>

const BackToSelectionsDashboardLink = ({project_id}) => <BackLink
    title="Назад к дашборду подборов"
    href={route('selections.dashboard', project_id)}
/>

export const PumpStationSelection = ({title, widths}) => {
    // HOOKS
    const {
        selection,
        project_id,
        selection_props,
        station_types,
        station_type,
        selection_types,
        selection_type,
    } = usePage().props
    const {fullWidth, reducedAntFormItemClassName, margin} = useStyles()
    const {loading, postRequest} = useHttp()
    const {isArrayEmpty} = useCheck()

    // STATE
    const [brandsValue, setBrandsValue] = useState(selection
        ? selection_props.brands_with_series_with_pumps
            .map(brand => brand.id)
            .filter(id => selection.pump_series_ids.includes(id))
        : [])
    const [seriesValue, setSeriesValue] = useState(selection?.pump_series_ids || [])
    const [selectedPumps, setSelectedPumps] = useState([])
    const [addedStations, setAddedStations] = useState(selection?.pump_stations || [])

    const [jockeyBrandsValue, setJockeyBrandsValue] = useState(selection?.jockey_brand_ids || [])
    const [jockeySeriesValue, setJockeySeriesValue] = useState(selection?.jockey_series_ids || [])

    const [brandValue, setBrandValue] = useState(selection?.pump_brand_id || null)
    const [oneSeriesValue, setOneSeriesValue] = useState(selection?.pump_series_id || null)

    const [jockeyBrandValue, setJockeyBrandValue] = useState(selection?.jockey_brand_id || null)
    const [jockeyOneSeriesValue, setJockeyOneSeriesValue] = useState(selection?.jockey_series_id || null)

    // const [pumpValue, setPumpValue] = useState(selection?.pump_id || null)
    const [stationToShow, setStationToShow] = useState(null)
    const [pumpInfo, setPumpInfo] = useState(null)
    const [addedStationKey, setAddedStationKey] = useState(selection != null
        ? Math.max(...selection.pump_stations.map(station => station.key)) + 1
        : 1
    )
    const [flow, setFlow] = useState(selection?.flow || null)
    const debouncedFlow = useDebounce(flow, 1000)

    // const [brandsToShow, setBrandsToShow] = useState(selection_props.brands_with_series_with_pumps)
    const [pumpsToShow, setPumpsToShow] = useState([])
    const [seriesToShow, setSeriesToShow] = useState([])

    const [jockeyPumpsToShow, setJockeyPumpsToShow] = useState([])
    const [jockeySeriesToShow, setJockeySeriesToShow] = useState([])

    const [collectorsToShow, setCollectorsToShow] = useState(selection_props.collectors)

    const [updated, setUpdated] = useState(!selection)
    const [pumpInfoVisible, setPumpInfoVisible] = useState(false)

    // console.log(selection)

    // CONSTS
    const [selectionForm] = Form.useForm()
    const mainPumpsCountCheckboxesOptions = [1, 2, 3, 4, 5].map(value => {
        return {value, label: value}
    })
    const yesNoRadioOptions = <Radio.Group>
        {selection_props.yes_no?.map(value => (
            <Radio key={value.id + value.name} value={value.id}>{value.name}</Radio>
        ))}
    </Radio.Group>
    const labels = {
        flow: "Расход, м³/ч",
        head: "Напор, м",
        deviation: "Запас/допуск, %",
        mainPumpsCount: "Количество рабочих насосов",
        reservePumpsCount: "Количество резервных насосов",
        controlSystemTypes: "Системы управления",
        brands: "Бренды",
        brand: "Бренд",
        theSeries: "Серии",
        series: "Серия",
        pump: "Насос",
        collectors: "Коллекторы",
        collector: "Коллектор",
        select: "Подобрать",
        comment: "Комментарий",
        AF: {
            avr: "АВР",
            gatesValvesCount: "Кол-во задвижек",
            kkv: "ККВ",
            onStreet: "Уличное исполнение"
        }
    }

    // HANDLERS
    const makeSelectionHandler = async () => {
        setStationToShow(null)
        document.getElementById('curves').innerHTML = ''
        if (station_type === station_types.AF)
            document.getElementById('jockey_curves').innerHTML = ''
        if (!selection)
            setAddedStations([])
        try {
            setSelectedPumps((await postRequest(route('selections.select'), {
                ...await selectionForm.validateFields(),
                station_type,
                selection_type,
            }, true)).selected_pumps)
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
        // Inertia.remember(selectionForm.getFieldsValue(true), "selection_form")
    }

    const saveAndCloseHandler = async () => {
        let method = 'post'
        let _route = route('selections.store', project_id)

        if (selection) {
            method = 'put'
            _route = route('selections.update', selection.id)
        }

        Inertia[method](_route, {
            ...await selectionForm.validateFields(),
            station_type,
            selection_type,
            added_stations: addedStations.map(station => ({
                id: station.id || null,
                full_name: station.full_name,
                cost_price: station.cost_price,
                extra_percentage: station.extra_percentage,
                extra_sum: station.extra_sum,
                final_price: station.final_price,
                comment: station.comment,
                main_pumps_count: station.main_pumps_count,
                reserve_pumps_count: station.reserve_pumps_count,

                pump_id: station.pump_id,
                control_system_id: station.control_system_id,
                chassis_id: station.chassis_id,
                input_collector_id: station.input_collector_id,
                output_collector_id: station.output_collector_id,

                jockey_pump_id: station.jockey_pump_id,
                jockey_chassis_id: station.jockey_chassis_id,
                jockey_flow: station.jockey_flow,
                jockey_head: station.jockey_head,
            }))
        })
    }

    useEffect(() => {
        if (stationToShow) {
            try {
                let data = {
                    pump_id: stationToShow.pump_id,
                    head: stationToShow.head,
                    flow: stationToShow.flow,
                    reserve_pumps_count: stationToShow.reserve_pumps_count || 0,
                    main_pumps_count: stationToShow.main_pumps_count || undefined,
                }
                const hasJockey = !!stationToShow.jockey_pump_id
                if (hasJockey) {
                    data = {
                        ...data,
                        jockey_pump_id: stationToShow.jockey_pump_id,
                        jockey_flow: stationToShow.jockey_flow,
                        jockey_head: stationToShow.jockey_head,
                    }
                }
                axios.request({
                    url: route('pump_stations.curves'),
                    method: 'POST',
                    data,
                }).then(res => {
                    document.getElementById('curves').innerHTML = res.data.curves
                    document.getElementById('jockey_curves').innerHTML = hasJockey
                        ? res.data.jockey_curves
                        : ""
                })
            } catch (e) {
                console.error(e)
            }
        }
    }, [stationToShow])

    const setSelectionForm = value => {
        selectionForm.setFieldsValue({
            ...selectionForm,
            ...value
        })
    }

    // HANDLERS
    const showPumpClickHandler = record => e => {
        e.preventDefault()
        postRequest(route('pumps.show', record.pump_id), {
            need_curves: true,
            need_info: true,
        }).then(data => {
            setPumpInfo(data.pump)
        })
    }

    useEffect(() => {
        if (!!debouncedFlow) {
            setCollectorsToShow(selection_props.collectors.map(dnMaterial => {
                return {
                    name: dnMaterial + " v=" + (debouncedFlow / 3600 / (3.14 * ((+dnMaterial.split(" ")[0] * 0.001) ** 2) / 4)).toFixed(2),
                    value: dnMaterial
                }
            }))
        } else {
            setCollectorsToShow(selection_props.collectors)
        }
    }, [debouncedFlow])

    useEffect(() => {
        if (station_type === station_types.AF) {
            if (selection_type === selection_types.Handle) {
                const _jockeySeriesToShow = [].concat(...selection_props.brands_with_series_with_pumps
                    .filter(brand => jockeyBrandValue === brand.id)
                    .map(brand => brand.series))
                setJockeySeriesToShow(_jockeySeriesToShow)
                const index = _jockeySeriesToShow.findIndex(series => jockeyOneSeriesValue === series.id)
                if (index === -1) {
                    setSelectionForm({jockey_series_id: null})
                    setJockeyOneSeriesValue(null)
                }
            } else {
                const _jockeySeriesToShow = [].concat(...selection_props.brands_with_series_with_pumps
                    .filter(brand => jockeyBrandsValue.includes(brand.id))
                    .map(brand => brand.series))
                setJockeySeriesToShow(_jockeySeriesToShow)
                const currentJockeySeries = _jockeySeriesToShow
                    .filter(series => jockeySeriesValue.includes(series.id))
                    .map(series => series.id)
                setSelectionForm({jockey_series_ids: currentJockeySeries})
            }
        }
    }, [jockeyBrandValue, jockeyBrandsValue])

    useEffect(() => {
        if (selection_type === selection_types.Auto) { // AUTO
            const _seriesToShow = [].concat(...selection_props.brands_with_series_with_pumps
                .filter(brand => brandsValue?.includes(brand.id))
                .map(brand => brand.series))
            setSeriesToShow(_seriesToShow)
            const currentSeries = _seriesToShow
                .filter(series => seriesValue.includes(series.id))
                .map(series => series.id)
            setSelectionForm({pump_series_ids: currentSeries})
        } else { // HANDLE
            const _seriesToShow = [].concat(...selection_props.brands_with_series_with_pumps
                .filter(brand => brandValue === brand.id)
                .map(brand => brand.series))
            setSeriesToShow(_seriesToShow)
            const index = _seriesToShow.findIndex(series => oneSeriesValue === series.id)
            if (index === -1) {
                setSelectionForm({pump_series_id: null})
                setOneSeriesValue(null)
            }
        }
        if (!updated) {
            setUpdated(true)
        }
    }, [brandsValue, brandValue])

    useEffect(() => {
        if (selection_type === selection_types.Handle) { // HANDLE ONLY
            if (updated) {
                const _pumpsToShow = [].concat(...seriesToShow
                    .filter(series => oneSeriesValue === series.id)
                    .map(series => series.pumps))
                setPumpsToShow(_pumpsToShow)
                const index = _pumpsToShow.findIndex(pump => selectionForm.getFieldValue('pump_id') === pump.id)
                if (index === -1) {
                    setSelectionForm({pump_id: null})
                    // setPumpValue(null)
                }
            }
        }
    }, [oneSeriesValue, updated])

    useEffect(() => {
        if (station_type === station_types.AF) {
            if (updated) {
                const _jockeyPumpsToShow = [].concat(...jockeySeriesToShow
                    .filter(series => jockeyOneSeriesValue === series.id)
                    .map(series => series.pumps))
                setJockeyPumpsToShow(_jockeyPumpsToShow)
                const index = _jockeyPumpsToShow.findIndex(pump => selectionForm.getFieldValue('jockey_pump_id') === pump.id)
                if (index === -1) {
                    setSelectionForm({jockey_pump_id: null})
                }
            }
        }
    }, [jockeyOneSeriesValue, updated])

    // RENDER
    return (
        <>
            <IndexContainer
                title={title}
                extra={[
                    <BackToProjectLink project_id={project_id}/>,
                    !selection && <BackToSelectionsDashboardLink project_id={project_id}/>
                ].filter(Boolean)}
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
                                <Col xs={widths.flow}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={labels.flow}
                                        name='flow'
                                        initialValue={selection?.flow}
                                    >
                                        <InputNum
                                            placeholder={labels.flow}
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={2}
                                            // disabled={!!selection}
                                            readOnly={!!selection}
                                            onChange={value => {
                                                setFlow(value)
                                            }}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                {/* HEAD */}
                                <Col xs={widths.head}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={labels.head}
                                        name="head"
                                        initialValue={selection?.head}
                                    >
                                        <InputNum
                                            placeholder={labels.head}
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={2}
                                            // disabled={!!selection}
                                            readOnly={!!selection}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                {/* DEVIATION */}
                                {selection_type === selection_types.Auto && <Col xs={widths.deviation}>
                                    <Form.Item
                                        className={reducedAntFormItemClassName}
                                        label={labels.deviation}
                                        name="deviation"
                                        initialValue={selection?.deviation}
                                    >
                                        <InputNum
                                            placeholder={labels.deviation}
                                            style={fullWidth}
                                            min={-50}
                                            max={50}
                                            precision={2}
                                        />
                                    </Form.Item>
                                </Col>}
                                {/* MAIN PUMPS COUNT */}
                                <Col xs={widths.main_pumps_count}>
                                    {selection_type === selection_types.Auto && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={labels.mainPumpsCount}
                                        name="main_pumps_counts"
                                        initialValue={selection?.main_pumps_counts}
                                    >
                                        <Checkbox.Group options={mainPumpsCountCheckboxesOptions}/>
                                    </RequiredFormItem>}
                                    {selection_type === selection_types.Handle && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={labels.mainPumpsCount}
                                        name="main_pumps_count"
                                        initialValue={selection?.main_pumps_count || 1}
                                    >
                                        <Radio.Group value={1}>
                                            {[1, 2, 3, 4, 5].map(value => (
                                                <Radio key={'mp_' + value}
                                                       value={value}>{value}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </RequiredFormItem>}
                                </Col>
                                {/* RESERVE PUMPS COUNT */}
                                <Col xs={widths.reserve_pumps_count}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="reserve_pumps_count"
                                        label={labels.reservePumpsCount}
                                        initialValue={selection?.reserve_pumps_count || 0}
                                    >
                                        <Radio.Group value={0}>
                                            {[0, 1, 2, 3, 4].map(value => (
                                                <Radio key={'rp_' + value}
                                                       value={value}>{value}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </RequiredFormItem>
                                </Col>
                                {/* AF OPTIONS */}
                                {station_type === station_types.AF && <>
                                    {/* AVR */}
                                    <Col xs={widths.avr}>
                                        <RequiredFormItem
                                            className={reducedAntFormItemClassName}
                                            name="avr"
                                            label={labels.AF.avr}
                                            initialValue={Number(selection?.avr) || 1}
                                        >
                                            {yesNoRadioOptions}
                                        </RequiredFormItem>
                                    </Col>
                                    {/* GATES VALVES COUNT */}
                                    <Col xs={widths.gate_valves_count}>
                                        <RequiredFormItem
                                            className={reducedAntFormItemClassName}
                                            name="gate_valves_count"
                                            label={labels.AF.gatesValvesCount}
                                            initialValue={selection?.gate_valves_count || 0}
                                        >
                                            <Radio.Group value={0}>
                                                {[0, 1, 2].map(value => (
                                                    <Radio key={'gvc_' + value}
                                                           value={value}>{value}</Radio>
                                                ))}
                                            </Radio.Group>
                                        </RequiredFormItem>
                                    </Col>
                                    {/* KKV */}
                                    <Col xs={widths.kkv}>
                                        <RequiredFormItem
                                            className={reducedAntFormItemClassName}
                                            name="kkv"
                                            label={labels.AF.kkv}
                                            initialValue={Number(selection?.kkv) || 1}
                                        >
                                            {yesNoRadioOptions}
                                        </RequiredFormItem>
                                    </Col>
                                    {/* ON STREET */}
                                    <Col xs={widths.on_street}>
                                        <RequiredFormItem
                                            className={reducedAntFormItemClassName}
                                            name="on_street"
                                            label={labels.AF.onStreet}
                                            initialValue={Number(selection?.on_street) || 0}
                                        >
                                            {yesNoRadioOptions}
                                        </RequiredFormItem>
                                    </Col>
                                </>}
                                {/* CONTROL SYSTEMS */}
                                <Col xs={widths.control_systems}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="control_system_type_ids"
                                        initialValue={selection?.control_system_type_ids}
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
                                <Col xs={widths.pump_brands}>
                                    {selection_type === selection_types.Auto && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_brand_ids"
                                        initialValue={brandsValue}
                                        label={labels.brands}
                                    >
                                        <MultipleSelection
                                            placeholder={labels.brands}
                                            style={fullWidth}
                                            options={selection_props.brands_with_series_with_pumps}
                                            onChange={values => {
                                                setBrandsValue(values)
                                            }}
                                        />
                                    </RequiredFormItem>}
                                    {selection_type === selection_types.Handle && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_brand_id"
                                        initialValue={brandValue}
                                        label={labels.brand}
                                    >
                                        <Selection
                                            placeholder={labels.brand}
                                            style={fullWidth}
                                            options={selection_props.brands_with_series_with_pumps}
                                            onChange={value => {
                                                setBrandValue(value)
                                            }}
                                        />
                                    </RequiredFormItem>}
                                </Col>
                                {/* PUMP SERIES */}
                                <Col xs={widths.pump_series}>
                                    {selection_type === selection_types.Auto && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_series_ids"
                                        initialValue={seriesValue}
                                        label={labels.theSeries}
                                    >
                                        <MultipleSelection
                                            disabled={brandsValue.length === 0}
                                            placeholder={labels.theSeries}
                                            style={fullWidth}
                                            options={seriesToShow}
                                            onChange={values => {
                                                setSeriesValue(values)
                                            }}
                                        />
                                    </RequiredFormItem>}
                                    {selection_type === selection_types.Handle && <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_series_id"
                                        initialValue={oneSeriesValue}
                                        label={labels.series}
                                    >
                                        <Selection
                                            disabled={!brandValue}
                                            placeholder={labels.series}
                                            style={fullWidth}
                                            options={seriesToShow}
                                            onChange={value => {
                                                setOneSeriesValue(value)
                                            }}
                                        />
                                    </RequiredFormItem>}
                                </Col>
                                {/* PUMP */}
                                {selection_type === selection_types.Handle && <Col xs={widths.pump}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_id"
                                        initialValue={selection?.pump_id}
                                        label={labels.pump}
                                    >
                                        <Selection
                                            disabled={!oneSeriesValue || !brandValue}
                                            placeholder={labels.pump}
                                            style={fullWidth}
                                            options={pumpsToShow}
                                            // onChange={value => {
                                            //     setPumpValue(value)
                                            // }}
                                        />
                                    </RequiredFormItem>
                                </Col>}
                                {/* COLLECTORS */}
                                <Col xs={widths.collectors}>
                                    {selection_type === selection_types.Auto &&
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="collectors"
                                        initialValue={selection?.collectors}
                                        label={labels.collectors}
                                    >
                                        <MultipleSelection
                                            placeholder={labels.collectors}
                                            style={fullWidth}
                                            options={collectorsToShow}
                                        />
                                    </RequiredFormItem>}
                                    {selection_type === selection_types.Handle &&
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="collector"
                                        initialValue={selection?.collector}
                                        label={labels.collector}
                                    >
                                        <Selection
                                            placeholder={labels.collector}
                                            style={fullWidth}
                                            options={collectorsToShow}
                                        />
                                    </RequiredFormItem>}
                                </Col>
                                {/* JOCKEY PUMP */}
                                {station_type === station_types.AF && <>
                                    <Col span={24}>
                                        <Divider orientation="left" style={{marginTop: -10, marginBottom: -10}}>
                                            Жокей насос
                                        </Divider>
                                    </Col>
                                    <Col xs={widths.jockey.flow}>
                                        <Form.Item
                                            className={reducedAntFormItemClassName}
                                            label={labels.flow}
                                            name='jockey_flow'
                                            initialValue={selection?.jockey_flow}
                                        >
                                            <InputNum
                                                placeholder={labels.flow}
                                                style={fullWidth}
                                                min={0}
                                                max={10000}
                                                precision={2}
                                            />
                                        </Form.Item>
                                    </Col>
                                    <Col xs={widths.jockey.head}>
                                        <Form.Item
                                            className={reducedAntFormItemClassName}
                                            label={labels.head}
                                            name='jockey_head'
                                            initialValue={selection?.jockey_head}
                                        >
                                            <InputNum
                                                placeholder={labels.head}
                                                style={fullWidth}
                                                min={0}
                                                max={10000}
                                                precision={2}
                                            />
                                        </Form.Item>
                                    </Col>
                                    <Col xs={widths.jockey.brand}>
                                        {selection_type === selection_types.Auto && <Form.Item
                                            className={reducedAntFormItemClassName}
                                            name="jockey_brand_ids"
                                            initialValue={jockeyBrandsValue}
                                            label={labels.brand}
                                        >
                                            <MultipleSelection
                                                placeholder={labels.brands}
                                                style={fullWidth}
                                                options={selection_props.brands_with_series_with_pumps}
                                                onChange={values => {
                                                    setJockeyBrandsValue(values)
                                                }}
                                            />
                                        </Form.Item>}
                                        {selection_type === selection_types.Handle && <Form.Item
                                            className={reducedAntFormItemClassName}
                                            name="jockey_brand_id"
                                            initialValue={jockeyBrandValue}
                                            label={labels.brand}
                                        >
                                            <Selection
                                                placeholder={labels.brands}
                                                style={fullWidth}
                                                options={selection_props.brands_with_series_with_pumps}
                                                onChange={value => {
                                                    setJockeyBrandValue(value)
                                                }}
                                            />
                                        </Form.Item>}
                                    </Col>
                                    <Col xs={widths.jockey.series}>
                                        {selection_type === selection_types.Auto && <Form.Item
                                            className={reducedAntFormItemClassName}
                                            name="jockey_series_ids"
                                            initialValue={jockeySeriesValue}
                                            label={labels.theSeries}
                                        >
                                            <MultipleSelection
                                                placeholder={labels.theSeries}
                                                style={fullWidth}
                                                options={jockeySeriesToShow}
                                                onChange={values => {
                                                    setJockeySeriesValue(values)
                                                }}
                                                disabled={jockeyBrandsValue.length === 0}
                                            />
                                        </Form.Item>}
                                        {selection_type === selection_types.Handle && <Form.Item
                                            className={reducedAntFormItemClassName}
                                            name="jockey_series_id"
                                            initialValue={jockeyOneSeriesValue}
                                            label={labels.series}
                                        >
                                            <Selection
                                                placeholder={labels.series}
                                                style={fullWidth}
                                                options={jockeySeriesToShow}
                                                onChange={value => {
                                                    setJockeyOneSeriesValue(value)
                                                }}
                                                disabled={!jockeyBrandValue}
                                            />
                                        </Form.Item>}
                                    </Col>
                                    {selection_type === selection_types.Handle && <Col xs={widths.jockey.pump}>
                                        <Form.Item
                                            className={reducedAntFormItemClassName}
                                            name="jockey_pump_id"
                                            initialValue={selection?.jockey_pump_id}
                                            label={labels.pump}
                                        >
                                            <Selection
                                                placeholder={labels.pump}
                                                style={fullWidth}
                                                options={jockeyPumpsToShow}
                                                disabled={!jockeyBrandValue || !jockeyOneSeriesValue}
                                            />
                                        </Form.Item>
                                    </Col>}
                                </>}
                                {/* SELECT BUTTON */}
                                <Col xs={widths.button}>
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
                                            clickPumpArticleHandler={showPumpClickHandler}
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
                        >
                            {station_type === station_types.WS
                                ? <div id="curves"/>
                                : <Tabs defaultActiveKey="curves">
                                    <Tabs.TabPane
                                        forceRender={true}
                                        key="curves"
                                        tab="Основные ГХ"
                                    >
                                        <div id="curves"/>
                                    </Tabs.TabPane>
                                    <Tabs.TabPane
                                        forceRender={true}
                                        key="jockey-curves"
                                        tab="ГХ жокея"
                                    >
                                        <div id="jockey_curves"/>
                                    </Tabs.TabPane>
                                </Tabs>}

                        </RoundedCard>
                    </Col>
                    <Col xs={24}>
                        <RoundedCard
                            className="table-rounded-card"
                            type="inner"
                            title="Добавленные станции"
                        >
                            <AddedPumpStationsTable
                                addedStations={addedStations}
                                loading={loading}
                                setStationToShow={setStationToShow}
                                setAddedStations={setAddedStations}
                                selectionType={selection_type}
                                stationType={station_type}
                            />
                        </RoundedCard>
                    </Col>
                    <Col xs={24}>
                        <Form form={selectionForm} layout="vertical">
                            <Form.Item
                                name='comment'
                                label={labels.comment}
                                className={reducedAntFormItemClassName}
                                initialValue={selection?.comment}
                            >
                                <Input.TextArea autoSize placeholder={labels.comment}/>
                            </Form.Item>
                        </Form>
                    </Col>
                    <Col xs={24}>
                        <BoxFlexEnd>
                            <Space size={8}>
                                <SecondaryButton
                                    onClick={() => {
                                        Inertia.get(route('projects.show', project_id))
                                    }}
                                    loading={loading}
                                >
                                    Выйти без сохранения
                                </SecondaryButton>
                                <PrimaryButton
                                    onClick={saveAndCloseHandler}
                                    loading={loading}
                                    disabled={isArrayEmpty(addedStations)}
                                >
                                    Сохранить и выйти
                                </PrimaryButton>
                            </Space>
                        </BoxFlexEnd>
                    </Col>
                </Row>
            </IndexContainer>
            <PumpPropsDrawer
                needCurve
                pumpInfo={pumpInfo}
                visible={pumpInfoVisible}
                setVisible={setPumpInfoVisible}
            />
        </>
    )
}
