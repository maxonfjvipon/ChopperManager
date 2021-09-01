import {VictoryAxis, VictoryChart, VictoryLegend, VictoryLine, VictoryScatter, VictoryVoronoiContainer} from "victory";
import React, {useMemo, useState} from "react";
import {useLang} from "./lang.hook";
// import Paper from "@material-ui/core/Paper";

export const useGraphic = () => {
    const [stationToShow, setStationToShow] = useState(null)
    const [workingPoint, setWorkingPoint] = useState(null)
    const Lang = useLang()

    const defaultDiagramId = "chart"

    const Diagram = ({multiline = false, toShow, width, height, id, workingPoint}) => {
        const diagramLine = (line, index) => (
            <VictoryLine
                key={"vline" + index}
                interpolation="linear" data={line}
                style={{data: {stroke: "black"}}}
            />
        )
        const scatterStyle = color => {
            return {
                data: {fill: color},
                labels: {fill: color}
            }
        }
        return useMemo(() => (
                <VictoryChart
                    width={width}
                    height={height - 20}
                    domain={{y: [0, toShow?.yMax || 100]}} // todo
                    containerComponent={<VictoryVoronoiContainer/>}
                    id={'victory-chart'}
                >
                    <VictoryAxis
                        orientation="bottom"
                        label="Расход, м³/час"
                    />
                    <VictoryAxis
                        dependentAxis
                        label="Напор, м"
                    />
                    {multiline
                        ? (toShow
                            ? [...toShow?.lines].map((line, index) => (
                                diagramLine(line, index)
                            )) : <></>)
                        : (diagramLine(toShow?.line || [], 1))
                    }
                    <VictoryLine
                        interpolation="linear"
                        data={
                            toShow?.systemPerformance || []
                        }
                        style={{data: {stroke: "red"}}}
                    />
                    <VictoryScatter
                        data={toShow != null
                            ? [{
                                x: +toShow.intersectionPoint.x,
                                y: +toShow.intersectionPoint.y
                            }]
                            : []}
                        size={6}
                        style={scatterStyle('green')}
                    />

                    <VictoryScatter
                        data={workingPoint ? [{
                            x: +workingPoint.x,
                            y: +workingPoint.y
                        }] : []}
                        size={6}
                        style={scatterStyle('red')}
                    />
                    <VictoryLegend
                        x={450} y={20}
                        centerTitle
                        orientation="vertical"
                        gutter={20}
                        colorScale={["red", "green"]}
                        data={(workingPoint && toShow) ? [
                            {name: "Рабочая точка\nРасход: " + workingPoint.x + "\nНапор: " + workingPoint.y},
                            {
                                name: "Точка пересечения\nРасход: " + (toShow.intersectionPoint.x).toFixed(1)
                                    + "\nНапор: " + (toShow.intersectionPoint.y).toFixed(1)
                            }
                        ] : []}
                    />
                </VictoryChart>
            ),
            // <Paper id={id} style={{height: height + 20, marginBottom: 10}} elevation={2}>

            // </Paper>,
            [toShow, workingPoint]
        )
    }

    const PSHCDiagram = ({multiline, width = 600, height = 500}) => {
        return <Diagram
            id={defaultDiagramId}
            toShow={stationToShow}
            multiline={multiline}
            height={height}
            width={width}
            workingPoint={workingPoint}
        />
    }

    return {
        PSHCDiagram,
        workingPoint,
        setWorkingPoint,
        stationToShow,
        setStationToShow,
    }
}
