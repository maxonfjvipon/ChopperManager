import React from "react";
import {Selection} from "./Selection";

export const MultipleSelection = ({...rest}) => {
    return <Selection mode="multiple" {...rest}/>
}
