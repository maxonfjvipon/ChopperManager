import React, {useContext, useEffect, useRef, useState} from "react";
import {Form, InputNumber, Table} from "antd";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../../../../resources/js/translation/lang";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {usePage} from "@inertiajs/inertia-react";
import {InputNum} from "../../../../../../resources/js/src/Shared/Inputs/InputNum";

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
                                formatter={value => value.replace(',', '.')}
                                parser={value => value.replace(',', '.')}
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

export const DiscountsTab = ({userInfo}) => {
    const {discounts} = userInfo || usePage().props

    const discountsForm = 'discounts-form'
    const tRoute = useTransRoutes()

    const discountsColumns = [
        {
            title: Lang.get('pages.profile.discounts.name'),
            dataIndex: 'name',
            key: 'brand-name',
            width: "70%",
            editable: false
        },
        {
            title: Lang.get('pages.profile.discounts.discount'),
            dataIndex: 'value',
            key: 'discount',
            editable: !!!userInfo,
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
        Inertia.post(tRoute('profile.discount.update'), row, {
            only: ['discounts'],
        })
    }

    return (
        <RoundedCard
            type="inner"
            title={Lang.get('pages.profile.discounts.tab')}
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
                />
            </Form>
        </RoundedCard>
    )
}
