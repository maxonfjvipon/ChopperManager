import React from 'react'
import {PumpStationSelection} from "./PumpStationSelection";

export default function AFHandle() {
    return <PumpStationSelection
        title="Ручной подбор станции пожаротушения"
        widths={{
            flow: 6,
            head: 6,
            main_pumps_count: 6,
            reserve_pumps_count: 6,
            avr: 6,
            gate_valves_count: 6,
            kkv: 6,
            on_street: 6,
            control_systems: 8,
            pump_brands: 4,
            pump_series: 4,
            pump: 4,
            collectors: 4,
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
