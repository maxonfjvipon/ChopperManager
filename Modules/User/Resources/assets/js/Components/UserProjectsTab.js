import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {Inertia} from "@inertiajs/inertia";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import Lang from "../../../../../../resources/js/translation/lang";
import {Tooltip} from "antd";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";

export const UserProjectsTab = ({projects}) => {
    const {has} = usePermissions()
    const tRoute = useTransRoutes()
    const {compareDate} = useDate()

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    const columns = [
        {
            title: Lang.get('pages.projects.index.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
            defaultSortOrder: ['ascend']
        },
        {
            title: Lang.get('pages.projects.index.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '70%',
            sorter: (a, b) => a.name.localeCompare(b.name)
        },
        {
            title: Lang.get('pages.projects.index.table.count'),
            dataIndex: 'selections_count',
            sorter: (a, b) => a.selections_count - b.selections_count
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('project_show') && <View clickHandler={showProjectHandler(record.id)}/>}
                    </TableActionsContainer>
                )
            }
        },
    ]

    return (
        <TTable
            columns={columns}
            dataSource={projects}
            doubleClickHandler={has('project_show') && showProjectHandler}
        />
    )
}
