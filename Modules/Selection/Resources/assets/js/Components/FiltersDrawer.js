import {Checkbox, Col, Drawer, Form, InputNumber, Row} from "antd";
import Lang from "../../../../../../resources/js/translation/lang";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import React, {useState} from "react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {BoxFlexEnd} from "../../../../../../resources/js/src/Shared/Box/BoxFlexEnd";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";

const ConditionSelectionFormItem = ({options, initialValue = null, name, disabled}) => {
    const {fullWidth} = useStyles()
    return (
        <Form.Item name={name} initialValue={initialValue}>
            <Selection
                style={fullWidth}
                placeholder={Lang.get('pages.selections.single_pump.condition')}
                options={options}
                disabled={disabled || false}
            />
        </Form.Item>
    )
}

const ConditionLimitCheckboxFormItem = ({name, initialValue, children}) => {
    return (
        <Form.Item className="ant-form-item-reduced" name={name} valuePropName="checked"
                   initialValue={initialValue || false}>
            {children}
        </Form.Item>
    )
}

const LimitRow = ({children}) =>
    <Row gutter={[10, 0]}>
        {children}
    </Row>

const LimitCol = ({children}) =>
    <Col xs={12}>
        {children}
    </Col>

export const FiltersDrawer = ({form, setUseAdditionalFilters, selection, selection_props, visible, setVisible}) => {
    const {
        dns,
        limit_conditions,
        mains_connections,
        connection_types
    } = selection_props
        // .data

    const [limitChecks, setLimitChecks] = useState({
        power: selection?.data.power_limit_checked || false,
        ptpLength: selection?.data.ptp_length_limit_checked || false,
        dnSuction: selection?.data.dn_suction_limit_checked || false,
        dnPressure: selection?.data.dn_pressure_limit_checked || false,
    })

    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName, color} = useStyles()
    const nextBelowStyle = {...fullWidth, ...marginBottomTen}

    return (
        <Drawer
            width={600}
            placement="right"
            visible={visible}
            closable={false}
            onClose={() => {
                setVisible(false)
            }}
        >
            <Form layout="vertical" form={form}>
                <Row gutter={[10, 10]}>
                    <Col span={12}>
                        {/* CONNECTION TYPES */}
                        <Form.Item
                            label={Lang.get('pages.selections.single_pump.connection_type')}
                            name="connection_type_ids"
                            className={reducedAntFormItemClassName}
                            initialValue={selection?.data.connection_types}
                        >
                            <MultipleSelection
                                placeholder={Lang.get('pages.selections.single_pump.connection_type')}
                                style={nextBelowStyle}
                                options={connection_types}
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        {/* MAINS CONNECTION */}
                        <Form.Item
                            label={Lang.get('pages.selections.single_pump.phase')}
                            name="mains_connection_ids"
                            className={reducedAntFormItemClassName}
                            initialValue={selection?.data.mains_connections}
                        >
                            <MultipleSelection
                                placeholder={Lang.get('pages.selections.single_pump.phase')}
                                style={nextBelowStyle}
                                options={mains_connections.map(connection => {
                                    return {
                                        customValue: connection.full_value,
                                        ...connection,
                                    }
                                })}
                            />
                        </Form.Item>
                    </Col>
                    {/*LIMITS */}
                    {/*POWER LIMIT */}
                    <Col span={12}>
                        <ConditionLimitCheckboxFormItem
                            name="power_limit_checked"
                            initialValue={selection?.data.power_limit_checked}
                        >
                            <Checkbox
                                checked={limitChecks.power}
                                onChange={e => {
                                    setLimitChecks({
                                        ...limitChecks,
                                        power: e.target.checked
                                    })
                                }}
                            >
                                {Lang.get('pages.selections.single_pump.power_limit')}
                            </Checkbox>
                        </ConditionLimitCheckboxFormItem>
                        <LimitRow>
                            <LimitCol>
                                <ConditionSelectionFormItem
                                    options={limit_conditions}
                                    name="power_limit_condition_id"
                                    disabled={!limitChecks.power}
                                    initialValue={selection?.data.power_limit_condition_id}
                                />
                            </LimitCol>
                            <LimitCol>
                                <Form.Item
                                    name="power_limit_value"
                                    initialValue={selection?.data.power_limit_value}
                                >
                                    <InputNumber
                                        disabled={!limitChecks.power}
                                        style={fullWidth}
                                        placeholder={Lang.get('pages.selections.single_pump.power')}
                                    />
                                </Form.Item>
                            </LimitCol>
                        </LimitRow>
                    </Col>
                    {/*PTP LENGTH LIMIT */}
                    <Col span={12}>
                        <ConditionLimitCheckboxFormItem
                            name="ptp_length_limit_checked"
                            initialValue={selection?.data.ptp_length_limit_checked}
                        >
                            <Checkbox
                                checked={limitChecks.ptpLength}
                                onChange={e => {
                                    setLimitChecks({
                                        ...limitChecks,
                                        ptpLength: e.target.checked
                                    })
                                }}
                            >
                                {Lang.get('pages.selections.single_pump.ptp_length_limit')}
                            </Checkbox>
                        </ConditionLimitCheckboxFormItem>
                        <LimitRow>
                            <LimitCol>
                                <ConditionSelectionFormItem
                                    options={limit_conditions}
                                    name="ptp_length_limit_condition_id"
                                    disabled={!limitChecks.ptpLength}
                                    initialValue={selection?.data.ptp_length_limit_condition_id}
                                />
                            </LimitCol>
                            <LimitCol>
                                <Form.Item
                                    name="ptp_length_limit_value"
                                    initialValue={selection?.data.ptp_length_limit_value}
                                >
                                    <InputNumber
                                        disabled={!limitChecks.ptpLength}
                                        style={fullWidth}
                                        placeholder={Lang.get('pages.selections.single_pump.ptp_length')}
                                    />
                                </Form.Item>
                            </LimitCol>
                        </LimitRow>
                    </Col>
                    {/*DN SUCTION */}
                    <Col span={12}>
                        <ConditionLimitCheckboxFormItem
                            name="dn_suction_limit_checked"
                            initialValue={selection?.data.dn_suction_limit_checked}
                        >
                            <Checkbox
                                checked={limitChecks.dnSuction}
                                onChange={e => {
                                    setLimitChecks({
                                        ...limitChecks,
                                        dnSuction: e.target.checked
                                    })
                                }}
                            >
                                {Lang.get('pages.selections.single_pump.dn_input_limit')}
                            </Checkbox>
                        </ConditionLimitCheckboxFormItem>
                        <LimitRow>
                            <LimitCol>
                                <ConditionSelectionFormItem
                                    initialValue={selection?.data.dn_suction_limit_condition_id}
                                    options={limit_conditions}
                                    name="dn_suction_limit_condition_id"
                                    disabled={!limitChecks.dnSuction}
                                />
                            </LimitCol>
                            <LimitCol>
                                <Form.Item
                                    name="dn_suction_limit_id"
                                    initialValue={selection?.data.dn_suction_limit_id}
                                >
                                    <Selection
                                        placeholder={Lang.get('pages.selections.single_pump.dn_input')}
                                        options={dns}
                                        disabled={!limitChecks.dnSuction}
                                        style={fullWidth}
                                    />
                                </Form.Item>
                            </LimitCol>
                        </LimitRow>
                    </Col>
                    {/*DN PRESSURE LIMIT */}
                    <Col span={12}>
                        <ConditionLimitCheckboxFormItem
                            name="dn_pressure_limit_checked"
                            initialValue={selection?.data.dn_pressure_limit_checked}
                        >
                            <Checkbox
                                checked={limitChecks.dnPressure}
                                onChange={e => {
                                    setLimitChecks({
                                        ...limitChecks,
                                        dnPressure: e.target.checked
                                    })
                                }}
                            >
                                {Lang.get('pages.selections.single_pump.dn_output_limit')}
                            </Checkbox>
                        </ConditionLimitCheckboxFormItem>
                        <LimitRow>
                            <LimitCol>
                                <ConditionSelectionFormItem
                                    initialValue={selection?.data.dn_pressure_limit_condition_id}
                                    options={limit_conditions}
                                    name="dn_pressure_limit_condition_id"
                                    disabled={!limitChecks.dnPressure}
                                />
                            </LimitCol>
                            <LimitCol>
                                <Form.Item
                                    name="dn_pressure_limit_id"
                                    initialValue={selection?.data.dn_pressure_limit_id}
                                >
                                    <Selection
                                        placeholder={Lang.get('pages.selections.single_pump.dn_output')}
                                        options={dns}
                                        disabled={!limitChecks.dnPressure}
                                        style={fullWidth}
                                    />
                                </Form.Item>
                            </LimitCol>
                        </LimitRow>
                    </Col>
                </Row>
                <BoxFlexEnd>
                    <PrimaryButton onClick={() => {
                        setUseAdditionalFilters(true)
                        setVisible(false)
                    }}>
                        {Lang.get('pages.selections.single_pump.additional_filters.apply')}
                    </PrimaryButton>
                </BoxFlexEnd>
            </Form>
        </Drawer>
    )
}
