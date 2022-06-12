import React from 'react'
import {PumpStationSelection} from "./PumpStationSelection";

export default function WSHandle() {
    return <PumpStationSelection
        title="Ручной подбор станции водснабжения"
        widths={{
            flow: 6,
            head: 6,
            main_pumps_count: 6,
            reserve_pumps_count: 6,
            control_systems: 8,
            pump_brands: 3,
            pump_series: 3,
            pump: 3,
            collectors: 4,
            button: 3
        }}
    />
}
