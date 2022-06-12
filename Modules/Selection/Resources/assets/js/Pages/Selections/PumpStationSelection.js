import React, {useEffect, useState} from "react";
import {Inertia} from "@inertiajs/inertia";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {BackLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";
import {
    Checkbox,
    Col,
    Form, Input,
    Radio,
    Row,
    Space,
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
import {InputNum} from "../../../../../../../resources/js/src/Shared/Inputs/InputNum";
import {AddedPumpsTable} from "../../Components/AddedPumpsTable";

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
    const [brandsValue, setBrandsValue] = useState(selection?.pump_brand_ids || [])
    const [seriesValue, setSeriesValue] = useState(selection?.pump_series_ids || [])
    const [selectedPumps, setSelectedPumps] = useState([])
    const [addedStations, setAddedStations] = useState(selection?.pump_stations || [])

    const [brandValue, setBrandValue] = useState(selection?.pump_brand_id || null)
    const [oneSeriesValue, setOneSeriesValue] = useState(selection?.pump_series_id || null)
    const [pumpValue, setPumpValue] = useState(selection?.pump_id || null)
    const [stationToShow, setStationToShow] = useState(null)
    const [addedStationKey, setAddedStationKey] = useState(selection != null
        ? Math.max(...selection.pump_stations.map(station => station.key)) + 1
        : 1
    )

    const [brandsToShow, setBrandsToShow] = useState(selection_props.brands_with_series_with_pumps)
    const [pumpsToShow, setPumpsToShow] = useState([])
    const [seriesToShow, setSeriesToShow] = useState([])

    const [updated, setUpdated] = useState(!selection)
    const [exportDrawerVisible, setExportDrawerVisible] = useState(false)

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
        if (!selection)
            setAddedStations([])
        try {
            // console.log({
            //     ...await selectionForm.validateFields(),
            //     station_type,
            //     selection_type,
            // })
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
            }))
        })
    }

    useEffect(() => {
        if (stationToShow) {
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

    useEffect(() => {
        console.log('pumps', pumpsToShow, 'pump_value', pumpValue)
    }, [pumpsToShow])

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
            // setSeriesValue(currentSeries)
        } else { // HANDLE
            console.log('brand', brandValue)
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
        if (updated)
            if (selection_type === selection_types.Handle) { // HANDLE ONLY
                console.log('series', oneSeriesValue)
                const _pumpsToShow = [].concat(...seriesToShow
                    .filter(series => oneSeriesValue === series.id)
                    .map(series => series.pumps))
                setPumpsToShow(_pumpsToShow)
                const index = _pumpsToShow.findIndex(pump => pumpValue === pump.id)
                if (index === -1) {
                    setSelectionForm({pump_id: null})
                    setPumpValue(null)
                }
            }
    }, [oneSeriesValue, updated])

    // RENDER
    return (
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
                                        disabled={!!selection}
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
                                        disabled={!!selection}
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
                                        initialValue={selection?.avr || 1}
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
                                        initialValue={selection?.kkv || 1}
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
                                        initialValue={selection?.on_street || 0}
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
                                        options={brandsToShow}
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
                                        options={brandsToShow}
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
                                    initialValue={pumpValue}
                                    label={labels.pump}
                                >
                                    <Selection
                                        disabled={!oneSeriesValue || !brandValue}
                                        placeholder={labels.pump}
                                        style={fullWidth}
                                        options={pumpsToShow}
                                        onChange={value => {
                                            setPumpValue(value)
                                        }}
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
                                        options={selection_props.collectors}
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
                                        options={selection_props.collectors}
                                    />
                                </RequiredFormItem>}
                            </Col>
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
    )
}
