import React from "react";
import {Selection} from "../Selection";
import Lang from "../../../../../../../resources/js/translation/lang";

export default function SinglePump() {
    return (
        <Selection
            pageTitle={Lang.get('pages.selections.single_pump.title_new')}
            widths={{
                brands: 4,
                types: 7,
                applications: 7,
                main_pumps_count: 4,
                reserve_pumps_count: 4,
                range: 3,
                range_slider: 3,
                buttons: 4
            }}/>
    )
}
