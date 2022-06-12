import React from 'react'
import {PumpStationSelection} from "./PumpStationSelection";

export default function WSAuto() {
    return <PumpStationSelection
        title="Автоматический подбор станции водснабжения"
        widths={{
            flow: 4,
            head: 4,
            deviation: 4,
            main_pumps_count: 6,
            reserve_pumps_count: 6,
            control_systems: 5,
            pump_brands: 5,
            pump_series: 6,
            collectors: 5,
            button: 3
        }}
    />
}
