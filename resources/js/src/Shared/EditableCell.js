import {Form} from "antd";

export const EditableCell = ({
                                 editing,
                                 dataIndex,
                                 title,
                                 record,
                                 index,
                                 children,
                                 input,
                                 // form,
                                 // handleSave,
                                 ...restProps
                             }) => {
    return (
        <td {...restProps}>
            {editing ? (
                <Form.Item
                    name={dataIndex}
                    style={{margin: 0}}
                    // rules={dataIndex !== 'comment' && [rules.required]}
                >
                    {input(record)}
                </Form.Item>
            ) : (
                children
            )}
        </td>
    );
};
