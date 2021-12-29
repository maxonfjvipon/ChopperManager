import React, {useEffect, useState} from "react";
import {Inertia} from "@inertiajs/inertia";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import Lang from "../../../../../../resources/js/translation/lang";
import {BackLink} from "../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";
import {Checkbox, Col, Divider, Form, InputNumber, message, notification, Radio, Row, Slider, Space, Tree} from "antd";
import {RequiredFormItem} from "../../../../../../resources/js/src/Shared/RequiredFormItem";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {SecondaryButton} from "../../../../../../resources/js/src/Shared/Buttons/SecondaryButton";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {SelectedPumpsTable} from "../Components/SelectedPumpsTable";
import {BoxFlexEnd} from "../../../../../../resources/js/src/Shared/Box/BoxFlexEnd";
import {FiltersDrawer} from "../Components/FiltersDrawer";
import {ExportAtOnceSelectionDrawer} from "../Components/ExportAtOnceSelectionDrawer";
import {usePage} from "@inertiajs/inertia-react";
import {useCheck} from "../../../../../../resources/js/src/Hooks/check.hook";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {useDebounce} from "../../../../../../resources/js/src/Hooks/debounce.hook";
import {useForm} from "antd/es/form/Form";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {usePumpableType} from "../../../../../../resources/js/src/Hooks/pumpable_type.hook";
import {usePumpInfo} from "../../../../../../resources/js/src/Hooks/pump_info.hook";
import {PumpPropsDrawer} from "../../../../../Pump/Resources/assets/js/Components/PumpPropsDrawer";

export const Selection = ({pageTitle, widths}) => {
    // HOOKS
    const {selection, project_id, selection_props} = usePage().props

    const {isArrayEmpty} = useCheck()
    const {postRequest, loading} = useHttp()
    const {has} = usePermissions()
    const tRoute = useTransRoutes()
    const [fullSelectionForm] = useForm()
    const [additionalFiltersForm] = useForm()
    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName, color} = useStyles()
    const pumpableType = usePumpableType()

    // console.log(selection_props)

    // STATE
    const [showBrandsList, setShowBrandsList] = useState(false)
    const [addLoading, setAddLoading] = useState(false)
    // const [checkedAll, setCheckedAll] = useState(false)
    // const [indeterminate, setIndeteminate] = useState(true)
    const [hideIcons, setHideIcons] = useState(false)
    const [pumpInfoVisible, setPumpInfoVisible] = useState(false)
    const [prevHideIcons, setPrevHideIcons] = useState(hideIcons)
    const [updated, setUpdated] = useState(!selection)
    const [useAdditionalFilters, setUseAdditionalFilters] = useState(selection?.data.use_additional_filters || false)
    const [rangeDisabled, setRangeDisabled] = useState(selection
        ? selection?.data.range_id !== selection_props.selectionRanges[selection_props.selectionRanges.length - 1].id
        : false
    )
    const [filtersDrawerVisible, setFiltersDrawerVisible] = useState(false)
    const [exportDrawerVisible, setExportDrawerVisible] = useState(false)
    const [reload, setReload] = useState(!selection)

    const [brandsSeriesList, setBrandsSeriesList] = useState([])
    const [brandsSeriesTree, setBrandsSeriesTree] = useState([])
    const [selectedPumps, setSelectedPumps] = useState([])
    const [brandsValue, setBrandsValue] = useState(
        selection?.data.pump_brands.filter(brandId => selection_props.brandsWithSeries.findIndex(bws => bws.id === brandId) !== -1)
        || selection_props.defaults.brands
    )
    const [brandsSeriesListValues, setBrandsSeriesListValues] = useState(selection?.data.pump_series || [])
    const [powerAdjustmentValue, setPowerAdjustmentValue] = useState(selection?.data.power_adjustments || selection_props.defaults.powerAdjustments)
    const [typesValue, setTypesValue] = useState(selection?.data.pump_types || [])
    const [applicationsValue, setApplicationsValue] = useState(selection?.data.pump_applications || [])

    const [chosenSelectedPumps, setChosenSelectedPumps] = useState({})
    const [pumpInfo, setPumpInfo] = useState(null)
    const filtersDrawerProps = {
        selection,
        selection_props,
        setUseAdditionalFilters,
        form: additionalFiltersForm,
        visible: filtersDrawerVisible,
        setVisible: setFiltersDrawerVisible,
    }
    const [stationToShow, setStationToShow] = useState(null)
    const [temperatureValue, setTemperatureValue] = useState(selection?.data.fluid_temperature || null)

    const [prevTemperatureValue, setPrevTemperatureValue] = useState(-100)
    const debouncedTemperature = useDebounce(temperatureValue, 500)

    // CONSTS
    const mainPumpsCountCheckboxesOptions = [1, 2, 3, 4, 5].map(value => {
        return {value, label: value}
    })
    const fieldsDisabled = (isArrayEmpty(brandsValue))
    const fullSelectionFormName = "full-selection-form"

    // CALLBACKS
    const filteredBrandsWithSeries = () => {
        return selection_props.brandsWithSeries.filter(brand => {
            return brandsValue.indexOf(brand.id) !== -1
        })
    }

    const checkValueIncludesSeriesParams = (value, params) => value.some(_value => !params.map(param => param.id).includes(_value))

    const seriesIcon = (src) => (src == null || src === "")
        ? <></>
        : <img src={selection_props.media_path + src} width={60}/>

    const checkHideIcons = () => prevHideIcons === hideIcons

    const hasTemperature = series => debouncedTemperature == null
        || (series.temps_min.length > 0 && series.temps_min[0] <= debouncedTemperature
            && series.temps_max.length > 0 && series.temps_max[1] >= debouncedTemperature)

    const seriesColorStyle = (hasTemp, series) => color(hasTemp
        ? ((debouncedTemperature >= series.temps_min[1] && debouncedTemperature <= series.temps_max[0])
            ? 'black'
            : 'orange')
        : 'red')


    // HANDLERS
    // TEMPERATURE CHANGE HANDLER
    const temperatureChangeHandler = () => value => {
        setTemperatureValue(value)
    }

    // PRODUCERS SERIES LIST VALUES CHECKED HANDLER
    const brandsSeriesListValuesCheckedHandler = values => {
        values.sort()
        let checked = values.filter(value => !brandsSeriesListValues.includes(value) && typeof value === "number")

        filteredBrandsWithSeries().forEach(brand => {
            brand.series.forEach(series => {
                let index = checked.findIndex(ch => series.id === ch)
                if (index !== -1) {
                    let array = []
                    if (checkValueIncludesSeriesParams(typesValue, series.types)) {
                        array.push(Lang.get('messages.selections.notification.description.types'))
                    }
                    if (selection_props.applications && checkValueIncludesSeriesParams(applicationsValue, series.applications)) {
                        array.push(Lang.get('messages.selections.notification.description.applications'))
                    }
                    if (!powerAdjustmentValue.includes(series.power_adjustment.id)) {
                        array.push(Lang.get('messages.selections.notification.description.power_adjustment'))
                    }
                    if (array.length > 0) {
                        notification.info({
                            message: Lang.get('messages.selections.notification.attention'),
                            description:
                                Lang.get('messages.selections.notification.description.1') + " " +
                                series.name + " " +
                                Lang.get('messages.selections.notification.description.2') + " " +
                                array.join(', '),
                            placement: 'topLeft',
                            duration: 5
                        })
                    }
                }

            })
        })
        setBrandsSeriesListValues(values)
    }

    // SAVE HANDLER
    const addSelectionToProjectClickHandler = (method) => async () => {
        // yaCounter86716585.reachGoal('add-selection-to-project')
        setAddLoading(true)
        setReload(false)
        const body = {
            ...await fullSelectionForm.validateFields(),
            ...await additionalFiltersForm.validateFields(),
            use_additional_filters: useAdditionalFilters,
            pump_id: stationToShow.pump_id,
            pumps_count: stationToShow.pumps_count || undefined,
            selected_pump_name: stationToShow.name,
            pump_series_ids: brandsSeriesListValues,
            pumpable_type: pumpableType(),
            project_id,
        }
        // prepareRequestBody(body)
        Inertia[method](
            method === 'put'
                ? tRoute('selections.update', selection.data.id)
                : tRoute('projects.selections.store', project_id),
            body,
            {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => {
                    setAddLoading(false)
                    setReload(true)
                }
            }
        )
    }

    // MAKE SELECTION HANDLER
    const makeSelectionHandler = async () => {
        // yaCounter86716585.reachGoal('make-select')
        if (isArrayEmpty(brandsSeriesListValues)) {
            message.warning(Lang.get('messages.selections.no_series_selected'))
            return
        }
        setStationToShow(null)
        document.getElementById('for-graphic').innerHTML = ""
        if (!updated) {
            setUpdated(true)
        }
        const body = {
            ...await fullSelectionForm.validateFields(),
            ...await additionalFiltersForm.validateFields(),
            use_additional_filters: useAdditionalFilters,
            series_ids: brandsSeriesListValues.filter(si => brandsSeriesList.findIndex(el => el.value === si) !== -1),
            pumpable_type: pumpableType(),
        }
        try {
            const data = await postRequest(tRoute('selections.select'), body, true)
            setSelectedPumps(data.selected_pumps)
        } catch (e) {
        }
    }

    // USE EFFECTS
    // CHECK PRODUCERS SERIES LIST AND TREE CHANGE
    useEffect(() => {
        const _brandsSeriesList = []
        const _brandsSeriesTree = []
        if (brandsValue.length > 0) {
            filteredBrandsWithSeries().forEach(brand => {
                let children = []
                brand.series.forEach(series => {
                    let hasTemp = hasTemperature(series)
                    const colorStyle = seriesColorStyle(hasTemp, series)
                    _brandsSeriesList.push({
                        label: <>
                            {!hideIcons && seriesIcon(series.image)}
                            <span style={{
                                ...colorStyle,
                                marginLeft: hideIcons ? 0 : 5,
                            }}>{brand.name + " " + series.name}</span>
                        </>,
                        value: series.id,
                        disabled: !hasTemp,
                    })
                    children.push({
                        title: <>
                            {!hideIcons && seriesIcon(series.image)}
                            <span style={{...colorStyle, marginLeft: hideIcons ? 0 : 5}}>{series.name}</span>
                        </>,
                        key: series.id,
                        disabled: !hasTemp,
                    })
                })
                _brandsSeriesTree.push({
                    title: brand.name,
                    key: brand.name,
                    children
                })
            })
        }
        setBrandsSeriesList(_brandsSeriesList)
        setBrandsSeriesTree(_brandsSeriesTree)

    }, [brandsValue, hideIcons])

    // FILTER BRANDS WITH SERIES
    useEffect(() => {
        if (checkHideIcons()) {
            if (!isArrayEmpty(brandsSeriesList)) {
                if (reload) {
                    const _producersSeriesListValues = []
                    const _producersSeriesList = []
                    const _producersSeriesTree = []
                    const temperatureWasChanged = debouncedTemperature !== prevTemperatureValue
                    const hasTypes = typesValue.length <= 0
                    const hasApplications = applicationsValue?.length <= 0 || false
                    const hasPowerAdjustments = powerAdjustmentValue <= 0
                    filteredBrandsWithSeries().forEach(brand => {
                        let children = []
                        brand.series.forEach(series => {
                            const brandsSeries = brand.name + " " + series.name
                            let hasType = hasTypes
                            let hasApplication = hasApplications
                            let hasPowerAdjustment = hasPowerAdjustments
                            let hasTemp = hasTemperature(series)
                            if (!hasType) {
                                if (typesValue.every(typeValue => series.types
                                    .map(type => type.id)
                                    .includes(typeValue))
                                ) {
                                    hasType = true
                                }
                            }
                            if (!hasApplication) {
                                if (applicationsValue.every(applicationValue => series.applications
                                    .map(application => application.id)
                                    .includes(applicationValue))
                                ) {
                                    hasApplication = true
                                }
                            }
                            if (!hasPowerAdjustment) {
                                if (powerAdjustmentValue.includes(series.power_adjustment?.id)) {
                                    hasPowerAdjustment = true
                                }
                            }
                            // checkedBrandsSeriesListValues - checked by user
                            if (hasType && hasPowerAdjustment && hasTemp && hasApplication) {
                                _producersSeriesListValues.push(series.id)
                            }
                            if (temperatureWasChanged) {
                                const colorStyle = seriesColorStyle(hasTemp, series)
                                _producersSeriesList.push({
                                    label: <>
                                        {!hideIcons && seriesIcon(series.image)}
                                        <span style={{...colorStyle, marginLeft: hideIcons ? 0 : 5}}>{brandsSeries}</span>
                                    </>,
                                    value: series.id,
                                    disabled: !hasTemp,
                                })
                                children.push({
                                    title: <>
                                        {!hideIcons && seriesIcon(series.image)}
                                        <span style={{...colorStyle, marginLeft: hideIcons ? 0 : 5}}>{series.name}</span>
                                    </>,
                                    key: series.id,
                                    disabled: !hasTemp
                                })
                            }
                        })
                        if (temperatureWasChanged) {
                            _producersSeriesTree.push({
                                title: brand.name,
                                key: brand.name,
                                children
                            })
                        }
                    })

                    if (temperatureWasChanged) {
                        setBrandsSeriesList(_producersSeriesList)
                        setBrandsSeriesTree(_producersSeriesTree)
                        setPrevTemperatureValue(debouncedTemperature)
                    }

                    // what checkboxes are checked
                    setBrandsSeriesListValues(_producersSeriesListValues)
                } else {
                    setReload(true)
                }
            }
        } else {
            setPrevHideIcons(hideIcons)
        }
    }, [typesValue, applicationsValue, powerAdjustmentValue, debouncedTemperature, brandsSeriesList])

    // CHECK CHANGE SELECTION
    useEffect(() => {
        if (stationToShow) {
            if (chosenSelectedPumps[stationToShow.key]?.svg === undefined) {
                const body = {
                    pump_id: stationToShow.pump_id,
                    head: stationToShow.head,
                    flow: stationToShow.flow,
                    dp_work_scheme_id: stationToShow.dp_work_scheme_id || undefined,
                    pumps_count: stationToShow.pumps_count || undefined,
                    main_pumps_count: stationToShow.main_pumps_count || undefined,
                    pumpable_type: pumpableType(),
                }
                try {
                    axios.request({
                        url: tRoute('selections.curves'),
                        method: 'POST',
                        data: body,
                    }).then(res => {
                        document.getElementById('for-graphic').innerHTML = res.data
                        chosenSelectedPumps[stationToShow.key] = {
                            ...chosenSelectedPumps[stationToShow.key],
                            svg: res.data,
                        }
                        setChosenSelectedPumps(chosenSelectedPumps)
                    })
                } catch (e) {
                }
            } else {
                document.getElementById('for-graphic').innerHTML = chosenSelectedPumps[stationToShow.key].svg
            }
        } else {
            setChosenSelectedPumps({})
        }
    }, [stationToShow])

    const showPumpInfo = async event => {
        event.preventDefault()
        if (chosenSelectedPumps[stationToShow.key]?.pump_info === undefined) {
            const data = await postRequest(tRoute('pumps.show', stationToShow.pump_id), {
                pumpable_type: pumpableType(),
                need_curves: false,
            })
            setPumpInfo(data)
            chosenSelectedPumps[stationToShow.key] = {
                ...chosenSelectedPumps[stationToShow.key],
                pump_info: data
            }
        } else {
            // console.log(pumpInfo, stationToShow, chosenSelectedPumps)
            if (pumpInfo.id === stationToShow.pump_id) {
                // console.log('equal')
                setPumpInfoVisible(true)
            } else {
                // console.log('new')
                setPumpInfo(chosenSelectedPumps[stationToShow.key].pump_info)
            }
        }
    }

    // IF ROUTE IS 'selections.show'
    useEffect(() => {
        if (selection) {
            setStationToShow(selection?.data.to_show)
        }
    }, [selection])

    return (
        <>
            <IndexContainer
                title={selection
                    ? selection.data.selected_pump_name
                    : pageTitle}
                extra={selection
                    ? <BackLink title={Lang.get('pages.selections.back.to_project')}
                                href={tRoute('projects.show', project_id)}/>
                    : project_id === "-1"
                        ? <BackLink
                            title={Lang.get('pages.selections.back.to_selections_dashboard')}
                            href={tRoute('selections.dashboard', project_id)}
                        />
                        : [
                            <BackLink
                                title={Lang.get('pages.selections.back.to_selections_dashboard')}
                                href={tRoute('selections.dashboard', project_id)}
                            />,
                            <BackLink
                                title={Lang.get('pages.selections.back.to_project')}
                                href={tRoute('projects.show', project_id)}
                            />
                        ]
                }
            >
                <Row justify="space-around" gutter={[8, 16]}>
                    <Col xxl={hideIcons ? 2 : 3} xl={hideIcons ? 3 : 4}>
                        <Row>
                            <Col xs={24}>
                                <Checkbox
                                    checked={showBrandsList}
                                    onChange={e => {
                                        setShowBrandsList(e.target.checked)
                                    }}
                                >
                                    <span style={{marginLeft: hideIcons ? 0 : 5}}>
                                        {Lang.get('pages.selections.single_pump.grouping')}
                                    </span>
                                </Checkbox>
                            </Col>
                            {/*<Col xs={24}>*/}
                            {/*    <Checkbox indeterminate={indeterminate} onChange={onCheckAllChange} checked={checkedAll}>*/}
                            {/*        Check all*/}
                            {/*    </Checkbox>*/}
                            {/*</Col>*/}
                            <Col xs={24}>
                                <Checkbox checked={hideIcons} onChange={e => {
                                    setHideIcons(e.target.checked)
                                }}>
                                    <span style={{marginLeft: hideIcons ? 0 : 5}}>
                                        {Lang.get('pages.selections.single_pump.hide_icons')}
                                    </span>
                                </Checkbox>
                            </Col>
                        </Row>
                        <Divider style={margin.all("5px 0 5px")}/>
                        {showBrandsList && <div style={{overflow: "auto", maxHeight: "70vh"}}>
                            <Tree
                                defaultExpandAll
                                checkable
                                treeData={brandsSeriesTree}
                                checkedKeys={brandsSeriesListValues}
                                onCheck={brandsSeriesListValuesCheckedHandler}
                            />
                        </div>}
                        {!showBrandsList && <div style={{overflow: "auto", maxHeight: "70vh"}}>
                            <Checkbox.Group
                                className='series-checkboxes'
                                options={brandsSeriesList}
                                value={brandsSeriesListValues}
                                onChange={brandsSeriesListValuesCheckedHandler}
                            />
                        </div>}
                    </Col>
                    <Col xxl={hideIcons ? 22 : 21} xl={hideIcons ? 21 : 20}>
                        {/*<Row gutter={[10, 10]}>*/}
                        <Form
                            name={fullSelectionFormName}
                            form={fullSelectionForm}
                            layout="vertical"
                        >
                            {/*<Col xs={24}>*/}
                            <Row gutter={10}>
                                {/* BRANDS */}
                                <Col xs={widths.brands}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        name="pump_brand_ids"
                                        initialValue={brandsValue}
                                        label={Lang.get('pages.selections.single_pump.brands')}
                                    >
                                        <MultipleSelection
                                            placeholder={Lang.get('pages.selections.single_pump.brands')}
                                            style={fullWidth}
                                            options={selection_props.brandsWithSeries}
                                            onChange={values => {
                                                setBrandsValue(values)
                                            }}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                <Col xs={3}>
                                    {/* TEMPERATURE */}
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={Lang.get('pages.selections.single_pump.fluid_temp')}
                                        name="fluid_temperature"
                                        initialValue={temperatureValue}
                                        style={margin.bottom(10)}
                                        tooltip={<>
                                            <span>{Lang.get('pages.selections.single_pump.temp_tooltip.red')}</span><br/>
                                            <span>{Lang.get('pages.selections.single_pump.temp_tooltip.orange')}</span>
                                        </>}
                                    >
                                        <InputNumber
                                            disabled={fieldsDisabled}
                                            style={fullWidth}
                                            min={-50}
                                            max={200}
                                            onChange={temperatureChangeHandler()}
                                            placeholder={Lang.get('pages.selections.single_pump.fluid_temp')}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                {/* TYPES */}
                                <Col xs={widths.types}>
                                    <Form.Item
                                        className={reducedAntFormItemClassName}
                                        name="pump_type_ids"
                                        label={Lang.get('pages.selections.single_pump.types.label')}
                                        initialValue={selection?.data.pump_types}
                                        tooltip={Lang.get('pages.selections.single_pump.types.tooltip')}
                                    >
                                        <MultipleSelection
                                            placeholder={Lang.get('pages.selections.single_pump.types.label')}
                                            disabled={fieldsDisabled}
                                            style={fullWidth}
                                            options={selection_props.types}
                                            onChange={values => {
                                                setTypesValue(values)
                                            }}
                                        />
                                    </Form.Item>
                                </Col>
                                {/* APPLICATIONS */}
                                {selection_props.applications && <Col xs={widths.applications}>
                                    <Form.Item
                                        className={reducedAntFormItemClassName}
                                        name="pump_application_ids"
                                        label={Lang.get('pages.selections.single_pump.applications.label')}
                                        initialValue={selection?.data.pump_applications}
                                        tooltip={Lang.get('pages.selections.single_pump.applications.tooltip')}
                                    >
                                        <MultipleSelection
                                            placeholder={Lang.get('pages.selections.single_pump.applications.label')}
                                            disabled={fieldsDisabled}
                                            style={fullWidth}
                                            options={selection_props.applications}
                                            onChange={values => {
                                                setApplicationsValue(values)
                                            }}
                                        />
                                    </Form.Item>
                                </Col>}
                                <Col xs={3}>
                                    {/* POWER ADJUSTMENT */}
                                    <Form.Item
                                        className={reducedAntFormItemClassName}
                                        label={Lang.get('pages.selections.single_pump.power_adjustments')}
                                        name="power_adjustment_ids"
                                        initialValue={powerAdjustmentValue}
                                        style={marginBottomTen}
                                    >
                                        <MultipleSelection
                                            placeholder={Lang.get('pages.selections.single_pump.power_adjustments')}
                                            disabled={fieldsDisabled}
                                            style={{...fullWidth, marginTop: 0}}
                                            options={selection_props.powerAdjustments}
                                            onChange={values => {
                                                setPowerAdjustmentValue(values)
                                            }}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={24}>
                                    <Divider style={margin.all("10px 0 10px")}/>
                                </Col>
                                {/* VOLUME FLOW */}
                                <Col span={2}>
                                    <RequiredFormItem
                                        label={Lang.get('pages.selections.single_pump.consumption')}
                                        name="flow"
                                        initialValue={selection?.data.flow}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <InputNumber
                                            placeholder={Lang.get('pages.selections.single_pump.consumption')}
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={1}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                {/* DELIVERY HEAD */}
                                <Col span={2}>
                                    <RequiredFormItem
                                        label={Lang.get('pages.selections.single_pump.pressure')}
                                        name="head"
                                        initialValue={selection?.data.head}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <InputNumber
                                            placeholder={Lang.get('pages.selections.single_pump.pressure')}
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={1}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                {/* DEVIATION */}
                                <Col span={2}>
                                    <Form.Item
                                        label={Lang.get('pages.selections.single_pump.deviation')}
                                        name="deviation"
                                        initialValue={selection?.data.deviation}
                                        className={reducedAntFormItemClassName}
                                        tooltip={Lang.get('pages.selections.single_pump.deviation_tooltip')}
                                    >
                                        <InputNumber
                                            placeholder={Lang.get('pages.selections.single_pump.deviation')}
                                            style={fullWidth}
                                            min={-50}
                                            max={50}
                                            precision={1}
                                        />
                                    </Form.Item>
                                </Col>
                                {/* MAIN PUMPS COUNT */}
                                {widths.main_pumps_count && <Col span={widths.main_pumps_count}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label={Lang.get('pages.selections.single_pump.main_pumps_count')}
                                        name="main_pumps_counts"
                                        initialValue={selection?.data.main_pumps_counts}
                                    >
                                        <Checkbox.Group
                                            options={mainPumpsCountCheckboxesOptions}/>
                                    </RequiredFormItem>
                                </Col>}
                                {/* RESERVE PUMPS COUNT */}
                                {widths.reserve_pumps_count && <Col span={widths.reserve_pumps_count}>
                                    <Form.Item
                                        required name="reserve_pumps_count"
                                        label={Lang.get('pages.selections.single_pump.backup_pumps_count')}
                                        initialValue={selection?.data.reserve_pumps_count || 0}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Radio.Group value={0}>
                                            {[0, 1, 2, 3, 4].map(value => (
                                                <Radio key={'bp' + value}
                                                       value={value}>{value}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </Form.Item>
                                </Col>}
                                {widths.work_scheme && <Col span={widths.work_scheme}>
                                    {/* WORK SCHEME */}
                                    <Form.Item
                                        required
                                        name="dp_work_scheme_id"
                                        label={Lang.get('pages.selections.double_pump.work_scheme')}
                                        initialValue={selection?.data.dp_work_scheme_id || selection_props.workSchemes[0].id}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Radio.Group>
                                            {selection_props.workSchemes.map(scheme => (
                                                <Radio key={'ws' + scheme.id}
                                                       value={scheme.id}>{scheme.name}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </Form.Item>
                                </Col>}
                                {/* RANGE */}
                                <Col span={widths.range}>
                                    <Form.Item
                                        required
                                        name="range_id"
                                        label={Lang.get('pages.selections.single_pump.range.label')}
                                        initialValue={selection?.data.range_id || selection_props.selectionRanges[selection_props.selectionRanges.length - 1].id}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Radio.Group
                                            onChange={e => {
                                                setRangeDisabled(e.target.value !== selection_props.selectionRanges[selection_props.selectionRanges.length - 1].id)
                                            }}>
                                            {selection_props.selectionRanges.map(range => (
                                                <Radio key={'bp' + range.name}
                                                       value={range.id}>{range.name}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </Form.Item>
                                </Col>
                                <Col span={widths.range_slider}>
                                    {/* RANGE */}
                                    <Form.Item
                                        required
                                        name="custom_range"
                                        label={Lang.get('pages.selections.single_pump.range.custom.label')}
                                        initialValue={selection?.data.custom_range || [0, 100]}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Slider
                                            range
                                            disabled={rangeDisabled}
                                            tipFormatter={(value) => `${value}%`}
                                            // defaultValue={selection?.data.custom_range || [0, 100]}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={widths.buttons}>
                                    <Row gutter={[10, 0]}>
                                        <Col span={24}>
                                            <Checkbox checked={useAdditionalFilters} onClick={e => {
                                                setUseAdditionalFilters(e.target.checked)
                                            }}>{Lang.get('pages.selections.single_pump.additional_filters.checkbox')}</Checkbox>
                                        </Col>
                                        <Col span={12}>
                                            <Form.Item className={reducedAntFormItemClassName}>
                                                <SecondaryButton
                                                    style={fullWidth}
                                                    onClick={() => {
                                                        setFiltersDrawerVisible(true)
                                                    }}
                                                >
                                                    {Lang.get('pages.selections.single_pump.additional_filters.button')}
                                                </SecondaryButton>
                                            </Form.Item>
                                        </Col>
                                        <Col span={12}>
                                            <Form.Item className={reducedAntFormItemClassName}>
                                                <PrimaryButton
                                                    style={fullWidth}
                                                    onClick={makeSelectionHandler}
                                                    // htmlType="submit"
                                                    disabled={brandsValue.length === 0}
                                                    loading={loading}
                                                >
                                                    {Lang.get('pages.selections.single_pump.select')}
                                                </PrimaryButton>
                                            </Form.Item>
                                        </Col>
                                    </Row>

                                </Col>
                            </Row>
                        </Form>
                        <Row gutter={[10, 10]} style={{display: "flex", alignItems: 'stretch', marginTop: 16}}>
                            {/* TABLE */}
                            <Col xs={15}>
                                <RoundedCard
                                    className="table-rounded-card"
                                    type="inner"
                                    title={Lang.get('pages.selections.single_pump.table.title')}
                                >
                                    <SelectedPumpsTable
                                        selectedPumps={selectedPumps}
                                        setStationToShow={setStationToShow}
                                        loading={loading}
                                    />
                                </RoundedCard>
                            </Col>
                            {/* GRAPHIC */}
                            <Col xs={9}>
                                <RoundedCard
                                    className={'flex-rounded-card'}
                                    // style={{height: "100%"}}
                                    type="inner"
                                    title={stationToShow?.name}
                                    extra={stationToShow && <Space>
                                        {has('selection_export') && <a onClick={e => {
                                            e.preventDefault()
                                            setExportDrawerVisible(true)
                                        }}>{Lang.get('pages.selections.single_pump.graphic.export')}</a>}
                                        <a onClick={showPumpInfo}>
                                            {Lang.get('pages.selections.single_pump.graphic.info')}>>
                                        </a>
                                    </Space>}
                                >
                                    <div id="for-graphic"/>
                                </RoundedCard>
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </IndexContainer>
            <BoxFlexEnd style={margin.top(16)}>
                <Space size={8}>
                    <SecondaryButton onClick={() => {
                        Inertia.get(tRoute(project_id !== '-1'
                            ? 'projects.show'
                            : 'projects.index',
                            project_id))
                    }}>
                        {Lang.get('pages.selections.single_pump.exit')}
                    </SecondaryButton>
                    {project_id !== "-1" && <>
                        <PrimaryButton
                            disabled={!stationToShow || !updated}
                            onClick={addSelectionToProjectClickHandler('post')}
                            loading={addLoading}
                        >
                            {Lang.get('pages.selections.single_pump.add')}
                        </PrimaryButton>
                        {selection && <PrimaryButton
                            disabled={!stationToShow || !updated}
                            onClick={addSelectionToProjectClickHandler('put')}
                            loading={addLoading}
                        >
                            {Lang.get('pages.selections.single_pump.update')}
                        </PrimaryButton>}
                    </>}
                </Space>
            </BoxFlexEnd>
            <PumpPropsDrawer
                visible={pumpInfoVisible}
                setVisible={setPumpInfoVisible}
                pumpInfo={pumpInfo}
            />
            <FiltersDrawer {...filtersDrawerProps}/>
            {(stationToShow && has('selection_export')) && <ExportAtOnceSelectionDrawer
                visible={exportDrawerVisible}
                setVisible={setExportDrawerVisible}
                stationToShow={stationToShow}
                pumpableType={pumpableType}
            />}
        </>
    )
}
