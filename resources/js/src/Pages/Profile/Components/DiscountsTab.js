import React, {useContext, useEffect, useRef, useState} from "react";
import {Card, Col, Form, InputNumber, Row, Table, Tabs} from "antd";
import {PrimaryButton} from "../../../Shared/Buttons/PrimaryButton";
import {useStyles} from "../../../Hooks/styles.hook";
import {Inertia} from "@inertiajs/inertia";
import {useLang} from "../../../Hooks/lang.hook";


const EditableContext = React.createContext(null)

const EditableRow = ({index, ...props}) => {
    const [form] = Form.useForm();
    return (
        <Form size="small" form={form} component={false}>
            <EditableContext.Provider value={form}>
                <tr {...props} />
            </EditableContext.Provider>
        </Form>
    );
}

const EditableCell = ({editable, title, dataIndex, record, children, handleSave, ...rest}) => {
    const [editing, setEditing] = useState(false);
    const inputRef = useRef();
    const form = useContext(EditableContext)
    const {fullWidth} = useStyles()

    useEffect(() => {
        if (editing) {
            inputRef.current.focus();
        }
    }, [editing]);

    const toggleEdit = () => {
        setEditing(!editing);
        form.setFieldsValue({[dataIndex]: record[dataIndex]});
    };

    const save = async () => {
        try {
            const values = await form.validateFields();
            toggleEdit();
            handleSave({...record, ...values});
        } catch (errInfo) {
            console.log('Save failed:', errInfo);
        }
    };

    return (
        editable
            ? <td {...rest}>
                {editing
                    ? (
                        <Form.Item
                            className={"ant-form-item-reduced"}
                            name={dataIndex}
                        >
                            <InputNumber
                                size="small"
                                max={100}
                                min={0}
                                style={fullWidth}
                                ref={inputRef}
                                onPressEnter={save}
                                onBlur={save}
                            />
                        </Form.Item>
                    )
                    : (
                        <div className="editable-cell-value-wrap" onClick={toggleEdit}>
                            {children}
                        </div>
                    )
                }
            </td>
            : <td {...rest}>{children}</td>
    )
}

export const DiscountsTab = ({discounts}) => {
    const {fullWidth} = useStyles()
    const discountsForm = 'discounts-form'
    const Lang = useLang()

    const discountsColumns = [
        {title: Lang.get('pages.profile.discounts.name'), dataIndex: 'name', key: 'producer-name', width: "70%", editable: false},
        {
            title: Lang.get('pages.profile.discounts.discount'),
            dataIndex: 'value',
            key: 'discount',
            editable: true,
        }
    ].map(col => {
        return {
            ...col,
            onCell: record => ({
                record,
                editable: col.editable || false,
                dataIndex: col.dataIndex,
                title: col.title,
                handleSave: discountsSaveHandler
            })
        }
    })

    const discountsSaveHandler = row => {
        Inertia.post(route('users.discount.update'), row, {
            only: ['discounts'],
        })
    }

    return (
        // <Tabs.TabPane tab="Скидки производителей" key="producers-discounts">
            <Row justify="space-around" align="middle" gutter={[0, 0]}>
                <Col md={24} lg={20} xl={15} xxl={12}>
                    <Card
                        title={Lang.get('pages.profile.discounts.tab')}
                        style={{...fullWidth, borderRadius: 10}}
                    >
                        <Form name={discountsForm} onFinish={discountsSaveHandler}>
                            <Table
                                rowClassName="editable-row"
                                components={{
                                    body: {
                                        cell: EditableCell,
                                        row: EditableRow
                                    },
                                }}
                                dataSource={discounts}
                                columns={discountsColumns}
                                size="small"
                                scroll={{y: 570}}
                            />
                        </Form>
                    </Card>
                </Col>
            </Row>
        // </Tabs.TabPane>
    )
}
