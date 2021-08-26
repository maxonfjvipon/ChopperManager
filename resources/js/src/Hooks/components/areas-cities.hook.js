import React, {useEffect, useRef, useState} from "react";
import {useCheck} from "../check.hook";

export const useAreasCities = () => {
    const [areaValue, setAreaValue] = useState(null)
    const [areas, setAreas] = useState([])

    const [areasWithCities, setAreasWithCities] = useState([])
    const [cityValue, setCityValue] = useState(null)
    const [citiesToShow, setCitiesToShow] = useState([])

    const {isArrayEmpty} = useCheck()

    useEffect(() => {
        if (!isArrayEmpty(areasWithCities)) {
            setAreas(areasWithCities.map(area => {
                return {
                    id: area.id,
                    name: area.name
                }
            }))
        }
    }, [areasWithCities])

    useEffect(() => {
        setCityValue(null)
        if (areaValue) {
            setCitiesToShow(areasWithCities.find(area => area.id === areaValue).cities)
        }
    }, [areaValue])

    return {
        areasOptions: {
            placeholder: "Выберите область",
            options: areas,
            onChange: value => {
                setAreaValue(value)
            },
        },
        citiesOptions: {
            placeholder: "Выберите город",
            options: citiesToShow,
            disabled: areaValue == null,
            onChange: value => {
                setCityValue(value)
            }
        },

        setAreaValue,
        setAreasWithCities,
        setCityValue,
        citiesToShow,

        areaValue
    }
}
