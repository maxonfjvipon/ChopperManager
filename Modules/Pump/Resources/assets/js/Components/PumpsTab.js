import React, {useEffect, useState} from 'react'
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";

export const PumpsTab = ({
                             columns,
                             filter_data,
                             pumpable_type,
                             setBrandsToShow,
                             setSeriesToShow,
                             setPumpInfo,
                         }) => {
    const {postRequest, loading} = useHttp()
    const {has} = usePermissions()
    const tRoute = useTransRoutes()

    const [pumps, setPumps] = useState([])

    const showPumpClickHandler = id => () => {
        postRequest(tRoute('pumps.show', id), {
            pumpable_type,
            need_curves: true,
        }).then(data => {
            setPumpInfo(data)
        })
    }

    useEffect(() => {
        postRequest(tRoute('pumps.load'), {
            pumpable_type,
        }).then(pumps => {
            setPumps(pumps)
        })
    }, [])

    useEffect(() => {
        if (pumps.length > 0) {
            setBrandsToShow(filter_data.brands
                .filter(brand => pumps
                    .findIndex(pump => pump.brand === brand.value) !== -1))
            setSeriesToShow(filter_data.series
                .filter(series => pumps
                    .findIndex(pump => pump.series === series.value) !== -1))
        }
    }, [pumps])

    return (
        <TTable
            columns={[...columns, {
                key: 'actions', width: "1%", render: (_, record) => {
                    return (
                        <TableActionsContainer>
                            {has('pump_show') && <View clickHandler={showPumpClickHandler(record.id)}/>}
                        </TableActionsContainer>
                    )
                }
            }]}
            dataSource={pumps}
            clickRecord
            doubleClickHandler={has('pump_show') && showPumpClickHandler}
            scroll={{x: 4000, y: "70vh"}}
            loading={loading}
        />
    )
}