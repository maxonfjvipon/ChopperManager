import React from 'react'
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";

export const PumpsTable = ({columns, pumps, showPumpClickHandler, loading}) => {
    const {has} = usePermissions()

    return (
        <TTable
            columns={columns}
            dataSource={pumps}
            doubleClickHandler={has('pump_show') && showPumpClickHandler}
            scroll={{x: 4000, y: "70vh"}}
            loading={loading}
        />
    )
}
