import React, {useContext, useEffect, useRef, useState} from "react";
import {Form, InputNumber} from "antd";
import {useStyles} from "./styles.hook";

const useEditable = () => {
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

    return {
        EditableRow,
        EditableCell
    }
}
