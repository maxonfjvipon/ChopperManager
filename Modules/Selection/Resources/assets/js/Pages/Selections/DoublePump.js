import React from "react";
import {Selection} from "../Selection";
import Lang from "../../../../../../../resources/js/translation/lang";

export default function DoublePump() {
    return (
        <Selection
            pageTitle={Lang.get('pages.selections.double_pump.title_new')}
            widths={{
                brands: 5,
                types: 13,
                work_scheme: 5,
                range: 3,
                range_slider: 4,
                buttons: 6
            }}/>
    )
}
