import React from 'react'
import {PumpStationSelection} from "./PumpStationSelection";

export default function AFAuto() {
    return <PumpStationSelection
        title="Автоматический подбор станции пожаротушения"
        widths={{
            flow: 4,
            head: 4,
            deviation: 4,
            main_pumps_count: 6,
            reserve_pumps_count: 6,
            avr: 6,
            gate_valves_count: 6,
            kkv: 6,
            on_street: 6,
            control_systems: 6,
            pump_brands: 6,
            pump_series: 7,
            collectors: 5,
            jockey: {
                flow: 4,
                head: 4,
                brand: 4,
                series: 4,
                pump: 4
            },
            button: 4
        }}
    />
}
